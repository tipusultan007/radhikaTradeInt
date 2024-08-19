@extends('layouts.app')

@section('content')
    <form action="{{ route('expenses.store') }}" method="POST" class="container mt-4">
        @csrf

        <div class="form-group mb-3">
            <label for="date" class="form-label">Date:</label>
            <input type="date" name="date" id="date" class="form-control" required>
        </div>

        <div class="form-group mb-3">
            <label for="amount" class="form-label">Amount:</label>
            <input type="number" name="amount" id="amount" step="0.01" class="form-control" required>
        </div>

        <div class="form-group mb-3">
            <label for="expense_category_id" class="form-label">Category:</label>
            <select name="expense_category_id" id="expense_category_id" class="form-select" required>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group mb-3">
            <label for="description" class="form-label">Description:</label>
            <input type="text" name="description" id="description" class="form-control">
        </div>

        <div class="form-group mb-3">
            <label for="account_id" class="form-label">Account:</label>
            <select name="account_id" id="account_id" class="form-select">
                <option value="">None</option>
                @foreach($accounts as $account)
                    <option value="{{ $account->id }}">{{ $account->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>

@endsection
