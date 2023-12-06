<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Http\Resources\SupplierResource;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        return SupplierResource::collection(Supplier::orderBy('id', 'desc')->get());
    }

    public function show($id)
    {
        $supplier = Supplier::find($id);
        return new SupplierResource($supplier);
    }

    public function store(Request $request)
    {
        $requestData = $request->only([
            'name',
            'fantasy_name',
            'phone',
            'email',
        ]);

        $supplier = Supplier::create($requestData);
        return new SupplierResource($supplier);
    }

    public function update(Request $request, Supplier $supplier)
    {
        $requestData = $request->only([
            'name',
            'fantasy_name',
            'phone',
            'email',
        ]);

        $supplier->update($requestData);
        return new SupplierResource($supplier);
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return response()->json(null, 204);
    }
}
