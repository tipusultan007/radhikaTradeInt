<?php
namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Asset;
use App\Models\JournalEntry;
use App\Models\JournalEntryLineItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AssetController extends Controller
{
    // Display a listing of the assets
    public function index()
    {
        $assets = Asset::all();
        return view('assets.index', compact('assets'));
    }

    // Show the form for creating a new asset
    public function create()
    {
        $accounts = Account::where('type','asset')->get();
        return view('assets.create',compact('accounts'));
    }

    // Store a newly created asset in storage
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'value' => 'required|numeric|min:0',
            'purchase_date' => 'required|date',
        ]);

        DB::beginTransaction();

        try {
            // Create the Asset
            $asset = Asset::create($request->all());

            // Create a Journal Entry for the Asset
            $journalEntry = JournalEntry::create([
                'journalable_type' => Asset::class,
                'journalable_id' => $asset->id,
                'type' => 'asset',
                'date' => $asset->purchase_date,
                'description' => 'Purchase of ' . $asset->name,
            ]);

            // Add line item to debit the Asset account
            JournalEntryLineItem::create([
                'journal_entry_id' => $journalEntry->id,
                'account_id' => 4,
                'debit' => $asset->value,
                'credit' => 0,
            ]);

            // Add line item to credit the Cash/Bank account
            JournalEntryLineItem::create([
                'journal_entry_id' => $journalEntry->id,
                'account_id' => $asset->account_id,
                'debit' => 0,
                'credit' => $asset->value,
            ]);

            DB::commit();
            return redirect()->route('assets.index')->with('success', 'Asset created successfully with a journal entry.');
        }catch (\Exception $exception){
            DB::rollBack();
            return redirect()->back()->with('error', $exception->getMessage());
        }

    }

    // Display the specified asset
    public function show(Asset $asset)
    {
        return view('assets.show', compact('asset'));
    }

    // Show the form for editing the specified asset
    public function edit(Asset $asset)
    {
        $accounts = Account::where('type','asset')->get();
        return view('assets.edit', compact('asset','accounts'));
    }

    // Update the specified asset in storage
    public function update(Request $request, Asset $asset)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'value' => 'required|numeric|min:0',
            'purchase_date' => 'required|date',
        ]);

        DB::beginTransaction();

        try {
            // Update the Asset
            $asset->update($request->all());

            // Optionally, adjust the journal entry if the asset's value has changed
            $journalEntry = $asset->journalEntry;

            if ($journalEntry) {
                $journalEntry->lineItems()->where('account_id', $asset->account_id)->update([
                    'debit' => $asset->value,
                    'date' => $asset->purchase_date,
                ]);

                $journalEntry->lineItems()->where('account_id', $asset->account_id)->update([
                    'credit' => $asset->value,
                    'date' => $asset->purchase_date,
                ]);
            }

            DB::commit();
            return redirect()->route('assets.index')->with('success', 'Asset updated successfully.');
        }catch (\Exception $exception){
            DB::rollBack();
            return redirect()->back()->with('error', $exception->getMessage());
        }

    }

    // Remove the specified asset from storage
    public function destroy(Asset $asset)
    {
        DB::beginTransaction();

        try {
            // Find the related journal entry
            $journalEntry = JournalEntry::where('journalable_type', Asset::class)
                ->where('journalable_id', $asset->id)
                ->first();

            // If a related journal entry exists, delete its line items first
            if ($journalEntry) {
                // Delete all related journal entry line items
                $journalEntry->lineItems()->delete();

                // Then delete the journal entry itself
                $journalEntry->delete();
            }

            // Finally, delete the asset
            $asset->delete();

            DB::commit();
            return redirect()->route('assets.index')->with('success', 'Asset and related journal entry deleted successfully.');
        }catch (\Exception $exception){
            DB::rollBack();
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }
}
