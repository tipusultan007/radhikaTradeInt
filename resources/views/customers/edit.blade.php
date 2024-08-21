@extends('layouts.app')

@section('title', 'Edit Customer')

@section('content')
    <h1>Edit Customer</h1>

    <form action="{{ route('customers.update', $customer->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $customer->name) }}" required>
        </div>

        <div class="form-group">
            <label for="phone">Phone</label>
            <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $customer->phone) }}">
        </div>

        <div class="form-group">
            <label for="address">Address</label>
            <textarea name="address" id="address" class="form-control">{{ old('address', $customer->address) }}</textarea>
        </div>
        <div class="form-group">
            <label for="type">Customer Type</label>
            <select name="type" id="type" class="form-control" required>
                <option value="dealer" {{ old('type', $customer->type) == 'dealer' ? 'selected' : '' }}>Dealer</option>
                <option value="commission_agent" {{ old('type', $customer->type) == 'commission_agent' ? 'selected' : '' }}>Commission Agent</option>
                <option value="retailer" {{ old('type', $customer->type) == 'retailer' ? 'selected' : '' }}>Retailer</option>
                <option value="wholesale" {{ old('type', $customer->type) == 'wholesale' ? 'selected' : '' }}>Wholesale</option>
                <option value="retail" {{ old('type', $customer->type) == 'retail' ? 'selected' : '' }}>Retail</option>
                <option value="customer" {{ old('type', $customer->type) == 'customer' ? 'selected' : '' }}>Customer</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Update</button>
        <a href="{{ route('customers.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
@endsection
