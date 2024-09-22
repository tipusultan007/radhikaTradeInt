<?php

namespace App\Http\Controllers;

use App\Exports\CustomersExport;
use App\Models\Customer;
use App\Models\JournalEntry;
use App\Models\JournalEntryLineItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class CustomerController extends Controller
{
    // List all customers
    public function index(Request $request)
    {
        $query = Customer::query();

        // Apply filters as before
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }

        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        if ($request->filled('phone')) {
            $query->where('phone', 'like', '%' . $request->input('phone') . '%');
        }

        // Check for export options
        if ($request->has('export') && $request->export == 'excel') {
            return Excel::download(new CustomersExport($query), 'customers.xlsx');
        }

        if ($request->has('export') && $request->export == 'pdf') {
            $customers = $query->get();
            $pdf = Pdf::loadView('customers.pdf', compact('customers'));
            return $pdf->download('customers.pdf');
        }

        $customers = $query->paginate(100);
        return view('customers.index', compact('customers'));
    }


    // Show the form for creating a new customer
    public function create()
    {
        return view('customers.create');
    }

    public function show($id)
    {
        $journalEntries = JournalEntry::with('lineItems', 'journalable')
            ->where('customer_id', $id)->whereIn('type',['sale','customer_payment'])
            ->orderBy('date','asc')->get();

        $customer = Customer::find($id);

        return view('customers.show', compact('customer','journalEntries'));
    }
    // Store a newly created customer in storage
    public function store(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'type' => 'required|in:dealer,commission_agent,retailer,wholesale,retail,customer', // Validate type
        ]);

        // Create the customer
        Customer::create($validated);

        // Redirect back to the customers index with a success message
        return redirect()->route('customers.index')->with('success', 'Customer created successfully.');
    }


    // Show the form for editing the specified customer
    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        return view('customers.edit', compact('customer'));
    }

    // Update the specified customer in storage
    public function update(Request $request, Customer $customer)
    {
        // Validate the request data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'type' => 'required|in:dealer,commission_agent,retailer,wholesale,retail,customer', // Validate type
        ]);

        // Update the customer with the validated data
        $customer->update($validated);

        // Redirect back to the customers index with a success message
        return redirect()->route('customers.index')->with('success', 'Customer updated successfully.');
    }


    // Remove the specified customer from storage
    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();

        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully.');
    }

    public function getCustomers(Request $request)
    {
        $type = $request->input('type');
        $customers = Customer::where('type', $type)->get(['id', 'name']);

        return response()->json([
            'customers' => $customers
        ]);
    }

}
