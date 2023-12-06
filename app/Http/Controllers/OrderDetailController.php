<?php

namespace App\Http\Controllers;

use App\Models\OrderDetail;
use Illuminate\Http\Request;

<?php

namespace App\Http\Controllers;

use App\Models\OrderDetail;
use App\Http\Resources\OrderDetailResource;
use Illuminate\Http\Request;

class OrderDetailController extends Controller
{
    // GET all order details
    public function index()
    {
        return OrderDetailResource::collection(OrderDetail::all());
    }

    // GET a single order detail
    public function show(OrderDetail $orderDetail)
    {
        return new OrderDetailResource($orderDetail);
    }

    // POST a new order detail
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer',
            'price' => 'required|numeric'
        ]);

        $orderDetail = OrderDetail::create($validatedData);
        return new OrderDetailResource($orderDetail);
    }

    // PUT/PATCH update an order detail
    public function update(Request $request, OrderDetail $orderDetail)
    {
        $validatedData = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer',
            'price' => 'required|numeric'
        ]);

        $orderDetail->update($validatedData);
        return new OrderDetailResource($orderDetail);
    }

    // DELETE an order detail
    public function destroy(OrderDetail $orderDetail)
    {
        $orderDetail->delete();
        return response()->json(null, 204);
    }
}

