<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Storage;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();
        return view('inventory.app', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('inventory.new', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $path = '';
        if($request->hasFile('photo'))  {
            $path = $request->file('photo')->store('products', 'public');
        }
        Product::create(
            [
            'name' => $request->name,
            'price' => $request->price,
            'stock' => $request->stock,
            'categories_id' => $request->category_id,
            'photo' => $path,
            ]
        );

        return redirect()->route('inventory.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::find($id);
        return view('inventory.detail', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = Product::with('variants')->find($id);
        $categories = Category::all();
        return view('inventory.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::find($id);
        $product->name = $request->name;
        $product->price = $request->price;
        $product->stock = $request->stock;
        $product->categories_id = $request->category_id;

        if ($product->photo&& Storage::disk('public')->exists($product->photo)) {
            Storage::disk('public')->delete($product->photo);
        }

        $path = '';
        if($request->hasFile('photo'))  {
            $path = $request->file('photo')->store('products', 'public');
        }
        $product->photo = $path;
        $product->save();
        return redirect()->route('inventory.index')->with('success', 'Produk berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $p = Product::find($id);
        $p->delete();
        return redirect()->route('inventory.index')->with('success', 'Produk berhasil dihapus');
    }
}
