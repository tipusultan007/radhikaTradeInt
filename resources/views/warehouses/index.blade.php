@extends('layouts.app')

@section('title', 'Warehouse Products')
@section('create-button')
    <a href="{{ route('warehouses.create') }}" class="btn btn-primary">Add New Product to Warehouse</a>
@endsection
@section('content')
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif


    @if ($warehouses->count())
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Product</th>
                <th>Packaging Type</th>
                <th>Stock</th>
                <th>Cost</th>
                <th>Sale Price</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($warehouses as $warehouse)
                <tr>
                    <td>{{ $warehouse->product->name }}</td>
                    <td>{{ $warehouse->packagingType->type }}</td>
                    <td>{{ $warehouse->stock }}</td>
                    <td>{{ $warehouse->cost }}</td>
                    <td>{{ $warehouse->sale_price }}</td>
                    <td>
                        <a href="{{ route('warehouses.edit', $warehouse->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('warehouses.destroy', $warehouse->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <p>No products found in warehouse.</p>
    @endif
@endsection
