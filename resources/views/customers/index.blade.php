@extends('layouts.app')

@section('title', 'Customers')
@section('create-button')
    <a href="{{ route('customers.create') }}" class="btn btn-primary btn-round">Add Customer</a>
@endsection

@section('content')
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Type</th>
                        <th>Balance</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($customers as $customer)
                        <tr>
                            <td>{{ $customer->name }}</td>
                            <td>{{ $customer->phone }}</td>
                            <td>{{ $customer->address }}</td>
                            <td>
                                @php
                                    $badgeClass = '';
                                    switch($customer->type) {
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
                                <span class="badge {{ $badgeClass }}">{{ ucfirst(str_replace('_', ' ', $customer->type)) }}</span>
                            </td>
                            <td>{{ $customer->balance }}</td>
                            <td>
                                <a href="{{ route('customers.show', $customer->id) }}" class="btn btn-primary btn-icon btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-warning btn-icon btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-icon btn-sm" onclick="return confirm('Are you sure?')">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
