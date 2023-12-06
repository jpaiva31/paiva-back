<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('supplier')->get();
        return ProductResource::collection($products);
    }

    public function show($id)
    {
        $product = Product::find($id)->with('supplier');
        return new ProductResource($product);
    }

    public function store(Request $request)
    {
        $requestData = $request->only([
            'name',
            'description',
            'price',
            'quantity',
            'supplier_id'
        ]);

        $product = Product::create($requestData);
        return new ProductResource($product);
    }

    public function update(Request $request, Product $product)
    {
        $requestData = $request->only([
            'name',
            'description',
            'price',
            'quantity',
            'supplier_id'
        ]);
        
        $product->update($requestData);
        return new ProductResource($product);
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(null, 204);
    }
}
