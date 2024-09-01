<?php
namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Customer;
use App\Models\PackagingType;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleCommission;
use App\Models\SaleDetail;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    // List all sales
    public function index()
    {
        $sales = Sale::with('details.product', 'details.packagingType', 'customer')->orderByDesc('date')->paginate(10);
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
        $accounts = Account::where('type','asset')->whereNotIn('id',[2,3,4])->get();

        // Return the view with the sale data
        return view('sales.edit', compact('sale', 'customers', 'products', 'packagingTypes','accounts'));
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

            $commission = 0;

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

                    $commission += ($item['price'] * $item['quantity']) - ($warehouse->commission_agent_price * $item['quantity']);

                    $warehouse->stock -= $item['quantity'];
                    if ($warehouse->stock < 0) {
                        throw new \Exception('Insufficient stock for this sale');
                    }
                    $warehouse->save();
                } else {
                    throw new \Exception('No stock found for the specified product and packaging type');
                }
            }

            $this->createOrUpdateJournalEntryForSale($sale, $commission);

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

        DB::transaction(function () use ($validated,$request, $id) {
            // Fetch the existing sale record
            $sale = Sale::findOrFail($id);

            $commission = 0;
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
            $sale->update($request->except('items'));

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
                    $commission += ($item['price'] * $item['quantity']) - ($warehouse->commission_agent_price * $item['quantity']);
                    $warehouse->stock -= $item['quantity'];
                    if ($warehouse->stock < 0) {
                        throw new \Exception('Insufficient stock for this sale');
                    }
                    $warehouse->save();
                } else {
                    throw new \Exception('No stock found for the specified product and packaging type');
                }
            }

            $this->createOrUpdateJournalEntryForSale($sale, $commission);
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

            $sale->journalEntry->lineItems()->delete();
            $sale->journalEntry()->delete();

            SaleCommission::where('sale_id', $id)->delete();

            $sale->delete();
        });

        return redirect()->route('sales.index')->with('success', 'Sale deleted successfully');
    }
    private function createOrUpdateJournalEntryForSale(Sale $sale, $commission = 0)
    {
        // Retrieve accounts
        $salesAccount = 6;
        $receivableAccount = 3;

        $paidAmount = $sale->paid_amount;
        $totalAmount = $sale->total;
        $discountAmount = $sale->discount ?? 0; // If no discount, assume 0
        $dueAmount = $totalAmount - $paidAmount;

        if ($sale->referrer_id != '' && $commission > 0) {
            $commissionAccount = Account::where('name','Sales Commission')->first();
            $salesCommission = SaleCommission::updateOrCreate(
                [
                    'sale_id' => $sale->id,
                ],
                [
                    'customer_id' => $sale->referrer_id,
                    'commission' => $commission
                ]
            );

        }else{
            SaleCommission::where('sale_id', $sale->id)->delete();
        }

        // Find or create the Journal Entry for the sale
        $journalEntry = $sale->journalEntry()->first(); // Get the existing journal entry if it exists

        if ($journalEntry) {
            // Update the existing journal entry
            $journalEntry->update([
                'customer_id' => $sale->customer_id,
                'date' => $sale->date,
            ]);
            // Clear previous line items
            $journalEntry->lineItems()->delete();
        } else {
            // Create a new journal entry
            $journalEntry = $sale->journalEntry()->create([
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
                'account_id' => $sale->account_id,
                'debit' => $paidAmount,
                'credit' => 0,
            ]);

            // Credit the Sales account (minus discount)
            $journalEntry->lineItems()->create([
                'account_id' => $salesAccount,
                'debit' => 0,
                'credit' => $totalAmount - $commission,
            ]);

            if ($sale->referrer_id != '' && $commission > 0){
                $journalEntry->lineItems()->create([
                    'account_id' => $commissionAccount->id,
                    'debit' => 0,
                    'credit' => $commission,
                ]);
            }

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
                'account_id' => $receivableAccount,
                'debit' => $dueAmount,
                'credit' => 0,
            ]);

            // Credit the Sales account (minus discount)
            $journalEntry->lineItems()->create([
                'account_id' => $salesAccount,
                'debit' => 0,
                'credit' => $totalAmount - $commission,
            ]);

            if ($sale->referrer_id != '' && $commission > 0){
                $journalEntry->lineItems()->create([
                    'account_id' => $commissionAccount->id,
                    'debit' => 0,
                    'credit' => $commission,
                ]);
            }

            // Scenario 3: Full Due
        } else {
            // Debit the Receivables account for the full amount
            $journalEntry->lineItems()->create([
                'account_id' => $receivableAccount,
                'debit' => $totalAmount,
                'credit' => 0,
            ]);

            // Credit the Sales account (minus discount)
            $journalEntry->lineItems()->create([
                'account_id' => $salesAccount,
                'debit' => 0,
                'credit' => $totalAmount - $commission,
            ]);

            if ($sale->referrer_id != '' && $commission > 0){
                $journalEntry->lineItems()->create([
                    'account_id' => $commissionAccount->id,
                    'debit' => 0,
                    'credit' => $commission,
                ]);
            }
        }
    }

}
