<?php

namespace App\Http\Controllers;

use App\Models\Configuration;
use App\Models\Customer;
use App\Models\DetailConfiguration;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SalesDetail;
use App\Models\SubFeature;
use Illuminate\Http\Request;
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
        $sales = $sales->orderBy('date', 'desc')->get();
        
        return view('sales.app', compact( 'sales', 'activeConfigs', 'activeDetails', 'features', 'status', 'startDate', 'endDate'));
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
        $products = Product::all();
        $customers = Customer::whereNot('id', 1)->get();
        
        return view('sales.new', compact('features','activeConfigs', 'activeDetails', 'customers', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $total = 0;
        foreach(Session::get('sale-products', []) as $product) {
            if($product['discount_id']== 2){
                $total += $product['price'] * $product['quantity'] * (1- ($product['discount'] / 100));
            } else{
                $total += $product['price'] * $product['quantity'] - $product['discount'];
            }
        }

        $sale = Sale::create([
            'customers_id' => $request->customer_id == "" ? 1: $request->customer_id,
            'sales_type' => 'sales',
            'date' => now(),
            'shipping_date' => now(),
            'total' => $total,
            'discount' => $request->discount ?? 0,
            'payment_methods' => $request->payment_method,
            'total_debt' => isset($request->paid) ? $total - $request->paid : 0,
        ]);
        foreach (Session::get('sale-products', []) as $product) {
            SalesDetail::create([
                'sales_id' => $sale->id,
                'products_id' => $product['id'],
                'amount' => $product['quantity'],
                'price' => $product['price'],
                'discount' => $product['discount'] ?? 0,
                'discounts_id' => $product['discount_id'] ?? null,
            ]);
        }
        return redirect()->route('sales.index');
    }

    public function setSession(Request $request)
    {
        $productFound = false;
        $products = Session::get('sale-products', []);
        $added = Session::get('added', []);
        foreach ($products as &$product) {
            if (isset($product['id']) && $product['id'] === $request->id) {
                $product['quantity'] += $request->quantity;
                $productFound = true;
                break;
            }
        }

        if (!$productFound) {
            $products[] = [
                'id' => $request->id,
                'name' => $request->name,
                'price' => $request->price,
                'quantity' => $request->quantity,
                'discount' => $request->discount ?? 0,
                'discount_id' => $request->discount_id ?? 1,
            ];
            $added[] = $request->id;
        }
        Session::put('added', $added);
        Session::put('sale-products', $products);
        return response()->json(['message' => 'Session set', 'products' => Session('sale-products')]);
    }

    public function updateDiscount(Request $request)
    {
        $productId = $request->productId;
        $discount = $request->discount;
        $discountId = $request->discount_id ?? 1;
        $product = '';

        $cart = Session::get('sale-products', []);

        foreach ($cart as $key => &$item) {
            if ((int)$item['id'] == (int)$productId) {
                $item['discount'] = $discount;
                $item['discount_id'] = $discountId;

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

    public function changeProduct(Request $request){
        $cart = Session::get('sale-products', []);
        foreach ($cart as $key => &$item) {
            if ((int)$item['id'] == (int)$request->productId) {
                $product = Product::find($request->newId);
                $item['id'] = $product->id;
                $item['name'] = $product->name;
                $item['price'] = $product->price;
                $item['quantity'] = 1; 
                $item['discount'] = 0; 

                Session::put('sale-products', $cart); 
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
        $sale = Sale::with(['salesDetails.product', 'customer'])->find($id);
        return view('sales.detail', compact('sale', 'features', 'activeConfigs', 'activeDetails'));        
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
