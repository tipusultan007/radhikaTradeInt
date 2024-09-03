<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductStock;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Warehouse;
use App\Models\PackagingType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WarehouseController extends Controller
{
    // Display a list of warehouse products
    public function index()
    {
        $warehouses = Warehouse::with('product', 'packagingType')->get();
        return view('warehouses.index', compact('warehouses'));
    }

    public function show($id)
    {
        $warehouseProduct = Warehouse::with('product', 'packagingType')->find($id);
        $soldItems = SaleDetail::where('product_id', $warehouseProduct->product_id)
            ->where('packaging_type_id', $warehouseProduct->packaging_type_id)
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('warehouses.show',compact('warehouseProduct','soldItems'));
    }
    // Show the form for adding a product to the warehouse
    public function create()
    {
        $products = Product::all();
        $packagingTypes = PackagingType::all();
        return view('warehouses.create', compact('products', 'packagingTypes'));
    }

    // Store a new product in the warehouse
    public function store(Request $request)
    {

        //dd(request()->all());
        // Validate the request data
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'packaging_type_id' => 'required|exists:packaging_types,id',
            'stock' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0',
            'dealer_price' => 'required|numeric|min:0',
            'commission_agent_price' => 'required|numeric|min:0',
            'retailer_price' => 'required|numeric|min:0',
            'retail_price' => 'required|numeric|min:0',
            'wholesale_price' => 'required|numeric|min:0',
        ]);

        // Retrieve the product
        $product = Product::findOrFail($validated['product_id']);

        // Check if the stock to be added exceeds the available product stock
        if ($validated['stock'] > $product->initial_stock_kg) {
            return redirect()->back()->withErrors(['stock' => 'The stock exceeds the available product stock.'])->withInput();
        }

        // Create a new warehouse record
        $warehouse = Warehouse::create($validated);

        // Update the product stock
        $product->initial_stock_kg -= $warehouse->packagingType->weight_kg * $validated['stock'];
        $product->save();

        return redirect()->route('warehouses.index')->with('success', 'Product added to warehouse successfully.');
    }

    // Show the form for editing a warehouse product
    public function edit(Warehouse $warehouse)
    {
        $products = Product::all();
        $packagingTypes = PackagingType::all();
        return view('warehouses.edit', compact('warehouse', 'products', 'packagingTypes'));
    }

    // Update an existing warehouse product
    public function update(Request $request, Warehouse $warehouse)
    {
        // Validate the request data
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'packaging_type_id' => 'required|exists:packaging_types,id',
            'stock' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0',
            'dealer_price' => 'required|numeric|min:0',
            'commission_agent_price' => 'required|numeric|min:0',
            'retailer_price' => 'required|numeric|min:0',
            'retail_price' => 'required|numeric|min:0',
            'wholesale_price' => 'required|numeric|min:0',
        ]);

        // Retrieve the product
        $product = Product::findOrFail($validated['product_id']);

        // Check if the stock to be added exceeds the available product stock
        if ($validated['stock'] > $product->initial_stock_kg + $warehouse->stock) {
            return redirect()->back()->withErrors(['stock' => 'The stock exceeds the available product stock.'])->withInput();
        }

        // Update the warehouse record
        $warehouse->update($validated);

        // Update the product stock
        $product->initial_stock_kg = $product->warehouses()->sum('stock');
        $product->save();

        return redirect()->route('warehouses.index')->with('success', 'Warehouse product updated successfully.');
    }

    // Delete a warehouse product
    public function destroy(Warehouse $warehouse)
    {
        // Restore product stock
        $product = $warehouse->product;
        $product->initial_stock_kg += $warehouse->stock;
        $product->save();

        $warehouse->delete();
        return redirect()->route('warehouses.index')->with('success', 'Warehouse product deleted successfully.');
    }

    public function getWarehouseInfo(Request $request)
    {
        $warehouse = Warehouse::where('product_id', $request->product_id)
            ->where('packaging_type_id', $request->packaging_type_id)
            ->first();

        if ($warehouse) {
            return response()->json([
                'stock' => $warehouse->stock,
                'sale_price' => $warehouse->sale_price,
                'dealer_price' => $warehouse->dealer_price,
                'commission_agent_price' => $warehouse->commission_agent_price,
                'retailer_price' => $warehouse->retailer_price,
                'retail_price' => $warehouse->retail_price,
                'wholesale_price' => $warehouse->wholesale_price,
            ]);
        } else {
            return response()->json([
                'stock' => 0,
                'sale_price' => 0,
                'dealer_price' => 0,
                'commission_agent_price' => 0,
                'retailer_price' => 0,
                'retail_price' => 0,
                'wholesale_price' => 0,
            ], 404);
        }
    }

    public function productStock(Request $request)
    {
        DB::beginTransaction();

        try {
            $warehouse = Warehouse::find(request('warehouse_id'));
            $productStock = ProductStock::create(request()->all());

            $warehouse->stock += $productStock->quantity;
            $warehouse->save();

            DB::commit();
            return redirect()->route('warehouses.index')->with('success', 'Product stock added successfully.');
        }catch (\Exception $exception){
            DB::rollBack();
            return redirect()->route('warehouses.index')->with('error', $exception->getMessage());
        }

       // return redirect()->route('warehouses.index')->with('success', 'Product stock updated successfully.');
    }
}
