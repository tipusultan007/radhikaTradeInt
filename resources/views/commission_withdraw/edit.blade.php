@extends('layouts.app')
@section('title','Commission Withdraw Update')
@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                @php
                    $agents = \App\Models\Customer::where('type','commission_agent')->get();
                    $accounts = \App\Models\Account::where('type','asset')->whereNotIn('id',[3,4])->get();
                @endphp
                <div class="card-body">
                    <form action="{{ route('commission-withdraw.update',$commissionWithdraw->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="" class="form-label">Agent</label>
                            <select name="customer_id" id="customer_id" class="select2">
                                @forelse($agents as $agent)
                                    <option value="{{ $agent->id }}" {{ $agent->id == $commissionWithdraw->customer_id?'selected':'' }}>{{ $agent->name }} - {{ $agent->commission }}/=</option>
                                @empty
                                @endforelse
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="" class="form-label">Amount</label>
                            <input type="text" class="form-control" name="amount" value="{{ $commissionWithdraw->amount }}">
                        </div>
                        <div class="form-group">
                            <label for="" class="form-label">Date</label>
                            <input type="text" name="date" class="form-control flatpickr" value="{{ $commissionWithdraw->date }}">
                        </div>
                        <div class="form-group">
                            <label for="" class="form-label">Account</label>
                            <select name="account_id" id="account_id" class="select2">
                                <option value=""></option>
                                @forelse($accounts as $account)
                                    <option value="{{ $account->id }}" {{ $commissionWithdraw->account_id==$account->id?'selected':'' }}>{{ $account->name }}</option>
                                @empty
                                    <option value="" disabled>No payment method found!</option>
                                @endforelse
                            </select>
                        </div>
                        <div class="form-group d-flex justify-content-end">
                            <button class="btn btn-primary" type="submit">Update</button>
                        </div>
                    </form>
                </div>
            </div>
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
