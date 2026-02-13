<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Configuration;
use App\Models\Debt;
use App\Models\DetailConfiguration;
use App\Models\Product;
use App\Models\Sale;
use App\Models\ProductReturn;
use App\Models\SalesDetail;
use App\Models\Customer;
use App\Models\Variant;
use Illuminate\Http\Request;
use App\Models\SubFeature;
use App\Models\DiscountRule;
use Session;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $features = SubFeature::where('features_id', 1)->where('is_active', 1)->get();
        $activeConfigs = [];  
        foreach($features as $key=>$feature){
            $configurations = Configuration::where('sub_features_id', $feature->id)->where('is_active', 1)->get();
            foreach($configurations as $key=>$config){
                $activeConfigs[] = $config->id;
            }
        }
        $activeDetails = [];
        foreach($activeConfigs as $configId){
            $details = DetailConfiguration::where('configurations_id', $configId)->where('is_active', 1)->get();
            foreach($details as $key=>$detail){
                $activeDetails[] = $detail->id;
            }
        }

        $discountRules = DiscountRule::all();

        $customers = Customer::whereNot('id', 1)->get();

        $products = Product::with('variants')->get();

        $categories = Category::all();
        
        return view('pos.app', compact('features','activeConfigs', 'activeDetails', 'products', 'customers', 'discountRules', 'categories'));
    }

    private function checkAndApplyBonus($cart)
    {
        $cleanCart = [];
        foreach ($cart as $key => $item) {
            if (!isset($item['is_bonus']) || $item['is_bonus'] !== true) {
                $cleanCart[$key] = $item;
            }
        }
        $cart = $cleanCart;

        $rules = DiscountRule::whereNotNull('bonus_product_id')->get();

        foreach ($cart as $item) {
            $qty = $item['quantity'];
            $prodId = $item['id'];
            $catId = $item['categories_id'] ?? null;

            $matchedRule = $rules->filter(function($rule) use ($prodId, $catId, $qty) {
                $isProductMatch = ($rule->products_id == $prodId);
                $isCategoryMatch = ($rule->categories_id == $catId);
                return ($isProductMatch || $isCategoryMatch) && $qty >= $rule->bonus_minimum;
            })->first();

            if ($matchedRule) {
                $bonusProd = Product::find($matchedRule->bonus_product_id);
                
                if ($bonusProd) {
                    $bonusId = "bonus-" . $bonusProd->id; 
                    
                    $cart[$bonusId] = [
                        "id" => $bonusProd->id,
                        "name" => "[BONUS] " . $bonusProd->name,
                        "price" => 0,
                        "quantity" => $matchedRule->bonus_quantity,
                        "type" => "product",
                        "discount" => 0,
                        "discount_type" => 1,
                        "is_bonus" => true,
                        "categories_id" => $bonusProd->categories_id
                    ];
                }
            }
        }

        return $cart;
    }

    public function setSession(Request $request)
    {
        $productFound = false;
        $products = Session::get('products', []);

        foreach ($products as &$product) {
            if (isset($product['id']) && $product['id'] === $request->id && isset($product['type']) && $product['type'] === $request->type) {
                $product['quantity'] += $request->quantity;
                $productFound = true;
                break;
            }
        }

        if (!$productFound) {
            $catId = null;
            if($request->type == 'variant') {
                $v = Variant::find($request->id);
                $catId = $v ? $v->product->categories_id : null;
            } else {
                $p = Product::find($request->id);
                $catId = $p ? $p->categories_id : null;
            }

            $products[] = [
                'id' => $request->id,
                'type' => $request->type,
                'name' => $request->name,
                'price' => $request->price,
                'quantity' => $request->quantity,
                'discount' => $request->discount ?? 0,
                'discount_type' => $request->discount_type ?? 1,
                'categories_id' => $catId
            ];
        }

        if (!Session::has('saleTotalDisc')) {
            Session::put('saleTotalDisc', 0);
        }

        $cart = $this->checkAndApplyBonus($products);
        
        Session::put('products', $cart);

        return response()->json([
            'message'=>'Session set', 
            'products'=>Session('products'), 
            'saleTotalDisc'=>Session('saleTotalDisc', 0)
        ]);
    }

    public function setSaleTotalDisc(Request $request)
    {
        if($request->saleTotalDisc) {
            Session::put('saleTotalDisc', $request->saleTotalDisc);
        } elseif (!Session::has('saleTotalDisc')) {
            Session::put('saleTotalDisc', 0);
        }

        return response()->json([
            'message'=>'Session set', 
            'products'=>Session('products'), 
            'saleTotalDisc'=>Session('saleTotalDisc',0)
        ]);
    }

    public function updateQuantity(Request $request)
    {
        $productId = $request->id;
        $amount = $request->input('amount', 0);

        if ($amount <= 0) {
            $this->deleteSessionProduct($request);
        }

        $products = Session::get('products', []);
        $productFound = false;

        foreach ($products as &$product) {
            if (isset($product['id']) && $product['id'] === $productId) {
                $product['quantity'] = $amount;
                $productFound = true;
                break;
            }
        }

        if ($productFound) {
            Session::put('products', $products);
            return response()->json([
                'message' => 'Product quantity increased.', 
                'products' => $products, 
                'saleTotalDisc'=>Session('saleTotalDisc',0)
            ]);
        } else {
            return response()->json(['message' => 'Product not found in session.'], 404);
        }
    }

    public function deleteSessionProduct(Request $request)
    {
        $productIdToDelete = $request->id; 
        $products = Session::get('products', []);

        $updatedProducts = [];

        foreach ($products as $product) {
            if ($product['id'] !== $productIdToDelete) {
                $updatedProducts[] = $product;
            }
        }

        Session::put('products', $updatedProducts);

        return response()->json(['message' => 'Product deleted from session', 'products' => $updatedProducts]);
    }

    public function updateDiscount(Request $request)
    {
        $productId = $request->productId;
        $discount = $request->discount;
        $discountType = $request->discount_type ?? 1;
        $product = '';

        $cart = Session::get('products', []);

        foreach ($cart as $key => &$item) {
            if ((int)$item['id'] == (int)$productId) {
                $item['discount'] = $discount;
                $item['discount_type'] = $discountType;
                $product = $item;

                Session::put('products', $cart); 
                return response()->json([
                    'message' => 'Discount updated successfully.', 
                    'products' => $cart, 
                    'product' => $product,
                    'saleTotalDisc'=>Session('saleTotalDisc',0)
                ]);
            }
        }
        
        return response()->json(['success' => false, 'message' => 'Product not found in cart.'], 404);
    }

    public function riwayat(Request $request)
    {
        $features = SubFeature::where('features_id', 1)->where('is_active', 1)->get();
        $activeConfigs = [];  
        foreach($features as $key=>$feature){
            $configurations = Configuration::where('sub_features_id', $feature->id)->where('is_active', 1)->get();
            foreach($configurations as $key=>$config){
                $activeConfigs[] = $config->id;
            }
        }
        $activeDetails = [];
        foreach($activeConfigs as $configId){
            $details = DetailConfiguration::where('configurations_id', $configId)->where('is_active', 1)->get();
            foreach($details as $key=>$detail){
                $activeDetails[] = $detail->id;
            }
        }

        $status = "";
        $sales = Sale::where('sales_type', 'pos')->orderBy('id', 'desc');
        if($request->has('status')){
            $status = $request->query('status');
            if ($status == 'lunas') {
                $sales = $sales->where('total_debt', 0);
            } elseif($status == 'belum') {
                $sales = $sales->where('total_debt', '>', 0);
            }
        }
        
        $startDate = "";
        $endDate = "";
       
        if($request->has('start_date') && $request->has('end_date')) {
            $startDate = $request->query('start_date');
            $endDate = $request->query('end_date');
            $sales = $sales->whereBetween('date', [$startDate, $endDate]);
        }
        $sales = $sales->orderBy('date', 'desc')->get();
        
        return view('pos.history', compact('sales', 'activeConfigs', 'activeDetails', 'features', 'status', 'startDate', 'endDate'));
    }

    public function updateDebt(Request $request)
    {
        $saleId = $request->id;
        $paid = $request->paid; 

        $sale = Sale::find($saleId);
        if ($sale) {
            $sale->total_debt -= $paid;
            if ($sale->total_debt < 0) {
                $sale->total_debt = 0;
            }
            $sale->save();

            $debt = Debt::create([
                'sales_id' => $saleId,
                'paid' => $paid,
                'date' => now(),
            ]);
            return response()->json(['success' => true, 'debt'=>$sale->total_debt, 'message' => 'Debt updated successfully.']);
        } else {
            return response()->json(['success' => false, 'message' => 'Sale not found.'], 404);
        }
    }

    public function returnProduct(Request $request)
    {
        $id = $request->sale_id;
        $productId = $request->product_id;
        $variantId = $request->variant_id;
        $returnAmount = $request->amount ?? 0;
        $returnType = $request->type;
        $keterangan = $request->keterangan;

        $query = SalesDetail::where('sales_id', $id)->where('products_id', $productId);

        if ($variantId) {
            $query->where('variants_id', $variantId);
        }

        $detail = $query->first();

        if (!$detail) {
            return response()->json('Sale details not found.', 404);
        }

        $currentReturned = ProductReturn::where('sales_id', $id)
            ->where('products_id', $productId)
            ->when($variantId, function ($q) use ($variantId) {
                return $q->where('variants_id', $variantId);
            })
            ->sum('amount');

        if ($currentReturned >= $detail->amount) {
            return response()->json('All products have been returned.', 200);
        }

        if ($returnAmount <= 0 || ($currentReturned + $returnAmount) > $detail->amount) {
            return response()->json('Return amount invalid or exceeds purchased amount.', 400);
        }

        $returned = ProductReturn::create([
            'sales_id' => $id,
            'products_id' => $productId,
            'variants_id' => $variantId,
            'amount' => $returnAmount,
            'type' => $returnType,
            'keterangan' => $keterangan,
            'date' => now(),
        ]);

        $isPerpetual = Configuration::where('id', 17)->where('is_active', 1)->first();

        if ($returned) {
            $detail->total_return += $returnAmount;
            $detail->save();

            if ($returnType == 'Ganti barang') {
                if ($isPerpetual) {
                    if ($variantId) {
                        $variant = Variant::find($variantId);
                        if ($variant) {
                            $variant->stock -= $returnAmount;
                            $variant->save();
                        }
                    } else {
                        $product = Product::find($productId);
                        if ($product) {
                            $product->stock -= $returnAmount;
                            $product->save();
                        }
                    }
                }
            } 
            elseif ($returnType == 'Kurangi piutang') {
                $sale = Sale::find($id);
                if ($sale) {
                    $refundValue = $detail->price * $returnAmount;
                    $sale->total_debt -= $refundValue;
                    $sale->save();
                }
            }
        }

        return response()->json('Product returned successfully.', 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $hasTaxConfig = Configuration::where('id', 3)->where('is_active', 1)->exists();
        $taxRate = $hasTaxConfig ? 0 : 0.11;
        $discount = Session("saleTotalDisc", 0); 
        $products = Session('products', []);
        $total = 0;
        foreach($products as $product) {
            if($product['discount_type']== 2){
                $total += $product['price'] * $product['quantity'] * (1- ($product['discount'] / 100));
            } else{
                $total += $product['price'] * $product['quantity'] - $product['discount'];
            }
        }

        $total -= $discount;
        $tax = $total * $taxRate; 
        $grandTotal = $total + $tax;

        $s = Sale::create([
            'customers_id' => $request->customer_id == "" ? 1: $request->customer_id,
            'sales_type' => 'pos',
            'date' => now(),
            'shipping_date' => now(),
            'total' => $grandTotal,
            'tax' => $tax,
            'discount' => $discount ?? 0,
            'discount_type' => $request->discount_type ?? 1,
            'payment_methods' => $request->payment_method,
            'total_debt' => $request->payment_method == "piutang" ? $total : 0,
        ]);
        $isPerpetual = Configuration::where('id', 17)->where('is_active', 1)->first();
        foreach($products as $product) {
            $variantId = null;
            $productId = $product['id'];
    
            if (isset($product['type']) && $product['type'] == 'variant') {
                $variantId = $product['id'];
                $variant = Variant::find($variantId);
                if($variant) {
                    $productId = $variant->products_id;
                }
            }
    
            SalesDetail::create([
                'sales_id' => $s->id,
                'products_id' => $productId,
                'variants_id' => $variantId,
                'amount' => $product['quantity'],
                'price' => $product['price'],
                'discount' => $product['discount'] ?? 0,
                'discount_type' => $product['discount_type'] ?? null
            ]);
    
            if ($isPerpetual) {
                if ($variantId) {
                    $v = Variant::find($variantId);
                    if ($v) {
                        $v->stock -= $product['quantity'];
                        $v->save();
                    }
                } else {
                    $p = Product::find($productId);
                    if ($p) {
                        $p->stock -= $product['quantity'];
                        $p->save();
                    }
                }
            }
        }

        Session::forget('products');
        Session::forget('saleTotalDisc');
        return redirect()->route('pos.index')->with('success', 'Sale recorded successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $features = SubFeature::where('features_id', 1)->where('is_active', 1)->get();
        $activeConfigs = [];  
        foreach($features as $key=>$feature){
            $configurations = Configuration::where('sub_features_id', $feature->id)->where('is_active', 1)->get();
            foreach($configurations as $key=>$config){
                $activeConfigs[] = $config->id;
            }
        }
        $activeDetails = [];
        foreach($activeConfigs as $configId){
            $details = DetailConfiguration::where('configurations_id', $configId)->where('is_active', 1)->get();
            foreach($details as $key=>$detail){
                $activeDetails[] = $detail->id;
            }
        }
        
        $sale = Sale::with(['customer', 'salesDetails.product', 'debts'])
            ->where('id', $id)
            ->first();

        $returns = ProductReturn::where('sales_id', $id)->get();

        return view('pos.detail', compact('sale', 'activeConfigs', 'activeDetails', 'features', 'returns'));
    }
}
