<?php 
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        //return response()->json(Category::all());
        
            $categories = Category::with('child')
                ->whereNull('parent_id')
                ->get();
    
            return response()->json($categories);
        
    }
}



?>