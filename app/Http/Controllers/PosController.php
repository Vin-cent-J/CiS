<?php

namespace App\Http\Controllers;

use App\Models\Configuration;
use App\Models\DetailConfiguration;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SalesDetail;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\SubFeature;
use Session;
use Illuminate\Support\Facades\Log;
use function Symfony\Component\Translation\t;

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

        $customers = Customer::whereNot('id', 1)->get();

        $products = Product::all();
        echo "<script>console.log('Debug Objects: " . $features . "' );</script>";
        return view('pos.app', compact('features','activeConfigs', 'activeDetails', 'products', 'customers'));
    }

    public function setSession(Request $request)
    {
        $productFound = false;
        $products = Session::get('products', []);
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
        }
        Session::put('products', $products);
        return response()->json(['message' => 'Session set', 'products' => Session('products')]);
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
            return response()->json(['message' => 'Product quantity increased.', 'products' => $products]);
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
        $discountId = $request->discount_id ?? 1;
        $product = '';

        $cart = Session::get('products', []);

        foreach ($cart as $key => &$item) {
            if ((int)$item['id'] == (int)$productId) {
                $item['discount'] = $discount;
                $item['discount_id'] = $discountId;
                $product = $item;

                Session::put('products', $cart); 
                return response()->json([
                    'success' => true,
                    'message' => 'Discount updated successfully.',
                    'products' => $cart,
                    'product' => $product 
                ]);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Product not found in cart.'
        ], 404);
    }

    public function riwayat()
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

        echo "<script>console.log('Debug Objects: " . $features . "' );</script>";

        $sales = Sale::where('sales_type', 'pos')->orderBy('date')->get();
        return view('pos.history', compact('sales', 'activeConfigs', 'activeDetails', 'features'));
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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $total = 0;
        foreach(Session::get('products', []) as $product) {
            if($product['discount_id']== 2){
                $total += $product['price'] * $product['quantity'] * (1- ($product['discount'] / 100));
            } else{
                $total += $product['price'] * $product['quantity'] - $product['discount'];
            }
        }

        $s = Sale::create([
            'customers_id' => $request->customer_id == "" ? 1: $request->customer_id,
            'sales_type' => 'pos',
            'date' => now(),
            'shipping_date' => now(),
            'total' => $total,
            'discount' => $request->discount ?? 0,
            'payment_methods' => $request->payment_method,
            'total_debt' => $request->debt ?? 0,
        ]);
        foreach(Session::get('products', []) as $product) {
            SalesDetail::create([
                'sales_id' => $s->id,
                'products_id' => $product['id'],
                'amount' => $product['quantity'],
                'price' => $product['price'],
                'discount' => $product['discount'] ?? 0,
                'discounts_id' => $product['discount_id'] ?? null,
            ]
            );
        }

        Session::forget('products');
        redirect()->route('pos.index')->with('success', 'Sale created successfully.');
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
        $sale = Sale::with(['customer', 'salesDetails.product'])
            ->where('id', $id)
            ->first();
        return view('pos.detail', compact('sale', 'activeConfigs', 'activeDetails', 'features'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        
    }
}
