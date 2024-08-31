<?php

namespace App\Http\Controllers;

use App\Models\JournalEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JournalController extends Controller
{
    public function index()
    {
        $journalEntries = JournalEntry::with('lineItems', 'journalable')
            ->orderBy('date','desc')->paginate(10);
        return view('journals.index', compact('journalEntries'));
    }


}
