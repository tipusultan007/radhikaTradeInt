@extends('layouts.app')

@section('title', 'Create Customer')

@section('content')
    <form action="{{ route('customers.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
        </div>

        <div class="form-group">
            <label for="phone">Phone</label>
            <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone') }}">
        </div>

        <div class="form-group">
            <label for="address">Address</label>
            <textarea name="address" id="address" class="form-control">{{ old('address') }}</textarea>
        </div>

        <div class="form-group">
            <label for="type">Customer Type</label>
            <select name="type" id="type" class="form-control" required>
                <option value="">Select Customer Type</option>
                <option value="dealer" {{ old('type') == 'dealer' ? 'selected' : '' }}>Dealer</option>
                <option value="commission_agent" {{ old('type') == 'commission_agent' ? 'selected' : '' }}>Commission Agent</option>
                <option value="retailer" {{ old('type') == 'retailer' ? 'selected' : '' }}>Retailer</option>
                <option value="wholesale" {{ old('type') == 'wholesale' ? 'selected' : '' }}>Wholesale</option>
                <option value="retail" {{ old('type') == 'retail' ? 'selected' : '' }}>Retail</option>
                <option value="customer" {{ old('type') == 'customer' ? 'selected' : '' }}>Customer</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Create</button>
        <a href="{{ route('customers.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
@endsection
