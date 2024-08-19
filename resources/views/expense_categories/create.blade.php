@extends('layouts.app')

@section('content')
    <h1>Create Expense Category</h1>

    <form action="{{ route('expense_categories.store') }}" method="POST">
        @csrf
        <div class="form-group">
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
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" required>
        <button type="submit">Create</button>
    </form>
@endsection
