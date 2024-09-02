@extends('layouts.app')
@section('title','Commission Withdraw List')
@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        Commission Withdraw
                    </h4>
                </div>
                @php
                    $agents = \App\Models\Customer::where('type','commission_agent')->get();
                    $accounts = \App\Models\Account::where('type','asset')->whereNotIn('id',[3,4])->get();
                @endphp
                <div class="card-body">
                    <form action="{{ route('commission-withdraw.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="" class="form-label">Agent</label>
                            <select name="customer_id" id="customer_id" class="select2">
                                @forelse($agents as $agent)
                                    <option value="{{ $agent->id }}">{{ $agent->name }} - {{ $agent->commission }}/=</option>
                                @empty
                                @endforelse
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="" class="form-label">Amount</label>
                            <input type="text" class="form-control" name="amount">
                        </div>
                        <div class="form-group">
                            <label for="" class="form-label">Date</label>
                            <input type="text" name="date" class="form-control flatpickr" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="form-group">
                            <label for="" class="form-label">Account</label>
                            <select name="account_id" id="account_id" class="select2">
                                <option value=""></option>
                                @forelse($accounts as $account)
                                    <option value="{{ $account->id }}">{{ $account->name }}</option>
                                @empty
                                    <option value="" disabled>No payment method found!</option>
                                @endforelse
                            </select>
                        </div>
                        <div class="form-group d-flex justify-content-end">
                            <button class="btn btn-primary" type="submit">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Date</th>
                    <th>Agent</th>
                    <th class="text-center">Commission</th>
                    <th class="text-end">Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($commissionWithdraws as $commission)
                    <tr>
                        <td>{{ $commission->date->format('d/m/Y') }}</td>
                        <td>{{ $commission->customer->name }}</td>
                        <td class="text-center">{{ $commission->amount }}</td>
                        <td class="text-end">
                            <a href="{{ route('commission-withdraw.edit', $commission->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('commission-withdraw.destroy', $commission->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this transfer?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection
@section('js')
    <script>
        $(".select2").select2({
            theme: 'bootstrap',
            width: '100%',
            placeholder: '-- Select --'
        })
    </script>
@endsection
