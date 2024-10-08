<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\SaleCommission;
use Illuminate\Http\Request;

class SaleCommissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $accounts = Account::where('type','asset')->get();
        $salesCommissions = SaleCommission::orderbyDesc('created_at')->paginate(10);
        return view('sales_commissions.index', compact('salesCommissions','accounts'));
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(SaleCommission $saleCommission)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SaleCommission $saleCommission)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SaleCommission $saleCommission)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SaleCommission $saleCommission)
    {
        //
    }

}
