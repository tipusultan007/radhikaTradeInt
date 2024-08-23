@extends('layouts.app')
@section('title',$expenseCategory->name.' - Expense Category')
@section('content')
    <!-- Expense Category Details -->
    <table class="table table-bordered w-50">
        <tbody>
        <tr>
            <th>Account</th>
            <td>{{ $expenseCategory->account_id ? $expenseCategory->account->name : 'N/A' }}</td>
        </tr>
        <tr>
            <th>Parent Category</th>
            <td>{{ $expenseCategory->parent_id ? $expenseCategory->parent->name : 'N/A' }}</td>
        </tr>
        <tr>
            <th>Total Expenses</th>
            <td>{{ number_format($totalExpense, 2) }}</td>
        </tr>
        </tbody>
    </table>

    <!-- Related Expenses -->
    <h3>Expenses</h3>
    @if ($expenseCategory->expenses->isEmpty())
        <p>No expenses found for this category.</p>
    @else
        <table class="table table-bordered">
            <thead>
            <tr>
                <th class="text-center">Date</th>
                <th class="text-center">Account</th>
                <th class="text-center">Description</th>
                <th class="text-center">Amount</th>

            </tr>
            </thead>
            <tbody>
            @foreach ($expenses as $expense)
                <tr>
                    <td>{{ $expense->date ? $expense->date->format('d/m/Y') : 'N/A' }}</td>
                    <td>{{ $expense->account_id ? $expense->account->name : 'N/A' }}</td>
                    <td>{{ $expense->description }}</td>
                    <td class="text-end">{{ number_format($expense->amount, 2) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <!-- Pagination Links -->
        {{ $expenses->links() }}
    @endif

    <a href="{{ route('expense_categories.index') }}" class="btn btn-primary">Back to Categories</a>
@endsection
