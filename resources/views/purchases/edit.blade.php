@extends('layouts.app')
@section('title','Edit Purchase')
@section('create-button')
    <a href="{{ route('purchases.index') }}" class="btn btn-secondary">Cancel</a>
@endsection
@section('content')
    <div class="container">
        <form action="{{ route('purchases.update', $purchase->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="product_id">Product</label>
                <select name="product_id" id="product_id" class="form-control" required>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" {{ $product->id == $purchase->product_id ? 'selected' : '' }}>
                            {{ $product->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="quantity_kg">Quantity (kg)</label>
                <input type="number" name="quantity_kg" id="quantity_kg" class="form-control" value="{{ $purchase->quantity_kg }}" required min="0" step="0.01">
            </div>

            <div class="form-group">
                <label for="purchase_price">Purchase Price</label>
                <input type="number" name="purchase_price" id="purchase_price" class="form-control" value="{{ $purchase->purchase_price }}" required min="0" step="0.01">
            </div>
            <div class="form-group">
                <label for="purchase_price">Purchase Date</label>
                <input type="text" name="date" id="date" class="form-control flatpickr" value="{{ $purchase->date }}" required>
            </div>
            <button type="submit" class="btn btn-success">Update Purchase</button>
        </form>
    </div>
@endsection
