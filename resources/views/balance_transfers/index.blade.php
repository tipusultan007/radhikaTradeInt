@extends('layouts.app')
@section('title','Balance Transfers')
@section('create-button')
    <a href="{{ route('balance_transfers.create') }}" class="btn btn-primary mb-3">Create New Transfer</a>
@endsection
@section('content')
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="row">
        <div class="col-lg-3">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        Balance Transfer Form
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('balance_transfers.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="from_account_id">From Account</label>
                            <select name="from_account_id" id="from_account_id" class="form-control select2">
                                <option value="">-- Select Account --</option>
                                @foreach($accounts as $account)
                                    <option value="{{ $account->id }}" {{ old('from_account_id') == $account->id ? 'selected' : '' }}>
                                        {{ $account->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="to_account_id">To Account</label>
                            <select name="to_account_id" id="to_account_id" class="form-control select2">
                                <option value="">-- Select Account --</option>
                                @foreach($accounts as $account)
                                    <option value="{{ $account->id }}" {{ old('to_account_id') == $account->id ? 'selected' : '' }}>
                                        {{ $account->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="amount">Amount</label>
                            <input type="text" name="amount" id="amount" class="form-control" value="{{ old('amount') }}">
                        </div>

                        <div class="form-group">
                            <label for="transfer_date">Transfer Date</label>
                            <input type="text" name="transfer_date" id="transfer_date" class="form-control flatpickr" value="{{ old('transfer_date',date('Y-m-d')) }}">
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" class="form-control">{{ old('description') }}</textarea>
                        </div>

                       <div class="form-group d-flex justify-content-end">
                           <button type="submit" class="btn btn-success">Create Transfer</button>
                       </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-9">
            <form method="GET" action="{{ route('balance_transfers.index') }}" class="row mb-3">
                <div class="form-group col-md-3">
                    <label for="account_id" class="mr-2">Account</label>
                    <select name="account_id" id="account_id" class="form-control select2">
                        <option value="">-- All Accounts --</option>
                        @foreach($accounts as $account)
                            <option value="{{ $account->id }}" {{ request('account_id') == $account->id ? 'selected' : '' }}>
                                {{ $account->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-md-3">
                    <label for="start_date" class="mr-2">Start Date</label>
                    <input type="text" name="start_date" id="start_date" class="form-control flatpickr" value="{{ request('start_date', date('Y-m-d')) }}">
                </div>

                <div class="form-group col-md-3">
                    <label for="end_date" class="mr-2">End Date</label>
                    <input type="text" name="end_date" id="end_date" class="form-control flatpickr" value="{{ request('end_date', date('Y-m-d')) }}">
                </div>

               <div class="col-md-3 form-group d-flex align-items-end gap-2">
                   <button type="submit" class="btn btn-primary">Filter</button>
                   <a href="{{ route('balance_transfers.index') }}" class="btn btn-secondary ml-2">Clear Filters</a>
               </div>
            </form>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th class="text-center">From Account</th>
                        <th class="text-center">To Account</th>
                        <th class="text-center">Amount</th>
                        <th class="text-center">Date</th>
                        <th class="text-center">Description</th>
                        <th class="text-center">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($balanceTransfers as $transfer)
                        <tr>
                            <td>{{ $transfer->fromAccount->name }}</td>
                            <td>{{ $transfer->toAccount->name }}</td>
                            <td>{{ number_format($transfer->amount, 2) }}</td>
                            <td>{{ $transfer->transfer_date->format('d/m/Y') }}</td>
                            <td>{{ $transfer->description }}</td>
                            <td class="text-end">
                                <a href="{{ route('balance_transfers.edit', $transfer->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('balance_transfers.destroy', $transfer->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this transfer?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {{ $balanceTransfers->links() }}
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script>
        $(".select2").select2({
            theme: 'bootstrap',
            width: '100%',
            allowClear: true
        })
    </script>
@endsection
