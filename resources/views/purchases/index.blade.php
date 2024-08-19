@extends('layouts.app')
@section('title','Purchase')
@section('create-button')
    <a href="{{ route('purchases.create') }}" class="btn btn-primary">Add New Purchase</a>
@endsection
@section('content')
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <table class="table mt-3">
        <thead>
        <tr>
            <th>ID</th>
            <th>Product</th>
            <th>Quantity (kg)</th>
            <th>Purchase Price</th>
            <th>Date</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($purchases as $purchase)
            <tr>
                <td>{{ $purchase->id }}</td>
                <td>{{ $purchase->product->name }}</td>
                <td>{{ $purchase->quantity_kg }}</td>
                <td>{{ $purchase->purchase_price }}</td>
                <td>{{ \Carbon\Carbon::parse($purchase->date)->format('d/m/Y') }}</td>
                <td>
                    <a href="{{ route('purchases.edit', $purchase->id) }}" class="btn btn-warning">Edit</a>
                    <form action="{{ route('purchases.destroy', $purchase->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
