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
        } catch (\Exception $exception) {
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
    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            // Find the existing product stock record
            $productStock = ProductStock::findOrFail($id);

            // Get the warehouse associated with the product stock
            $warehouse = Warehouse::find($request->input('warehouse_id'));

            // Calculate the difference in stock quantity
            $quantityDifference = $request->input('quantity') - $productStock->quantity;

            // Update the product stock record with new values
            $productStock->update($request->all());

            // Adjust the warehouse stock based on the difference
            $warehouse->stock += $quantityDifference;
            $warehouse->save();

            DB::commit();
            return redirect()->route('warehouses.index')->with('success', 'Product stock updated successfully.');
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->route('warehouses.index')->with('error', $exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            // Find the existing product stock record
            $productStock = ProductStock::findOrFail($id);

            // Get the warehouse associated with the product stock
            $warehouse = Warehouse::find($productStock->warehouse_id);

            // Adjust the warehouse stock by subtracting the quantity being deleted
            $warehouse->stock -= $productStock->quantity;
            $warehouse->save();

            // Delete the product stock record
            $productStock->delete();

            DB::commit();
            return redirect()->route('warehouses.index')->with('success', 'Product stock deleted successfully.');
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->route('warehouses.index')->with('error', $exception->getMessage());
        }

    }
}
