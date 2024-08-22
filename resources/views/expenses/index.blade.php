@extends('layouts.app')

@section('title','Expenses')
@section('content')
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('expenses.store') }}" method="POST" class="mt-4">
                @csrf
                <div class="row">
                    <div class="form-group col-md-2">
                        <label for="date" class="form-label">Date:</label>
                        <input type="text" name="date" id="date" class="form-control flatpickr"
                               value="{{ date('Y-m-d') }}" required>
                    </div>


                    <div class="form-group col-md-2">
                        <label for="expense_category_id" class="form-label">Category:</label>
                        <select name="expense_category_id" id="expense_category_id" class="form-select" required
                                data-placeholder="-- Select --">
                            <option value=""></option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="amount" class="form-label">Amount:</label>
                        <input type="number" name="amount" id="amount" step="0.01" class="form-control"
                               required>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="description" class="form-label">Description:</label>
                        <input type="text" name="description" id="description" class="form-control">
                    </div>

                    <div class="form-group col-md-3">
                        <label for="account_id" class="form-label">Account:</label>
                        <select name="account_id" id="account_id" class="form-select">
                            @foreach($accounts as $account)
                                <option value="{{ $account->id }}">{{ $account->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col d-flex align-items-end">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Category</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($expenses as $expense)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($expense->date)->format('d/m/Y') }}</td>
                        <td>{{ $expense->amount }}</td>
                        <td>{{ $expense->expenseCategory->name }}</td>
                        <td>{{ $expense->description }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <a class="btn btn-icon  btn-info"
                                   href="{{ route('expenses.edit', $expense->id) }}">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <form action="{{ route('expenses.destroy', $expense->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-icon btn-danger">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $expenses->links() }}
        </div>
    </div>
@endsection
@section('js')
    <script>
        $("select").select2({
            theme: "bootstrap",
            width: "100%"
        });
    </script>
@endsection
