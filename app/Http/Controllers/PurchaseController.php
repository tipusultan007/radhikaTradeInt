<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    // Display a list of purchases
    public function index()
    {
        $purchases = Purchase::with('product')->get();
        return view('purchases.index', compact('purchases'));
    }

    // Show the form for creating a new purchase
    public function create()
    {
        $products = Product::all();
        return view('purchases.create', compact('products'));
    }

    // Store a newly created purchase in storage
    public function store(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity_kg' => 'required|numeric|min:0',
            'purchase_price' => 'required|numeric|min:0',
            'date' => 'required|date',
        ]);

        // Use DB transaction
        DB::beginTransaction();

        try {
            // Create the purchase
            $purchase = Purchase::create($validated);

            // Update the product stock
            $product = Product::find($request->product_id);
            $product->initial_stock_kg += $request->quantity_kg;
            $product->save();

            DB::commit();

            return redirect()->route('purchases.index')->with('success', 'Purchase recorded successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to record purchase: ' . $e->getMessage());
        }
    }

    // Show the form for editing a purchase
    public function edit($id)
    {
        $purchase = Purchase::findOrFail($id);
        $products = Product::all();
        return view('purchases.edit', compact('purchase', 'products'));
    }

    // Update the specified purchase in storage
    public function update(Request $request, $id)
    {
        $purchase = Purchase::findOrFail($id);

        // Validate the request data
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity_kg' => 'required|numeric|min:0',
            'purchase_price' => 'required|numeric|min:0',
            'date' => 'required|date',
        ]);

        // Use DB transaction
        DB::beginTransaction();

        try {
            // Adjust the product stock (subtract old quantity and add new quantity)
            $product = $purchase->product;
            $product->initial_stock_kg -= $purchase->quantity_kg;
            $product->initial_stock_kg += $request->quantity_kg;
            $product->save();

            // Update the purchase
            $purchase->update($validated);

            DB::commit();

            return redirect()->route('purchases.index')->with('success', 'Purchase updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update purchase: ' . $e->getMessage());
        }
    }

    // Remove the specified purchase from storage
    public function destroy($id)
    {
        $purchase = Purchase::findOrFail($id);

        // Use DB transaction
        DB::beginTransaction();

        try {
            // Adjust the product stock
            $product = $purchase->product;
            $product->initial_stock_kg -= $purchase->quantity_kg;
            $product->save();

            // Delete the purchase
            $purchase->delete();

            DB::commit();

            return redirect()->route('purchases.index')->with('success', 'Purchase deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to delete purchase: ' . $e->getMessage());
        }
    }
}
