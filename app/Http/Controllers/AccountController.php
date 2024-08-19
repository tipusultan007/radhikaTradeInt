<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    // Display a listing of the accounts
    public function index()
    {
        $accounts = Account::all();
        return view('accounts.index', compact('accounts'));
    }

    // Show the form for creating a new account
    public function create()
    {
        $accounts = Account::whereNull('parent_id')->get();
        return view('accounts.create',compact('accounts'));
    }

    // Store a newly created account in the database
    public function store(Request $request)
    {
        $request->validate([
            'parent_id' => 'nullable|integer',
            'name' => 'required|string|max:255',
            'type' => 'required|in:asset,liability,equity,revenue,expense',
            'code' => 'required|string|max:255|unique:accounts,code',
            'opening_balance' => 'nullable|numeric|min:0',
        ]);

        Account::create($request->all());

        return redirect()->route('accounts.index')->with('success', 'Account created successfully.');
    }

    // Show the form for editing the specified account
    public function edit($id)
    {
        $account = Account::findOrFail($id);
        $parentAccounts = Account::whereNull('parent_id')->get();
        return view('accounts.edit', compact('account','parentAccounts'));
    }

    // Update the specified account in the database
    public function update(Request $request, $id)
    {
        $request->validate([
            'parent_id' => 'nullable|integer',
            'name' => 'required|string|max:255',
            'type' => 'required|in:asset,liability,equity,revenue,expense',
            'code' => 'required|string|max:255|unique:accounts,code,' . $id,
            'opening_balance' => 'nullable|numeric|min:0',
        ]);

        $account = Account::findOrFail($id);
        $account->update($request->all());

        return redirect()->route('accounts.index')->with('success', 'Account updated successfully.');
    }

    // Remove the specified account from the database
    public function destroy($id)
    {
        $account = Account::findOrFail($id);
        $account->delete();

        return redirect()->route('accounts.index')->with('success', 'Account deleted successfully.');
    }
}
