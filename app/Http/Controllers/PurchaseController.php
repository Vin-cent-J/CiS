<?php

namespace App\Http\Controllers;

use App\Models\Configuration;
use App\Models\DetailConfiguration;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\PurchaseReturn;
use App\Models\SubFeature;
use App\Models\Supplier;
use App\Models\Variant;
use DB;
use Illuminate\Http\Request;
use Session;

class PurchaseController extends Controller
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

        $startDate = "";
        $endDate = "";
        $purchases = Purchase::with(['supplier']);
        if($request->has('status')){
            $status = $request->query('status');
            if ($status == 'lunas') {
                $purchases = $purchases->where('total_debt', 0);
            } elseif($status == 'belum') {
                $purchases = $purchases->where('total_debt', '>', 0);
            }
        }

        if($request->has('start_date') && $request->has('end_date')) {
            $startDate = $request->query('start_date');
            $endDate = $request->query('end_date');
            $purchases = $purchases->whereBetween('date', [$startDate, $endDate]);
        }

        $purchases = $purchases->orderBy('date', 'desc')->get();

        return view('purchase.app', compact('purchases', 'features', 'activeConfigs', 'activeDetails', 'startDate', 'endDate'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $features = SubFeature::where('features_id', 3)->where('is_active', 1)->get();
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

        $suppliers = Supplier::all();
        $products = Product::with('variants')->get();
        return view('purchase.new', compact('suppliers', 'products', 'features', 'activeConfigs', 'activeDetails'));
        
    }

    public function setSession(Request $request)
    {
        $productFound = false;
        $products = Session::get('purchase-products', []);
        
        $type = $request->type;

        foreach ($products as &$product) {
            if (isset($product['id']) && $product['id'] === $request->id && isset($product['type']) && $product['type'] === $type) {
                $product['quantity'] += $request->quantity;
                $productFound = true;
                break;
            }
        }

        if (!$productFound) {
            if ($type == 'product') {
                $prod = Product::find($request->id);
                if ($prod) {
                    $products[] = [
                        'id' => $prod->id,
                        'type' => $type,
                        'name' => $prod->name,
                        'price' => $prod->price,
                        'quantity' => $request->quantity,
                    ];
                }
            } else if ($type == 'variant') {
                $variant = Variant::find($request->id);
                if ($variant) {
                    $products[] = [
                        'id' => $variant->id,
                        'type' => $type,
                        'name' => $variant->name,
                        'product' => $variant->product->name,
                        'price' => $variant->price,
                        'quantity' => $request->quantity,
                    ];
                }
            }
        }

        Session::put('purchase-products', $products);
        return response()->json(['message' => 'Session set', 'products' => Session('purchase-products')]);
    }

    public function deleteSessionProduct(Request $request)
    {
        $productIdToDelete = (int) $request->id; 
        $products = Session::get('purchase-products', []);

        $updatedProducts = [];

        foreach ($products as $product) {
            if ($product['id'] !== $productIdToDelete) {
                $updatedProducts[] = $product;
            }
        }

        Session::put('purchase-products', $updatedProducts);

        return response()->json(['message' => 'Product deleted from session', 'products' => $updatedProducts]);
    }


    public function changeProduct(Request $request){
        $cart = Session::get('purchase-products', []);
        foreach ($cart as $key => &$item) {
            if ((int)$item['id'] == (int)$request->productId) {
                $product = Product::find($request->newId);
                $item['id'] = $product->id;
                $item['name'] = $product->name;
                $item['price'] = $product->price;
                $item['quantity'] = 1; 
                $item['discount'] = 0; 

                Session::put('purchase-products', $cart); 
            }
        }

        $added = collect($cart)->pluck('id')->all();

        Session::put('added', $added);
        return response()->json([
            'success' => true,
            'message' => $added
        ]);
    }

    public function updateQuantity(Request $request)
    {
        $products = Session::get('purchase-products', []);
        if ($request->quantity <= 0) {
            foreach ($products as $key => $product) {
                if ($product['id'] == $request->id) {
                    unset($products[$key]);
                    Session::put('purchase-products', array_values($products));
                    break;
                }
            }
            return response()->json(['success' => true]);
        }

        foreach ($products as &$product) {
            if ($product['id'] == $request->id) {
                $product['quantity'] = (int)$request->quantity;
                break;
            }
        }

        Session::put('purchase-products', $products);
        return response()->json(['success' => true, 'products' => Session::get('purchase-products')]);
    }

    public function updatePrice(Request $request)
    {
        $products = Session::get('purchase-products', []);

        foreach ($products as &$product) {
            if ($product['id'] == $request->id) {
                $product['price'] = (int)$request->price;
                break;
            }
        }

        Session::put('purchase-products', $products);
        return response()->json(['success' => true, 'products' => Session::get('purchase-products')]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $total = 0;
        $products = Session::get('purchase-products', []); 
        foreach($products as $product) {
            $total += $product['price'] * $product['quantity'];
        }

       $ins = Purchase::create([
            'suppliers_id' => $request->supplier,
            'date' => now(),
            'total' => $total,
            'discount' => $request->discount ?? 0,
            'shipping' => $request->shipping ?? '',
            'shipping_fee' => $request->shipping_fee ?? 0,
            'payment_methods' => $request->payment_method,
            'total_debt' => $request->payment_method == "hutang" ? $total : 0,
        ]);
        $isPerpetual = Configuration::where('id', 17)->where('is_active', 1)->first();
        foreach ($products as $product) {
            $variantId = null;
            $productId = $product['id'];

            if (isset($product['type']) && $product['type'] == 'variant') {
                $variantId = $product['id'];
                $v = Variant::find($variantId);
                if($v) $productId = $v->products_id;
            }

            PurchaseDetail::create([
                'purchases_id' => $ins->id,
                'products_id' => $product['id'],
                'variants_id' => $variantId,
                'amount' => $product['quantity'],
                'price' => $product['price'],
            ]);

            if ($isPerpetual) {
                if ($variantId) {
                    $v = Variant::find($variantId);
                    if($v) {
                        $v->stock += $product['quantity'];
                        $v->save();
                    }
                } else {
                    $p = Product::find($productId);
                    if($p) {
                        $p->stock += $product['quantity'];
                        $p->save();
                    }
                }
            }
        }

        Session::forget('purchase-products');
        Session::forget('p-added');

        return redirect()->route('purchase.index');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
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

        $returns = PurchaseReturn::where('purchases_id', $id)->get();

        $purchase = Purchase::with(['purchaseDetails.products', 'supplier'])->find($id);
        return view('purchase.detail', compact('purchase', 'features', 'activeConfigs', 'activeDetails', 'returns'));
    }

    public function updateDebt(Request $request)
    {
        $purchaseId = $request->id;
        $paid = $request->paid;

        $purchase = Purchase::find($purchaseId);
        if ($purchase) {
            $purchase->total_debt -= $paid;
            if ($purchase->total_debt < 0) {
                $purchase->total_debt = 0;
            }
            $purchase->save();
            return response()->json(['success' => true, 'message' => 'Debt updated successfully.']);
        } else {
            return response()->json(['success' => false, 'message' => 'purchase not found.'], 404);
        }
    }

    public function returnProduct(Request $request)
    {
        $id = $request->purchase_id;
        $productId = $request->product_id;
        $variantId = $request->variant_id;
        $returnAmount = $request->amount ?? 0;
        $returnType = $request->type;

        $detail = PurchaseDetail::where('purchases_id', $id)
            ->where('products_id', $productId);

        if ($variantId) {
            $detail->where('variants_id', $variantId);
        }

        $detail = $detail->first();
        
        $returned = PurchaseReturn::create([
            'purchases_id' => $id,
            'products_id' => $productId,
            'variants_id' => $variantId,
            'amount' => $returnAmount,
            'type' => $returnType,
        ]);
        $isPerpetual = Configuration::where('id', 17)->where('is_active', 1)->first();
        if($returned) {
            $product = Product::find($productId);
            $purchase = Purchase::find($id);
            if($returnType == 'Ganti barang') {
                if ($isPerpetual) {
                    if ($variantId) {
                        $v = Variant::find($variantId);
                        if($v) {
                            $v->stock -= $returnAmount;
                            $v->save();
                        }
                    } else {
                        $p = Product::find($productId);
                        if($p) {
                            $p->stock -= $returnAmount;
                            $p->save();
                        }
                    }
                }
            }
            else {
                 $updateQuery = PurchaseDetail::where('purchases_id', $id)
                    ->where('products_id', $productId);
    
                if ($variantId) {
                    $updateQuery->where('variants_id', $variantId);
                } else {
                    $updateQuery->whereNull('variants_id');
                }
                
                $updateQuery->increment('total_return', $returnAmount);
    
                if($returnType == 'Kurangi piutang') {
                    $purchase = Purchase::find($id);
                    $purchase->total_debt -= $detail->price * $returnAmount;
                    $purchase->save();
                }
            }
        }
        return response()->json('Product returned successfully.', 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Purchase $purchase)
    {

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Purchase $purchase)
    {
        $purchase->delete();
        return redirect()->route('purchase.index');
    }
}
