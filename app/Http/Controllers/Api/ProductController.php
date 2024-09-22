<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        $prodcuts = Product::get();

        if ($prodcuts->count() > 0) {
            return ProductResource::collection($prodcuts);
        }
        else{
            return response()->json(['message' => 'No products found'], 200);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required',
            'price' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'All fields are mandatory',
                'error' => $validator->messages()
            ], 422);
        }

        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price
        ]);

        return response()->json([
            'message' => 'Product created successfully',
            'data' => new ProductResource($product)
        ], 200);
    }

    public function show(Product $product)
    {
        return new ProductResource($product);
    }

    public function update(Product $product, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required',
            'price' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'All fields are mandatory',
                'error' => $validator->messages()
            ], 422);
        }

        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price
        ]);

        return response()->json([
            'message' => 'Product updated successfully',
            'data' => new ProductResource($product)
        ], 200);
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json([
            'message' => 'Product Deleted Successfully',
            'data' => new ProductResource($product)
        ], 200);
    }


}
