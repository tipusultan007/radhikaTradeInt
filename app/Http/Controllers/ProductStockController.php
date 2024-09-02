<?php

namespace App\Http\Controllers;

use App\Models\ProductStock;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductStockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $warehouse = Warehouse::find($request->input('warehouse_id'));
            $productStock = ProductStock::create($request->all());

            $warehouse->stock += $productStock->quantity;
            $warehouse->save();

            DB::commit();
            return redirect()->route('warehouses.index')->with('success', 'Product stock added successfully.');
        }catch (\Exception $exception){
            DB::rollBack();
            return redirect()->route('warehouses.index')->with('error', $exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductStock $productStock)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductStock $productStock)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductStock $productStock)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductStock $productStock)
    {
        //
    }
}
