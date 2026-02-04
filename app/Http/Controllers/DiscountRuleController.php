<?php

namespace App\Http\Controllers;

use App\Models\DiscountRule;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use PDOException;
use PhpParser\Node\Stmt\TryCatch;

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
        try {
            $minimum = $request->minimum;
            $bonusQuantity = $request->bonus_quantity;
            $saleType = $request->sale_type;
            $lineDiscount = $request->line_discount;
            $categoryIds = $request->category_ids;
            $productIds = $request->product_ids;
            $bonusId = $request->bonus_product_id;


            if($categoryIds == null and $productIds == null){
                $rule = DiscountRule::where('minimum', $minimum)
                    ->whereNull('products_id')
                    ->whereNull('categories_id')
                    ->first();
                if ($rule) {
                    return response()->json(['errCode'=>1062, 'message' => 'Duplicate Entry'], 409);
                }
            }
    
            $rule = DiscountRule::create([
                'minimum' => $minimum,
                'line_discount' => $lineDiscount,
                'sales_type' => $saleType,
                'products_id' => $productIds,
                'categories_id' => $categoryIds
            ]);
            
    
            if($rule){
                return response()->json(['message' => 'Discount rule created successfully!', 'data'=>[$categoryIds, $productIds]]);
            } else {
                return response()->json(['message' => 'Cant create discount rule']);
            }   
        }
        catch (PDOException $e){
            if ($e->errorInfo[1] === 1062) {
                return response()->json(['errCode'=>$e->errorInfo[1], 'message' => 'Error: ' . $e->getMessage()], 409);
            }
        }
    }

    public function insertRule(Request $request)
    {
        $bonusQuantity = $request->bonus_quantity;
        $bonusId = $request->bonus_product_id;
        $min = $request->minimal;
        if ($request->has('categories')) {
            foreach ($request->categories as $categoryId) {
                DiscountRule::updateOrCreate(
                    ['categories_id' => $categoryId],
                    [
                        'minimum' => $min,
                        'products_id' => null,
                        'bonus_quantity'=> 1,
                        'bonus_product_id'=> $bonusId ? $bonusId : null
                    ]
                );
            }
        }

        if ($request->has('products')) {
            foreach ($request->products as $prodId) {
                DiscountRule::updateOrCreate(
                    ['products_id' => $prodId],
                    [
                        'minimum' => $min,
                        'categories_id' => null,
                        'bonus_quantity'=> 1,
                        'bonus_product_id'=> $bonusId ? $bonusId : null
                    ]
                );
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Riwayat telah tersimpan.'
        ], 200);
    }

    public function updateRule(Request $request)
    {
        $id = $request->id;
        $minimum = $request->minimum;
        $saleType = $request->sale_type;
        $lineDiscount = $request->line_discount;
        $categoryId = $request->category_ids;
        $productId = $request->product_ids;

        $rule = DiscountRule::find($id);

        $rule->update([
            'minimum' => $minimum,
            'line_discount' => $lineDiscount,
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
    public function destroy(string $id)
    {
        $discountRule = DiscountRule::findOrFail($id);
        $discountRule->delete();
        return redirect()->route('discounts.index')->with('success', 'Discount rule deleted successfully!');
    }
}
