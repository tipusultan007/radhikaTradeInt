<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // List all products
    public function index()
    {
        $products = Product::all();
        return view('products.index', compact('products'));
    }

    // Show a single product
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('products.show', compact('product'));
    }

    // Show the form for creating a new product
    public function create()
    {
        return view('products.create');
    }

    // Store a newly created product
    public function store(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'initial_stock_kg' => 'required|numeric|min:0',
        ]);

        // Create the product
        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    // Show the form for editing the specified product
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('products.edit', compact('product'));
    }

    // Update an existing product
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        // Validate the request data
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'initial_stock_kg' => 'sometimes|numeric|min:0',
        ]);

        // Update the product
        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    // Delete a product
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // Ensure that there are no dependent records before deletion
        if ($product->warehouses()->count() > 0 || $product->salesDetails()->count() > 0) {
            return redirect()->route('products.index')->with('error', 'Cannot delete product with existing warehouse or sale records.');
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
}
