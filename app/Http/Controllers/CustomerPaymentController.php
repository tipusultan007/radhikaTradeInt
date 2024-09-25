<?php

namespace App\Http\Controllers;

use App\Models\CustomerPayment;
use App\Models\JournalEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomerPaymentController extends Controller
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
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'amount' => 'required|numeric',
            'date' => 'required|date',
            'account_id' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $payment = CustomerPayment::create($request->all());

            $journalEntry = $payment->journalEntry()->create([
                'customer_id' => $request->customer_id,
                'type' => 'customer_payment',
                'date' => $request->date,
                'description' => 'Customer Payment',
                'user_id' => Auth::id(),
            ]);
            $journalEntry->lineItems()->create([
                'account_id' => $request->account_id,
                'debit' => $request->amount,
                'credit' => 0,
            ]);

            // Credit the Sales account (minus discount)
            $journalEntry->lineItems()->create([
                'account_id' => 3,
                'debit' => 0,
                'credit' => $request->amount,
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Customer Payment Created Successfully');
        }catch (\Exception $e){
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(CustomerPayment $customerPayment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CustomerPayment $customerPayment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CustomerPayment $customerPayment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CustomerPayment $customerPayment)
    {
        //
    }
}
