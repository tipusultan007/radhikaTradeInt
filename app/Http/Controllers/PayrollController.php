<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Payroll;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    // Show the form to create a new payroll entry
    public function create()
    {
        $users = User::all();
        return view('payroll.create', compact('users'));
    }

    // Store the new payroll entry in the database
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id', // Ensure user ID is valid
            'bonus' => 'nullable|numeric',
            'deductions' => 'nullable|numeric',
            'pay_date' => 'required|date',
            'month' => 'required|string',
        ]);

        // Get the user based on user_id from the request
        $user = User::findOrFail($request->input('user_id'));
        $basicSalary = $user->getLastIncrementedSalary();

        // Check if the user has already been paid for the given month
        $existingPayroll = Payroll::where('user_id', $user->id)
            ->where('month', $request->input('month'))
            ->first();

        if ($existingPayroll) {
            return redirect()->route('payroll.create')
                ->with('error', 'This user has already been paid for this month.');
        }

        // Calculate net pay
        $bonus = $request->input('bonus', 0);
        $deductions = $request->input('deductions', 0);
        $netPay = $basicSalary + $bonus - $deductions;

        // Create the payroll entry
        $payroll = new Payroll([
            'salary' => $basicSalary,
            'bonus' => $bonus,
            'deductions' => $deductions,
            'net_pay' => $netPay,
            'pay_date' => $request->input('pay_date'),
            'month' => $request->input('month'), // Store month
        ]);

        $user->payrolls()->save($payroll);

        return redirect()->route('payroll.index')
            ->with('success', 'Payroll entry created successfully.');
    }


    // Show all payroll entries for a user
    /*public function index($userId)
    {
        $user = User::findOrFail($userId);
        $payrolls = $user->payrolls()->latest()->get();

        return view('payroll.index', compact('user', 'payrolls'));
    }*/
    public function index()
    {
        $payrolls = Payroll::latest()->with('user')->get(); // Load all payrolls with the associated user

        return view('payroll.index', compact('payrolls'));
    }

    // Show a form to edit a payroll entry
    public function edit($id)
    {
        $payroll = Payroll::findOrFail($id);
        $users = User::all(); // Get all users for selection

        return view('payroll.edit', compact('payroll', 'users'));
    }

    // Update a payroll entry
    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'bonus' => 'nullable|numeric',
            'deductions' => 'nullable|numeric',
            'pay_date' => 'required|date',
            'month' => 'required|string',
        ]);

        // Find the payroll entry to update
        $payroll = Payroll::findOrFail($id);
        $user = User::findOrFail($request->input('user_id'));
        $basicSalary = $user->getLastIncrementedSalary();

        // Calculate net pay
        $bonus = $request->input('bonus', 0);
        $deductions = $request->input('deductions', 0);
        $netPay = $basicSalary + $bonus - $deductions;

        // Update the payroll entry
        $payroll->user_id = $user->id;
        $payroll->salary = $basicSalary;
        $payroll->bonus = $bonus;
        $payroll->deductions = $deductions;
        $payroll->net_pay = $netPay;
        $payroll->pay_date = $request->input('pay_date');
        $payroll->month = $request->input('month');

        $payroll->save(); // Save the updated payroll entry

        return redirect()->route('payroll.index')
            ->with('success', 'Payroll entry updated successfully.');
    }

    // Delete a payroll entry
    public function destroy( $payrollId)
    {
        $payroll = Payroll::findOrFail($payrollId);
        $payroll->delete();

        return redirect()->route('payroll.index')
            ->with('success', 'Payroll entry deleted successfully.');
    }
}
