<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\SubFeature;
use App\Models\Configuration;
use App\Models\DetailConfiguration;
use Storage;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $features = SubFeature::where('features_id', 4)->where('is_active', 1)->get();
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

        $products = Product::with('variants')->get();
        return view('inventory.app', compact('products', 'activeDetails', 'features', 'activeConfigs'));
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
        return view('inventory.edit', compact('product'));
    }

    public function addVariant(Request $request)
    {
        $product = Product::find($request->id);
        $product->variants()->create([
            'name' => $request->name,
            'price' => $request->price,
            'stock' => $request->stock,
        ]);
        $product->update([
            'has_variant' => 1,
        ]);
        $product->save();
        return redirect()->back()->with('success', 'Varian produk berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $features = SubFeature::where('features_id', 4)->where('is_active', 1)->get();
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

        $product = Product::with('variants')->find($id);
        $categories = Category::all();
        return view('inventory.edit', compact('product', 'categories', 'activeDetails', 'features', 'activeConfigs'));
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
