<?php
namespace App\Http\Controllers\Api;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function apiProducts(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'category' => 'nullable|integer|exists:categories,id',
                'sort_by' => 'nullable|in:asc,desc'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }

            $query = Product::query();

            if ($request->has('category')) {
                $query->where('category_id', $request->category);
            }

            if ($request->sort_by === 'asc') {
                $query->orderBy('price', 'asc');
            } elseif ($request->sort_by === 'desc') {
                $query->orderBy('price', 'desc');
            }

            $products = $query->get();

            return response()->json(["message" => __("messageApi.Product list successfully retrieved"), "products" => $products], 200);

        } catch (QueryException $e) {
            return response()->json(["error" => __("messageApi.Database error") . ": " . $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(["error" => __("messageApi.Unexpected error") . ": " . $e->getMessage()], 500);
        }
    }
}
