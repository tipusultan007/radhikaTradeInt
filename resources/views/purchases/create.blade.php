@extends('layouts.app')
@section('title','Add New Purchase')
@section('create-button')
    <a href="{{ route('purchases.index') }}" class="btn btn-secondary">Cancel</a>
@endsection
@section('content')
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    <div class="container">
        <form action="{{ route('purchases.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="product_id">Product</label>
                <select name="product_id" id="product_id" class="form-control" required>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="quantity_kg">Quantity (kg)</label>
                <input type="number" name="quantity_kg" id="quantity_kg" class="form-control" required min="0" step="0.01">
            </div>

            <div class="form-group">
                <label for="purchase_price">Purchase Price</label>
                <input type="number" name="purchase_price" id="purchase_price" class="form-control" required min="0" step="0.01">
            </div>
            <div class="form-group">
                <label for="">Account</label>
                <select name="account_id" id="account_id" class="select2">
                    <option value=""></option>
                    @forelse($accounts as $account)
                        <option value="{{ $account->id }}">{{ $account->name }}</option>
                    @empty
                        <option value="" disabled>No payment method found!</option>
                    @endforelse
                </select>
            </div>
            <div class="form-group">
                <label for="purchase_price">Purchase Date</label>
                <input type="text" name="date" id="date" class="form-control flatpickr" value="{{ date('Y-m-d') }}" required>
            </div>
            <button type="submit" class="btn btn-success">Add Purchase</button>
        </form>
    </div>
@endsection
@section('js')
    <script>
        $(".select2").select2({
            theme: 'bootstrap',
            width: '100%'
        })
    </script>
@endsection
