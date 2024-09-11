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
            <th class="text-end">Total Amount</th>
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
                <td class="text-end">{{ $sale->total }}</td>
                <td class="text-center">
                    @if($sale->status === 'pending')
                        <i class="fas fa-clock text-warning"></i> <!-- Clock icon for pending status -->
                        <span class="badge bg-warning">Pending</span>
                    @else
                        <i class="fas fa-check-circle text-success"></i> <!-- Check-circle icon for delivered status -->
                        <span class="badge bg-success">Delivered</span>
                    @endif
                </td>

                <td class="text-end">
                    <div class="d-flex gap-2">
                        <a href="{{ route('sales.show', $sale->id) }}" class="btn btn-icon btn-info">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('sales.edit', $sale->id) }}" class="btn btn-icon btn-warning">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('sales.destroy', $sale->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-icon" onclick="return confirm('Are you sure you want to delete this sale?');">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </div>
                </td>

            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $sales->links() }}
@endsection
