<?php

namespace App\Http\Controllers;

use App\Models\DiscountRule;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class DiscountRuleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $discountRules = DiscountRule::with('category', 'product')->orderBy('id', 'desc')->get();

        $products = Product::all();
        $categories = Category::all();
        return view('discounts.app', compact('discountRules', 'products', 'categories'));
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
        $minimum = $request->minimum;
        $saleType = $request->sale_type;
        $categoryIds = $request->category_ids;
        $productIds = $request->product_ids;

        $rule = DiscountRule::create([
            'minimum' => $minimum,
            'sales_type' => $saleType,
            'products_id' => $productIds,
            'categories_id' => $categoryIds
        ]);

        if($rule){
            return response()->json(['success' => true, 'message' => 'Discount rule created successfully!', 'data'=>[$categoryIds, $productIds]]);
        } else {
            return response()->json(['success' => false, 'message' => 'Cant create discount rule']);
        }
        
    }

    public function updateRule(Request $request)
    {
        $id = $request->id;
        $minimum = $request->minimum;
        $saleType = $request->sale_type;
        $categoryId = $request->category_ids;
        $productId = $request->product_ids;

        $rule = DiscountRule::find($id);

        $rule->update([
            'minimum' => $minimum,
            'sales_type' => $saleType,
            'products_id' => $productId,
            'categories_id' => $categoryId
        ]);

        if($rule){
            return response()->json(['success' => true, 'message' => 'Discount rule updated successfully!']);
        } else {
            return response()->json(['success' => false, 'message' => 'Cant update discount rule']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $id = $request->id;
        $discountRule = DiscountRule::findOrFail($id);
        $discountRule->delete();
        return redirect()->route('discounts.index')->with('success', 'Discount rule deleted successfully!');
    }
}
