<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Product;
use App\Http\Resources\PurchaseResource;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function index()
    {
        return PurchaseResource::collection(Purchase::with('product', 'supplier')->get());
    }

    public function show($id)
    {
        $purchase = Purchase::find($id)->with('product', 'supplier');
        return new PurchaseResource($purchase);
    }

    public function store(Request $request)
    {
        $requestData = $request->only([
            'product_id',
            'supplier_id',
            'quantity',
            'price',
        ]);

        $requestData['purchase_date'] = Carbon::now();

        $product = Product::find($requestData['product_id']);
        $product->quantity += $requestData['quantity'];
        $product->save();

        $purchase = Purchase::create($requestData);
        return new PurchaseResource($purchase);
    }

    public function update(Request $request)
    {
        $requestData = $request->only([
            'id',
            'product_id',
            'supplier_id',
            'quantity',
            'price',
        ]);

        $purchase = Purchase::find($requestData['id']);
        $product = Product::find($requestData['product_id']);

        $product->quantity -= $purchase->quantity;
        $product->quantity += $requestData['quantity'];
        $product->save();

        $purchase->update($requestData);
        return new PurchaseResource($purchase);
    }

    public function destroy(Purchase $purchase)
    {
        $product = Product::find($purchase->product_id);
        $product->quantity -= $purchase->quantity;
        $product->save();

        $purchase->delete();
        return response()->json(null, 204);
    }
}
