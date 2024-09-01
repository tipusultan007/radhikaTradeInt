@extends('layouts.app')
@section('title','Sales')
@section('create-button')
    <a href="{{ route('sales.create') }}" class="btn btn-primary">Create New Sale</a>
@endsection
@section('content')
    <table class="table table-striped table-bordered">
        <thead>
        <tr>
            <th>Date</th>
            <th>Invoice #</th>
            <th>Customer</th>
            <th>Type</th>
            <th>Total Amount</th>
            <th class="text-end">Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($sales as $sale)
            <tr>
                <td>{{ $sale->date->format('d/m/Y') }}</td>
                <td>{{ $sale->invoice_no }}</td>
                <td>{{ $sale->customer->name }}</td>
                <td>
                    @php
                        $badgeClass = '';
                        switch($sale->customer->type) {
                            case 'dealer':
                                $badgeClass = 'badge-primary';
                                break;
                            case 'commission_agent':
                                $badgeClass = 'badge-success';
                                break;
                            case 'retailer':
                                $badgeClass = 'badge-info';
                                break;
                            case 'wholesale':
                                $badgeClass = 'badge-warning';
                                break;
                            case 'retail':
                                $badgeClass = 'badge-secondary';
                                break;
                            case 'customer':
                                $badgeClass = 'badge-black';
                                break;
                            default:
                                $badgeClass = 'badge-count';
                                break;
                        }
                    @endphp
                    <span class="badge {{ $badgeClass }}">{{ ucfirst(str_replace('_', ' ', $sale->customer->type)) }}</span>
                </td>
                <td>{{ $sale->total }}</td>
                <td class="text-end">
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
    {{ $sales->links() }}
@endsection
