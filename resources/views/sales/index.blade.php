@extends('layouts.app')
@section('title','Sales')
@section('create-button')
    <a href="{{ route('sales.create') }}" class="btn btn-primary">Create New Sale</a>
@endsection
@section('content')
    <table class="table table-striped">
        <thead>
        <tr>
            <th>Invoice #</th>
            <th>Customer</th>
            <th>Total Amount</th>
            <th>Date</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($sales as $sale)
            <tr>
                <td>{{ $sale->invoice_no }}</td>
                <td>{{ $sale->customer->name }}</td>
                <td>{{ $sale->total }}</td>
                <td>{{ \Carbon\Carbon::parse($sale->date)->format('d/m/Y') }}</td>
                <td>
                    <a href="{{ route('sales.show', $sale->id) }}" class="btn btn-info btn-sm">View</a>
                    <a href="{{ route('sales.edit', $sale->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('sales.destroy', $sale->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this sale?');">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
