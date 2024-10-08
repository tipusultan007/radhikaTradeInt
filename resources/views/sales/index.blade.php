@extends('layouts.app')
@section('title','Sales')
@section('create-button')
    <a href="{{ route('sales.create') }}" class="btn btn-primary">Create New Sale</a>
    <a href="{{ request()->fullUrlWithQuery(['export' => 'excel']) }}" class="btn btn-success">
        <i class="fas fa-file-excel"></i> Download Excel
    </a>
    <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" class="btn btn-danger">
        <i class="fas fa-file-pdf"></i> Download PDF
    </a>

@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <form method="GET" class="row" action="{{ route('sales.index') }}">
                <!-- Customer Field -->
                <div class="form-group col-md-3">
                    <label for="customer_id">Customer:</label>
                    <select name="customer_id" class="form-control select2">
                        <option value="">--Select Customer--</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                                {{ $customer->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Invoice No Field -->
                <div class="form-group col-md-2">
                    <label for="invoice_no">Invoice No:</label>
                    <input type="text" name="invoice_no" class="form-control" value="{{ request('invoice_no') }}">
                </div>

                <!-- Start Date Field -->
                <div class="form-group col-md-2">
                    <label for="start_date">Start Date:</label>
                    <input type="text" name="start_date" class="form-control flatpickr" value="{{ request('start_date') }}">
                </div>

                <!-- End Date Field -->
                <div class="form-group col-md-2">
                    <label for="end_date">End Date:</label>
                    <input type="text" name="end_date" class="form-control flatpickr" value="{{ request('end_date') }}">
                </div>

                <div class="form-group col-md-3">
                    <label for="created_by">Created By:</label>
                    <select name="created_by" class="form-control select2">
                        <option value="">--Select User--</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('created_by') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-md-3">
                    <label for="status">Status:</label>
                    <select name="status" class="form-control select2">
                        <option value="">--Select Status--</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="dispatched" {{ request('status') == 'dispatched' ? 'selected' : '' }}>Dispatched</option>
                        <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                    </select>
                </div>

                <!-- Filter Button -->
                <div class="form-group col-md-3 align-self-end">
                    <button type="submit" class="btn btn-primary">Search</button>
                    <a class="btn btn-danger" href="{{ route('sales.index') }}">Reset</a>
                </div>
            </form>
            @if(request()->hasAny(['customer_id', 'invoice_no', 'start_date', 'end_date', 'created_by', 'status']))
                <div class="alert alert-info">
                    <strong>Search Results For:</strong>
                    <ul>
                        @if(request('customer_id'))
                            <li><strong>Customer:</strong> {{ $customers->find(request('customer_id'))->name }}</li>
                        @endif
                        @if(request('invoice_no'))
                            <li><strong>Invoice No:</strong> {{ request('invoice_no') }}</li>
                        @endif
                        @if(request('start_date'))
                            <li><strong>Start Date:</strong> {{ request('start_date') }}</li>
                        @endif
                        @if(request('end_date'))
                            <li><strong>End Date:</strong> {{ request('end_date') }}</li>
                        @endif
                        @if(request('created_by'))
                            <li><strong>Created By:</strong> {{ $users->find(request('created_by'))->name }}</li>
                        @endif
                        @if(request('status'))
                            <li><strong>Status:</strong> {{ ucfirst(request('status')) }}</li>
                        @endif
                    </ul>
                </div>
            @endif

        </div>
    </div>


    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead>
            <tr>
                <th>Date</th>
                <th>Inv</th>
                <th>Customer</th>
                <th>Type</th>
                <th>Discount</th>
                <th class="text-end">Total</th>
                <th>Created</th>
                <th>Status</th>
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
                    <td>{{ $sale->discount??'0' }}</td>
                    <td class="text-end">{{ $sale->total }}</td>
                    <td>{{ $sale->creator->name??'-' }}</td>
                    <td class="text-center">
                        @if($sale->status === 'pending')
                            <i class="fas fa-clock text-warning"></i> <!-- Clock icon for pending status -->
                            <span class="badge bg-warning">Pending</span>
                        @elseif($sale->status === 'dispatched')
                            <i class="fas fa-box text-info"></i> <!-- Clock icon for pending status -->
                            <span class="badge bg-info">Dispatched</span>
                        @else
                            <i class="fas fa-check-circle text-success"></i> <!-- Check-circle icon for delivered status -->
                            <span class="badge bg-success">Delivered</span>
                        @endif
                    </td>

                    <td class="text-end">
                        <div class="d-flex gap-2">
                            <a href="{{ route('invoice.pdf', $sale->id) }}" class="btn btn-sm btn-icon btn-danger">
                                <i class="fas fa-file-pdf"></i>
                            </a>
                            <a href="{{ route('sales.show', $sale->id) }}" class="btn btn-sm btn-icon btn-info">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('sales.edit', $sale->id) }}" class="btn btn-sm btn-icon btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('sales.destroy', $sale->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger btn-icon" onclick="return confirm('Are you sure you want to delete this sale?');">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </td>

                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $sales->withQueryString()->links() }}
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
