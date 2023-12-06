<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Http\Resources\CustomerResource;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        return CustomerResource::collection(Customer::all());
    }

    public function show($id)
    {
        $customer = Customer::find($id);
        return new CustomerResource($customer);
    }

    public function store(Request $request)
    {
        $requestData = $request->only([
            'name',
            'email',
            'phone',
        ]);

        $customer = Customer::create($requestData);
        return new CustomerResource($customer);
    }

    public function update(Request $request, Customer $customer)
    {
        $requestData = $request->only([
            'name',
            'email',
            'phone',
        ]);

        $customer->update($requestData);
        return new CustomerResource($customer);
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return response()->json(null, 204);
    }
}
