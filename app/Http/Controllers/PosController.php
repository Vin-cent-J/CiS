<?php

namespace App\Http\Controllers;

use App\Models\Configuration;
use App\Models\DetailConfiguration;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Request;
use App\Models\SubFeature;
use Session;

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

        $products = Product::all();
        echo "<script>console.log('Debug Objects: " . $features . "' );</script>";
        return view('pos.app', compact('features','activeConfigs', 'activeDetails', 'products'));
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

    public function updateItemDiscount(Request $request)
    {
        $productId = $request->input('productId');
        $discount = $request->input('discount');

        $cart = Session::get('cart', []);
        $itemFound = false;

        foreach ($cart as $key => &$item) {
            if ($item['id'] == $productId) {
                $item['discount'] = $discount;
                $itemFound = true;
                break;
            }
        }

        if ($itemFound) {
            Session::put('cart', $cart); 
            return response()->json([
                'success' => true,
                'message' => 'Discount updated successfully.',
                'cart' => $cart 
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Product not found in cart.'
            ], 404);
        }
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
        session('products', []);
        return $s = Sale::create([
            'customer_id' => $request->customer_id,
            'sales_type' => 'pos',
            'date' => now(),
            'total' => $request->total,
            'discount' => $request->discount,
            'payment_method' => $request->payment_method,
            'status' => 'completed',
        ]);
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
        $sale = Sale::findOrFail($id);
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
