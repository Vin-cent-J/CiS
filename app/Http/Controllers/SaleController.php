<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Configuration;
use App\Models\Customer;
use App\Models\Debt;
use App\Models\DetailConfiguration;
use App\Models\DiscountRule;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SalesDetail;
use App\Models\SubFeature;
use App\Models\Variant;
use Illuminate\Http\Request;
use App\Models\ProductReturn;
use Illuminate\Support\Facades\DB;
use Session;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
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
        $sales = Sale::where('sales_type', 'sales');
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
        $sales = $sales->orderBy('id', 'desc')->get();

        $products = Product::with('variants')->get();

        $categories = Category::all();
        
        return view('sales.app', compact( 'sales', 'activeConfigs', 'activeDetails', 'features', 'status', 'startDate', 'endDate', 'products', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $features = SubFeature::where('features_id', 2)->where('is_active', 1)->get();
        $activeConfigs = [];  
        foreach($features as $key=>$feature){
            $configurations = Configuration::where('sub_features_id', $feature->id)->where('is_active', 1)->get();
            foreach($configurations as $key=>$config){
                $activeConfigs[]=$config->id;
            }
        }
        $activeDetails = [];
        foreach($activeConfigs as $configId){
            $details = DetailConfiguration::where('configurations_id', $configId)->where('is_active', 1)->get();
            foreach($details as $key=>$detail){
                $activeDetails[] = $detail->id;
            }
        }
        $products = Product::with("variants")->get();
        $customers = Customer::whereNot('id', 1)->get();
        
        return view('sales.new', compact('features','activeConfigs', 'activeDetails', 'customers', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $total = 0;
        $totalTax = 0;
        $taxRate = 0.11;
        foreach(Session::get('sale-products', []) as $product) {
            if($product['discount_type']== 2){
                $total += $product['price'] * $product['quantity'] * (1- ($product['discount'] / 100));
            } else{
                $total += $product['price'] * $product['quantity'] - $product['discount'];
            }
        }

        $globalDiscount = $request->discount ?? 0;
    
        $taxableAmount = max(0, $total - $globalDiscount);

        $isTaxIncluded = Configuration::where('id', 11)->where('is_active', 1)->exists();
        $taxRate = $isTaxIncluded ? 0 : 0.11;
        $totalTax = $taxableAmount * $taxRate; 
        $grandTotal = $taxableAmount + $totalTax;

        $sale = Sale::create([
            'customers_id' => $request->customer == "" ? 1: $request->customer,
            'sales_type' => 'sales',
            'date' => now(),
            'shipping' => $request->shipping ?? '',
            'shipping_fee' => $request->shipping_fee ?? 0,
            'total' => $grandTotal,
            'discount' => $request->discount ?? 0,
            'discount_type' => $request->discount_type ?? 1,
            'payment_methods' => $request->payment_method,
            'total_debt' => $request->payment_method == "piutang" ? $total : 0,
            'tax' => $totalTax ?? 0,
        ]);
        $isPerpetual = Configuration::where('id', 17)->where('is_active', 1)->first();
        foreach (Session::get('sale-products', []) as $product) {
        
            $variantId = null;
            $productId = $product['id'];
    
            if (isset($product['type']) && $product['type'] == 'variant') {
                $variantId = $product['id']; 
                $variantModel = Variant::find($variantId);
                if ($variantModel) {
                    $productId = $variantModel->products_id;
                }
            }
    
            SalesDetail::create([
                'sales_id' => $sale->id,
                'products_id' => $productId, 
                'variants_id' => $variantId, 
                'amount' => $product['quantity'],
                'price' => $product['price'],
                'discount' => $product['discount'] ?? 0,
                'discount_type' => $product['discount_type'] ?? null,
                'total_return' => 0
            ]);
            
            if ($isPerpetual) {
                if ($variantId) {
                     $v = Variant::find($variantId);
                     if($v) {
                         $v->stock -= $product['quantity'];
                         $v->save();
                     }
                } else {
                     $p = Product::find($productId);
                     if($p) {
                         $p->stock -= $product['quantity'];
                         $p->save();
                     }
                }
            }
        }

        Session::forget('sale-products');
        
        return redirect()->route('sales.index');
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
        $products = Session::get('sale-products', []);
        $added = Session::get('added', []);

        $type = $request->type;
        $added[] = $type."-".$request->id;

        foreach ($products as &$product) {
            if (isset($product['id']) && $product['id'] === $request->id) {
                $product['quantity'] += $request->quantity;
                $productFound = true;
                break;
            }
        }

        if (!$productFound && $type == 'product') {
            $prod = Product::find($request->id);
            $products[] = [
                'id' => $prod->id,
                'type' => $type,
                'name' => $prod->name,
                'price' => $prod->price,
                'quantity' => $request->quantity,
                'discount' => $request->discount ?? 0,
                'discount_type' => $request->discount_type ?? 1,
            ];
        } else if (!$productFound && $type == 'variant') {
            $variant = Variant::find($request->id);
            $products[] = [
                'id' => $variant->id,
                'type' => $type,
                'name' => $variant->name,
                'product' => $variant->product->name,
                'price' => $variant->price,
                'quantity' => $request->quantity,
                'discount' => $request->discount ?? 0,
                'discount_type' => $request->discount_type ?? 1,
            ];
        }
        $cart = $this->checkAndApplyBonus($products);
        Session::put('added', $added);
        Session::put('sale-products', $cart);
        return response()->json(['message' => 'Session set', 'products' => Session('sale-products')]);
    }

    public function deleteSessionProduct(Request $request)
    {
        $productIdToDelete = (int)$request->id; 
        $products = Session::get('sale-products', []);

        $updatedProducts = [];

        $updatedProducts = array_filter($products, function ($product) use ($productIdToDelete) {
            return $product['id'] !== $productIdToDelete;
        });

        Session::put('sale-products', $updatedProducts);

        return response()->json(['message' => 'Product deleted from session', 'products' => $updatedProducts]);
    }

    public function updateDiscount(Request $request)
    {
        $productId = $request->productId;
        $discount = $request->discount;
        $discountId = $request->discount_type ?? 1;
        $product = '';

        $cart = Session::get('sale-products', []);

        foreach ($cart as $key => &$item) {
            if ((int)$item['id'] == (int)$productId) {
                $item['discount'] = $discount;
                $item['discount_type'] = $discountId;

                Session::put('sale-products', $cart); 
                return response()->json([
                    'success' => true,
                    'message' => 'Discount updated successfully.',
                    'products' => $cart,
                    'product' => $item 
                ]);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Product not found in cart.'
        ], 404);
    }

    public function updateQuantity(Request $request)
    {
        if ($request->quantity <= 0) {
            $this->deleteSessionProduct($request);
            return response()->json(['success' => true]);
        }

        $products = Session::get('sale-products', []);

        foreach ($products as &$product) {
            if ($product['id'] == $request->id) {
                $product['quantity'] = (int)$request->quantity;
                break;
            }
        }

        Session::put('sale-products', $products);
        return response()->json(['success' => true, 'products' => Session::get('sale-products')]);
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

            Debt::create([
                'sales_id' => $saleId,
                'paid' => $paid,
                'date' => now(),
            ]);

            return response()->json(['success' => true, 'message' => 'Debt updated successfully.']);
        } else {
            return response()->json(['success' => false, 'message' => 'Sale not found.'], 404);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $features = SubFeature::where('features_id', 2)->where('is_active', 1)->get();
        $activeConfigs = [];  
        foreach($features as $key=>$feature){
            $configurations = Configuration::where('sub_features_id', $feature->id)->where('is_active', 1)->get();
            foreach($configurations as $key=>$config){
                $activeConfigs[]=$config->id;
            }
        }
        $activeDetails = [];
        foreach($activeConfigs as $configId){
            $details = DetailConfiguration::where('configurations_id', $configId)->where('is_active', 1)->get();
            foreach($details as $key=>$detail){
                $activeDetails[] = $detail->id;
            }
        }
        $sale = Sale::with(['salesDetails.product', 'customer', 'debts'])->find($id);

        $returns = ProductReturn::where('sales_id', $id)->get();

        return view('sales.detail', compact('sale', 'features', 'activeConfigs', 'activeDetails', 'returns'));        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $sale = Sale::with(['salesDetails.product', 'customer'])->find($id);
        return view('sales.detail', compact('sale'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $s = Sale::find($id);
        return $s->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $s = Sale::find($id);
        return $s->delete();
    }
}
