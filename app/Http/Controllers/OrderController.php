<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Http\Resources\OrderResource;
use Illuminate\Http\Request;
use Carbon\Carbon;

class OrderController extends Controller
{
    public function index()
    {
        // Eager load order details
        return OrderResource::collection(Order::with('orderDetails', 'customer')->get());
    }

    public function show($id)
    {
        $order = Order::with('orderDetails')->find($id);
        return new OrderResource($order);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|integer',
            'status' => 'required|boolean',
            'order_details' => 'sometimes|array'
        ]);

        $validated['order_date'] = Carbon::now();

        $order = Order::create($validated);

        if (!empty($validated['order_details'])) {
            foreach ($validated['order_details'] as $detail) {
                $product = Product::find($detail['product_id']);
                $product->quantity -= $detail['quantity'];
                $product->save();
            }
        }

        if (!empty($validated['order_details'])) {
            foreach ($validated['order_details'] as $detail) {
                $order->orderDetails()->create($detail);
            }
        }

        return new OrderResource($order->load('orderDetails'));
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'customer_id' => 'required|integer',
            'status' => 'required|boolean',
            'order_details' => 'sometimes|array'
        ]);

        $order->update($validated);

        $oldOrderDetails = $order->orderDetails()->get();
        foreach ($oldOrderDetails as $detail) {
            $product = Product::find($detail['product_id']);
            $product->quantity += $detail['quantity'];
            $product->save();
        }

        $order->orderDetails()->delete();

        if (!empty($validated['order_details'])) {
            foreach ($validated['order_details'] as $detail) {
                $product = Product::find($detail['product_id']);
                $product->quantity -= $detail['quantity'];
                $product->save();
            }
        }

        if (!empty($validated['order_details'])) {
            foreach ($validated['order_details'] as $detail) {
                $order->orderDetails()->create($detail);
            }
        }

        return new OrderResource($order->load('orderDetails'));
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return response()->json(null, 204);
    }
}
