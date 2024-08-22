<?php

namespace App\Http\Controllers;

use App\Models\SalaryIncrement;
use App\Models\User;
use Illuminate\Http\Request;

class SalaryIncrementController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric',
            'new_salary' => 'required|numeric',
            'increment_date' => 'required|date',
        ]);
        $user = User::findOrFail($request->user_id);

        // Calculate the new salary
        $newSalary = $user->salary + $request->input('amount');
        SalaryIncrement::create([
            'user_id' => $request->user_id,
            'amount' => $request->amount,
            'new_salary' => $newSalary,
            'increment_date' => $request->increment_date,
        ]);

        return redirect()->route('users.show', $request->user_id)
            ->with('success', 'Salary increment added successfully.');
    }
}
