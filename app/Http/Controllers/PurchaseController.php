<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\Supplier;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $purchases = Purchase::with(['purchaseDetails', 'suppliers']);
        return view('purchases.app', compact('purchases'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $suppliers = Supplier::all();
        $products = Product::all();
        return view('purchases.new', compact('suppliers', 'products'));
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       $ins = Purchase::create([
            'date' => now(),
            'total' => $request->total,
            'shipping_date' => $request->shipping_date,
            'payment_method' => $request->payment_method,
            'return_date' => $request->return_date,
            'return_type' => $request->return_type,
            'total_debt' => $request->total_debt,
            'suppliers_id' => $request->suppliers_id,
            'shipping_method' => $request->shipping_method
        ]);
        foreach ($request->products as $product) {
            PurchaseDetail::create([
                'purchases_id' => $ins->id,
                'products_id' => $product['products_id'],
                'quantity' => $product['quantity'],
                'price' => $product['price'],
                'total' => $product['total']
            ]);
        }
        return redirect()->route('purchases.app');
    }

    /**
     * Display the specified resource.
     */
    public function show(Purchase $purchase)
    {
        $purchase = Purchase::with(['purchaseDetails.products', 'suppliers'])->find($purchase->id);
        return view('purchases.detail', compact('purchase'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Purchase $purchase)
    {
        $suppliers = Supplier::all();
        $products = Product::all();
        return view('purchases.edit', compact('purchase', 'suppliers', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Purchase $purchase)
    {
        $purchase->update([
            'date' => now(),
            'total' => $request->total,
            'shipping_date' => $request->shipping_date,
            'payment_method' => $request->payment_method,
            'return_date' => $request->return_date,
            'return_type' => $request->return_type,
            'total_debt' => $request->total_debt,
            'suppliers_id' => $request->suppliers_id,
            'shipping_method' => $request->shipping_method
        ]);
        return redirect()->route('purchases.app');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Purchase $purchase)
    {
        $purchase->delete();
        return redirect()->route('purchases.app');
    }
}
