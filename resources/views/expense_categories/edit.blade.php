@extends('layouts.app')
@section('title','Edit Expense Category')
@section('create-button')
    <a href="{{ route('expense_categories.index') }}" class="btn btn-secondary">Back to List</a>
@endsection
@section('content')

    <form action="{{ route('expense_categories.update', $expenseCategory->id) }}" method="POST">
        @csrf
        @method('PUT')
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
                <input type="text" name="name" class="form-control" value="{{ old('name', $expenseCategory->name) }}" id="name" required>
            </div>
            <div class="col-md-12 form-group d-flex align-items-end">
                <button type="submit" class="btn btn-success">Create</button>
            </div>
        </div>
    </form>

@endsection
