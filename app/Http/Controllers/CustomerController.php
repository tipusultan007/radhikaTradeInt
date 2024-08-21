<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    // List all customers
    public function index()
    {
        $customers = Customer::all();
        return view('customers.index', compact('customers'));
    }

    // Show the form for creating a new customer
    public function create()
    {
        return view('customers.create');
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
}
