<?php 
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Production;
use Illuminate\Support\Facades\DB;

class PublicationController extends Controller
{
    public function index()
    {

        //return response()->json(Production::all());
         // $productions = DB::table('productions')
         //           ->join('products', 'productions.id', '=', 'production_id')
         //           ->select('productions.*', 'products.name as product_name', 'products.description as product_description')
         //           ->get();

         $production = Production::with('products')->get();

         if (!$production) {
        return response()->json([
            'status' => false,
            'message' => 'Production not found'
        ], 404);
    }

    return response()->json([
        'data' => [
            'production' => $production,
           
        ]
    ]);


        return response()->json($productions);
    }
}



?>