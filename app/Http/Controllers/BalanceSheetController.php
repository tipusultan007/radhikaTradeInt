<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;

class BalanceSheetController extends Controller
{
    public function show()
    {
        $assets = Account::where('type', 'asset')->get();
        $liabilities = Account::where('type', 'liability')->get();
        $equity = Account::where('type', 'equity')->get();

        $totalAssets = $assets->sum(function($account) {
            return $account->balance();
        });

        $totalLiabilities = $liabilities->sum(function($account) {
            return $account->balance();
        });

        $totalEquity = $equity->sum(function($account) {
            return $account->balance();
        });

        return view('balance_sheet', compact('assets', 'liabilities', 'equity', 'totalAssets', 'totalLiabilities', 'totalEquity'));
    }
}
