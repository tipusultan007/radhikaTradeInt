<?php
namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Customer;
use App\Models\PackagingType;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    // List all sales
    public function index()
    {
        $sales = Sale::with('details.product', 'details.packagingType', 'customer')->get();
        return view('sales.index', compact('sales'));
    }

    // Show a single sale
    public function show($id)
    {
        $sale = Sale::with('details.product', 'details.packagingType', 'customer')->findOrFail($id);
        return view('sales.show', compact('sale'));
    }
    public function create()
    {
        // Fetch customers, products, and packaging types to populate the form
        $customers = Customer::all();
        $products = Product::all();
        $packagingTypes = PackagingType::all();
        $accounts = Account::where('type','asset')->get();

        return view('sales.create', compact('customers', 'products', 'packagingTypes','accounts'));
    }

    public function edit($id)
    {
        // Retrieve the sale by its ID
        $sale = Sale::with('details')->findOrFail($id);

        // Retrieve necessary data for the form
        $customers = Customer::all();
        $products = Product::all();
        $packagingTypes = PackagingType::all();

        // Return the view with the sale data
        return view('sales.edit', compact('sale', 'customers', 'products', 'packagingTypes'));
    }
    // Create a new sale
    /*public function store(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'total' => 'required|numeric',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.packaging_type_id' => 'required|exists:packaging_types,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        $data = $request->all();
        // Create the Sale entry
        $sale = Sale::create($data);

        // Loop through the sale items and add them to the SaleDetail table
        foreach ($validated['items'] as $item) {
            SaleDetail::create([
                'sale_id' => $sale->id,
                'product_id' => $item['product_id'],
                'packaging_type_id' => $item['packaging_type_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);

            // Update the warehouse stock
            $warehouse = Warehouse::where('product_id', $item['product_id'])
                ->where('packaging_type_id', $item['packaging_type_id'])
                ->first();
            if ($warehouse) {
                $warehouse->stock -= $item['quantity'];
                if ($warehouse->stock < 0) {
                    return redirect()->back()->withErrors(['message' => 'Insufficient stock for this sale']);
                }
                $warehouse->save();
            } else {
                return redirect()->back()->withErrors(['message' => 'No stock found for the specified product and packaging type']);
            }
        }

        return redirect()->route('sales.index')->with('success', 'Sale recorded successfully');
    }*/
    public function store(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'total' => 'required|numeric',
            'date' => 'required|date',
            'paid_amount' => 'required|numeric|min:0',
            'account_id' => 'required_if:paid_amount,>0',
            'subtotal' => 'required|numeric',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.packaging_type_id' => 'required|exists:packaging_types,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($validated, $request) {
            // Create the Sale entry
            $sale = Sale::create($request->except('items'));

            $this->createOrUpdateJournalEntryForSale($sale);

            // Loop through the sale items and add them to the SaleDetail table
            foreach ($validated['items'] as $item) {
                SaleDetail::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'packaging_type_id' => $item['packaging_type_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);

                // Update the warehouse stock
                $warehouse = Warehouse::where('product_id', $item['product_id'])
                    ->where('packaging_type_id', $item['packaging_type_id'])
                    ->first();
                if ($warehouse) {
                    $warehouse->stock -= $item['quantity'];
                    if ($warehouse->stock < 0) {
                        throw new \Exception('Insufficient stock for this sale');
                    }
                    $warehouse->save();
                } else {
                    throw new \Exception('No stock found for the specified product and packaging type');
                }
            }
        });

        return redirect()->route('sales.index')->with('success', 'Sale recorded successfully');
    }

    // Optionally, update a sale record (complex cases might need reverting stock and reapplying changes)
    public function update(Request $request, $id)
    {
        // Validate the request data
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'total' => 'required|numeric',
            'date' => 'required|date',
            'subtotal' => 'required|numeric',
            'paid_amount' => 'required|numeric|min:0',
            'account_id' => 'required_if:paid_amount,>0',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.packaging_type_id' => 'required|exists:packaging_types,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($validated, $id) {
            // Fetch the existing sale record
            $sale = Sale::findOrFail($id);

            // Revert the stock for each item in the original sale
            foreach ($sale->details as $detail) {
                $warehouse = Warehouse::where('product_id', $detail->product_id)
                    ->where('packaging_type_id', $detail->packaging_type_id)
                    ->first();
                if ($warehouse) {
                    $warehouse->stock += $detail->quantity;
                    $warehouse->save();
                }
            }

            // Update the Sale entry
            $sale->update($validated);

            $this->createOrUpdateJournalEntryForSale($sale);

            // Remove all previous sale details
            $sale->details()->delete();

            // Add the updated sale items
            foreach ($validated['items'] as $item) {
                SaleDetail::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'packaging_type_id' => $item['packaging_type_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);

                // Update the warehouse stock
                $warehouse = Warehouse::where('product_id', $item['product_id'])
                    ->where('packaging_type_id', $item['packaging_type_id'])
                    ->first();
                if ($warehouse) {
                    $warehouse->stock -= $item['quantity'];
                    if ($warehouse->stock < 0) {
                        throw new \Exception('Insufficient stock for this sale');
                    }
                    $warehouse->save();
                } else {
                    throw new \Exception('No stock found for the specified product and packaging type');
                }
            }
        });

        return redirect()->route('sales.index')->with('success', 'Sale updated successfully');
    }

    // Optionally, delete a sale record and revert stock
    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $sale = Sale::findOrFail($id);

            // Revert stock before deleting
            foreach ($sale->details as $detail) {
                $warehouse = Warehouse::where('product_id', $detail->product_id)
                    ->where('packaging_type_id', $detail->packaging_type_id)
                    ->first();
                if ($warehouse) {
                    $warehouse->stock += $detail->quantity;
                    $warehouse->save();
                }
            }

            $sale->delete();
        });

        return redirect()->route('sales.index')->with('success', 'Sale deleted successfully');
    }

    private function createJournalEntryForSale(Sale $sale)
    {
        // Adjust accounts for sales, receivables, and cash/bank
        $salesAccount = Account::where('code', 'sales')->first();
        $receivableAccount = Account::where('code', 'receivables')->first();
        $cashAccount = Account::where('code', 'cash')->first();  // or 'bank' for bank transactions

        $paidAmount = $sale->paid_amount;
        $totalAmount = $sale->total;
        $dueAmount = $totalAmount - $paidAmount;

        // Create a new Journal Entry for the sale
        $journalEntry = $sale->journalEntries()->create([
            'customer_id' => $sale->customer_id,
            'type' => 'sale',
            'date' => $sale->date,
            'description' => 'Sale entry for sale ID: ' . $sale->id,
        ]);

        // Scenario 1: Full Payment
        if ($paidAmount == $totalAmount) {
            // Debit the Cash/Bank account
            $journalEntry->lineItems()->create([
                'account_id' => $cashAccount->id,
                'debit' => $paidAmount,
                'credit' => 0,
            ]);

            // Credit the Sales account
            $journalEntry->lineItems()->create([
                'account_id' => $salesAccount->id,
                'debit' => 0,
                'credit' => $totalAmount,
            ]);

            // Scenario 2: Partial Payment
        } elseif ($paidAmount > 0 && $paidAmount < $totalAmount) {
            // Debit the Cash/Bank account for the paid amount
            $journalEntry->lineItems()->create([
                'account_id' => $cashAccount->id,
                'debit' => $paidAmount,
                'credit' => 0,
            ]);

            // Debit the Receivables account for the remaining due amount
            $journalEntry->lineItems()->create([
                'account_id' => $receivableAccount->id,
                'debit' => $dueAmount,
                'credit' => 0,
            ]);

            // Credit the Sales account for the total amount
            $journalEntry->lineItems()->create([
                'account_id' => $salesAccount->id,
                'debit' => 0,
                'credit' => $totalAmount,
            ]);

            // Scenario 3: Full Due
        } else {
            // Debit the Receivables account for the full amount
            $journalEntry->lineItems()->create([
                'account_id' => $receivableAccount->id,
                'debit' => $totalAmount,
                'credit' => 0,
            ]);

            // Credit the Sales account for the total amount
            $journalEntry->lineItems()->create([
                'account_id' => $salesAccount->id,
                'debit' => 0,
                'credit' => $totalAmount,
            ]);
        }
    }

    private function updateJournalEntryForSale(Sale $sale)
    {
        // Adjust accounts for sales, receivables, and cash/bank
        $salesAccount = Account::where('code', 'sales')->first();
        $receivableAccount = Account::where('code', 'receivables')->first();
        $cashAccount = Account::where('code', 'cash')->first();  // or 'bank' for bank transactions

        $paidAmount = $sale->paid_amount;
        $totalAmount = $sale->total;
        $dueAmount = $totalAmount - $paidAmount;

        // Retrieve the existing journal entry for the sale
        $journalEntry = $sale->journalEntries()->first();

        if ($journalEntry) {
            // Delete existing journal entry line items
            $journalEntry->lineItems()->delete();
        } else {
            // If no journal entry exists, create a new one
            $journalEntry = $sale->journalEntries()->create([
                'customer_id' => $sale->customer_id,
                'type' => 'sale',
                'date' => $sale->date,
                'description' => 'Sale entry for sale ID: ' . $sale->id,
            ]);
        }

        // Scenario 1: Full Payment
        if ($paidAmount == $totalAmount) {
            // Debit the Cash/Bank account
            $journalEntry->lineItems()->create([
                'account_id' => $cashAccount->id,
                'debit' => $paidAmount,
                'credit' => 0,
            ]);

            // Credit the Sales account
            $journalEntry->lineItems()->create([
                'account_id' => $salesAccount->id,
                'debit' => 0,
                'credit' => $totalAmount,
            ]);

            // Scenario 2: Partial Payment
        } elseif ($paidAmount > 0 && $paidAmount < $totalAmount) {
            // Debit the Cash/Bank account for the paid amount
            $journalEntry->lineItems()->create([
                'account_id' => $cashAccount->id,
                'debit' => $paidAmount,
                'credit' => 0,
            ]);

            // Debit the Receivables account for the remaining due amount
            $journalEntry->lineItems()->create([
                'account_id' => $receivableAccount->id,
                'debit' => $dueAmount,
                'credit' => 0,
            ]);

            // Credit the Sales account for the total amount
            $journalEntry->lineItems()->create([
                'account_id' => $salesAccount->id,
                'debit' => 0,
                'credit' => $totalAmount,
            ]);

            // Scenario 3: Full Due
        } else {
            // Debit the Receivables account for the full amount
            $journalEntry->lineItems()->create([
                'account_id' => $receivableAccount->id,
                'debit' => $totalAmount,
                'credit' => 0,
            ]);

            // Credit the Sales account for the total amount
            $journalEntry->lineItems()->create([
                'account_id' => $salesAccount->id,
                'debit' => 0,
                'credit' => $totalAmount,
            ]);
        }
    }

    private function createOrUpdateJournalEntryForSale(Sale $sale)
    {
        // Retrieve accounts
        $salesAccount = 6;
        $receivableAccount = 3;
        //$cashAccount = Account::where('code', 'cash')->first();
        //$discountAccount = Account::where('code', 'discounts_given')->first();

        $paidAmount = $sale->paid_amount;
        $totalAmount = $sale->total;
        $discountAmount = $sale->discount ?? 0; // If no discount, assume 0
        $dueAmount = $totalAmount - $paidAmount;

        // Create or update the Journal Entry for the sale
        $journalEntry = $sale->journalEntries()->firstOrCreate([
            'customer_id' => $sale->customer_id,
            'type' => 'sale',
            'date' => $sale->date,
            'description' => 'Sale entry for sale ID: ' . $sale->id,
        ]);

        // Clear previous line items
        $journalEntry->lineItems()->delete();

        // Scenario 1: Full Payment
        if ($paidAmount == $totalAmount) {
            // Debit the Cash/Bank account
            $journalEntry->lineItems()->create([
                'account_id' => $sale->account_id,
                'debit' => $paidAmount,
                'credit' => 0,
            ]);

            // Credit the Sales account (minus discount)
            $journalEntry->lineItems()->create([
                'account_id' => 6,
                'debit' => 0,
               /* 'credit' => $totalAmount + $discountAmount,*/
                'credit' => $totalAmount,
            ]);

            // Debit the Discounts Given account (if discount exists)
           /* if ($discountAmount > 0) {
                $journalEntry->lineItems()->create([
                    'account_id' => $discountAccount->id,
                    'debit' => $discountAmount,
                    'credit' => 0,
                ]);
            }*/

            // Scenario 2: Partial Payment
        } elseif ($paidAmount > 0 && $paidAmount < $totalAmount) {
            // Debit the Cash/Bank account for the paid amount
            $journalEntry->lineItems()->create([
                'account_id' => $sale->account_id,
                'debit' => $paidAmount,
                'credit' => 0,
            ]);

            // Debit the Receivables account for the remaining due amount
            $journalEntry->lineItems()->create([
                'account_id' => 3,
                'debit' => $dueAmount,
                'credit' => 0,
            ]);

            // Credit the Sales account (minus discount)
            $journalEntry->lineItems()->create([
                'account_id' => 6,
                'debit' => 0,
               /* 'credit' => $totalAmount + $discountAmount,*/
                'credit' => $totalAmount,
            ]);

            // Debit the Discounts Given account (if discount exists)
            /*if ($discountAmount > 0) {
                $journalEntry->lineItems()->create([
                    'account_id' => $discountAccount->id,
                    'debit' => $discountAmount,
                    'credit' => 0,
                ]);
            }*/

            // Scenario 3: Full Due
        } else {
            // Debit the Receivables account for the full amount
            $journalEntry->lineItems()->create([
                'account_id' => 3,
                'debit' => $totalAmount,
                'credit' => 0,
            ]);

            // Credit the Sales account (minus discount)
            $journalEntry->lineItems()->create([
                'account_id' => 6,
                'debit' => 0,
                /*'credit' => $totalAmount + $discountAmount,*/
                'credit' => $totalAmount,
            ]);

            // Debit the Discounts Given account (if discount exists)
           /* if ($discountAmount > 0) {
                $journalEntry->lineItems()->create([
                    'account_id' => $discountAccount->id,
                    'debit' => $discountAmount,
                    'credit' => 0,
                ]);
            }*/
        }
    }

}
