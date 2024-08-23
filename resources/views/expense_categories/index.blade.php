@extends('layouts.app')
@section('title','Expense categories')
@section('create-button')
    <a class="btn btn-primary" href="{{ route('expense_categories.create') }}">Create New Category</a>
@endsection
@section('content')
    @if(session('success'))
        <p>{{ session('success') }}</p>
    @endif
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('expense_categories.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label for="parent_id">Parent Category</label>
                                <select name="parent_id" class="form-control">
                                    <option value="">No Parent</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('parent_id', $expenseCategory->parent_id ?? '') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12 form-group">
                                <label class="form-label" for="name">Name:</label>
                                <input type="text" name="name" class="form-control" id="name" required>
                            </div>
                            <div class="col-md-12 form-group d-flex align-items-end">
                                <button type="submit" class="btn btn-success">Create</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Total Expense</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($categories as $category)
                            <tr>
                                <td>{{ $category->name }}</td>
                                <td>{{ number_format($category->total_expense, 2) }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a class="btn btn-primary" href="{{ route('expense_categories.show', $category->id) }}">Show</a>
                                        <a class="btn btn-secondary" href="{{ route('expense_categories.edit', $category->id) }}">Edit</a>
                                        <form action="{{ route('expense_categories.destroy', $category->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>


@endsection
