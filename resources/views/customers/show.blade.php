@extends('layouts.app')
@section('title', 'Customer Details')
@section('create-button')
    <a href="{{ route('customers.index') }}" class="btn btn-secondary">Back to List</a>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-5">
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th style="width: 150px">Name</th>
                            <td>{{ $customer->name }}</td>
                        </tr>
                        <tr>
                            <th style="width: 150px">Phone</th>
                            <td>{{ $customer->phone }}</td>
                        </tr>
                        <tr>
                            <th style="width: 150px">Address</th>
                            <td>{{ $customer->address }}</td>
                        </tr>
                        <tr>
                            <th style="width: 150px">Customer Type</th>
                            <td><span class="badge badge-{{
        $customer->type == 'dealer' ? 'primary' :
        ($customer->type == 'commission_agent' ? 'success' :
        ($customer->type == 'retailer' ? 'warning' :
        ($customer->type == 'wholesale' ? 'info' :
        ($customer->type == 'retail' ? 'danger' : 'secondary'))))
    }}">
        {{ ucfirst(str_replace('_', ' ', $customer->type)) }}
    </span></td>
                        </tr>
                            <tr>
                            <th>Balance</th> <td>{{ $customer->balance }}/=</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Payment Form</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('customer-payments.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="" class="form-label">Date</label>
                                <input type="text" name="date" value="{{ date('Y-m-d') }}" class="form-control flatpickr">
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="" class="form-label">Amount</label>
                                <input type="text" name="amount" class="form-control">
                            </div>
                            @php
                                $accounts = \App\Models\Account::where('type','asset')->whereNotIn('id',[3,4])->get();
                            @endphp
                            <div class="col-md-6 form-group">
                                <label for="" class="form-label">Account</label>
                                <select name="account_id" id="account_id" class="select2">
                                    @forelse($accounts as $account)
                                        <option value="{{ $account->id }}"> {{ $account->name }}</option>
                                    @empty
                                    @endforelse
                                </select>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="" class="form-label">Payment Detail</label>
                                <input type="text" name="payment_detail" class="form-control">
                            </div>
                            <div class="col-md-12 form-group">
                                <label for="" class="form-label">Note</label>
                                <input type="text" name="note" class="form-control">
                            </div>
                            <div class="col-md-12 form-group d-flex justify-content-end">
                                <button class="btn btn-primary" type="submit">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <table class="table table-striped table-bordered">
            <thead>
            <tr>
                <th>Date</th>
                <th>Particulars</th>
                <th class="text-end">Amount</th>
                <th class="text-end">Action</th>
            </tr>
            </thead>
            @foreach($journalEntries as $entry)
                @php
                    $isFirstLineItem = true;
                @endphp
                @foreach($entry->lineItems()->orderBy('id','desc')->get() as $item)
                    @if($item->account_id === 1 || $item->account_id === 2 || $item->account_id === 6)
                    <tr>
                        <td>{{ $entry->date->format('d/m/Y') }}</td>
                        <td class="{{ $item->credit > 0 ? 'text-end' : '' }}">{{ $item->account->name }} <br>
                        <small>{{ $entry->description??'' }}</small>
                        </td>
                        @if($item->debit > 0)
                            <td class="text-success text-end">{{ number_format($item->debit, 2)  }}</td>
                        @else
                            <td class="text-danger text-end">{{  number_format($item->credit, 2) }}</td>
                        @endif
                     <td class="text-end">
                         @if($entry->type === 'customer_payment')
                             <button class="btn btn-danger btn-sm">Delete</button>
                         @endif
                     </td>
                    </tr>
                    @endif
                @endforeach
            @endforeach
            <tr>
                <th class="text-end" colspan="2">Balance</th>
                <th class="text-end">{{ number_format($customer->balance,2) }}</th>
                <td></td>
            </tr>
        </table>
    </div>

@endsection
@section('js')
    <script>
        $(".select2").select2({
            theme: 'bootstrap',
            width: '100%'
        })
    </script>
@endsection
