@extends('layouts.app')

@section('title', isset($product) ? 'Edit Product' : 'Create Product')

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ isset($product) ? route('products.update', $product->id) : route('products.store') }}" method="POST">
                @csrf
                @if (isset($product))
                    @method('PUT')
                @endif

                <div class="form-group">
                    <label for="name">Product Name</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $product->name ?? '') }}" required>
                </div>

                <div class="form-group">
                    <label for="initial_stock_kg">Initial Stock (kg)</label>
                    <input type="number" step="0.01" name="initial_stock_kg" id="initial_stock_kg" class="form-control" value="{{ old('initial_stock_kg', $product->initial_stock_kg ?? '') }}" required>
                </div>

               <div class="form-group">
                   <button type="submit" class="btn btn-success">{{ isset($product) ? 'Update' : 'Create' }}</button>
                   <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancel</a>
               </div>
            </form>
        </div>
    </div>
@endsection
