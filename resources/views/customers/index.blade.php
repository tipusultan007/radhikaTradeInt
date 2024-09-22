@extends('layouts.app')

@section('title', 'Customers')
@section('create-button')
    <a href="{{ route('customers.create') }}" class="btn btn-primary btn-round">Add Customer</a>
    <a href="{{ request()->fullUrlWithQuery(['export' => 'excel']) }}" class="btn btn-success">
        <i class="fas fa-file-excel"></i> Download Excel
    </a>
    <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" class="btn btn-danger">
        <i class="fas fa-file-pdf"></i> Download PDF
    </a>

@endsection

@section('content')
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form class="row" method="GET" action="{{ route('customers.index') }}">
                <div class="col-md-3 form-group">
                    <label for="name">Name:</label>
                    <input type="text" class="form-control" name="name" value="{{ request('name') }}">
                </div>
                <div class="col-md-3 form-group">
                    <label for="phone">Phone:</label>
                    <input type="text" class="form-control" name="phone" value="{{ request('phone') }}">
                </div>

                <div class="col-md-3 form-group">
                    <label for="type">Type:</label>
                    <select class="form-control select2" name="type">
                        <option value="">--Select Type--</option>
                        <option value="dealer" {{ request('type') == 'dealer' ? 'selected' : '' }}>Dealer</option>
                        <option value="commission_agent" {{ request('type') == 'commission_agent' ? 'selected' : '' }}>Commission Agent</option>
                        <option value="retailer" {{ request('type') == 'retailer' ? 'selected' : '' }}>Retailer</option>
                        <option value="wholesale" {{ request('type') == 'wholesale' ? 'selected' : '' }}>Wholesale</option>
                        <option value="retail" {{ request('type') == 'retail' ? 'selected' : '' }}>Retail</option>
                        <option value="customer" {{ request('type') == 'customer' ? 'selected' : '' }}>Customer</option>
                    </select>
                </div>

                <div class="col-md-3 form-group d-flex align-items-end gap-2">
                    <button class="btn btn-primary" type="submit">Search</button>
                    <a class="btn btn-danger" href="{{ route('customers.index') }}">Reset</a>
                </div>
            </form>
            @if(request()->hasAny(['name', 'phone', 'type']))
                <div class="alert alert-info">
                    <strong>Search Results For:</strong>
                    <ul>
                        @if(request('name'))
                            <li><strong>Name:</strong> {{ request('name') }}</li>
                        @endif
                        @if(request('phone'))
                            <li><strong>Phone:</strong> {{ request('phone') }}</li>
                        @endif
                        @if(request('type'))
                            <li><strong>Type:</strong> {{ ucfirst(str_replace('_', ' ', request('type'))) }}</li>
                        @endif
                    </ul>
                </div>
            @endif

        </div>
    </div>
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
                        <th>Commission</th>
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
                            <td>{{ $customer->commission }}</td>
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
                {{ $customers->withQueryString()->links() }}
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(".select2").select2({
            theme: "bootstrap",
            width: '100%',
            placeholder: '-- Select Type --',
            allowClear: true
        })
    </script>
@endsection
