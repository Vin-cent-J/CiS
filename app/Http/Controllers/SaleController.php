<?php

namespace App\Http\Controllers;

use App\Models\Configuration;
use App\Models\DetailConfiguration;
use App\Models\Sale;
use App\Models\SalesDetail;
use App\Models\SubFeature;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sales = Sale::with(['salesDetails', 'customers']);
        return view('sales.app', compact( 'sales'));
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
        
        return view('sales.new', compact('features','activeConfigs', 'activeDetails'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $ins = Sale::create([
            'date' => now(),
            'total' => $request->total,
            'shipping_date' => $request->shipping_date,
            'payment_method' => $request->payment_method,
            'return_date' => $request->return_date,
            'return_type' => $request->return_type,
            'total_debt' => $request->total_debt,
            'customers_id' => $request->customers_id,
            'shipping_method' => $request->shipping_method
        ]);
        foreach ($request->products as $product) {
            SalesDetail::create([
                'sales_id' => $ins->id,
                'products_id' => $product['products_id'],
                'quantity' => $product['quantity'],
                'price' => $product['price'],
                'total' => $product['total']
            ]);
        }
        return redirect()->route('sales.app');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $sale = Sale::where('id', $id)->get();
        return view('pos.detail', compact('sale'));        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $sale = Sale::find($id);
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
