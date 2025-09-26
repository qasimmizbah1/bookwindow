<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {
        return response()->json(Product::with('category')->get());
    }

    public function show($slug)
    {
        //$product = Product::with(['category','production'])->where('slug', $slug)->firstOrFail();
        $product = Product::with(['category', 'production'])
        ->where('slug', $slug)
        ->firstOrFail();
         $relatedProducts = Product::where('category_id', $product->category_id)
        ->where('id', '!=', $product->id) // Exclude the current product
        ->inRandomOrder() // Optional: randomize the results
        ->limit(4) // Optional: limit the number of related products
        ->get();
        
        return response()->json([
        'product' => $product,
        'related_products' => $relatedProducts
    ]);

        
    }

    public function productsByCategorySlug($slug)
    {
        try{

            $category = Category::where('slug', $slug)->first();

            // $subcategories = Category::with('child')
            // ->where('parent_id' , $category->id)
            // ->get();

            //$subcategories = Category::where('parent_id', $category->id)->get();
                $subcategories = DB::table('categories')
                ->where('parent_id', $category->id)
                ->get();

            if (!$category) {
            return response()->json(['error' => 'Category not found'], 404);
            }

            $products = \App\Models\Product::where('category_id', $category->id)
            ->orWhere('sub_category_id', $category->id)
            ->orWhere('child_category_id', $category->id)
            ->get();

            foreach ($subcategories as $subcategory) {
            $subproducts = \App\Models\Product::where('category_id', $subcategory->id)
            ->orWhere('sub_category_id', $subcategory->id)
            ->orWhere('child_category_id', $subcategory->id)
            ->get();

            // Add products to each subcategory
            $subcategory->subproducts = $subproducts;
            }


            $data = [
            "category" => $subcategories,
            "products" => $products,
            ];

return response()->json($data);

        //return response()->json($products);
    }
        catch (\Exception $e) {
            logger()->error('Registration error:', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
