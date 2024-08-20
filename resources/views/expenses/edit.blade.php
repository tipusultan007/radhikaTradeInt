@extends('layouts.app')

@section('title','Edit Expenses')

@section('create-button')
    <a class="btn btn-secondary" href="{{ route('expenses.index') }}">Back to List</a>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('expenses.update', $expense->id) }}" method="POST" class="mb-4">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="date">Date:</label>
                    <input type="text" name="date" id="date" class="form-control flatpickr" value="{{ old('date', $expense->date) }}" required>
                </div>

                <div class="form-group">
                    <label for="expense_category_id">Category:</label>
                    <select name="expense_category_id" id="expense_category_id" class="form-control" required>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $expense->expense_category_id == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="amount">Amount:</label>
                    <input type="number" name="amount" class="form-control" step="0.01" value="{{ old('amount', $expense->amount) }}" required>
                </div>
                <div class="form-group col-md-3">
                    <label for="account_id" class="form-label">Account:</label>
                    <select name="account_id" id="account_id" class="form-select">
                        @foreach($accounts as $account)
                            <option value="{{ $account->id }}">{{ $account->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="description">Description:</label>
                    <input type="text" name="description" id="description" class="form-control" value="{{ old('description', $expense->description) }}">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>

    <a href="{{ route('expenses.index') }}" class="btn btn-secondary">Back to List</a>
@endsection
@section('js')
    <script>
        $("select").select2({
            theme: 'bootstrap'
        })
    </script>
@endsection
