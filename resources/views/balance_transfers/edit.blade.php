@extends('layouts.app')

@section('content')
    <h1>Edit Balance Transfer</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('balance_transfers.update', $balanceTransfer->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="from_account_id">From Account</label>
                    <select name="from_account_id" id="from_account_id" class="form-control select2">
                        <option value="">-- Select Account --</option>
                        @foreach($accounts as $account)
                            <option value="{{ $account->id }}" {{ $balanceTransfer->from_account_id == $account->id ? 'selected' : '' }}>
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
                            <option value="{{ $account->id }}" {{ $balanceTransfer->to_account_id == $account->id ? 'selected' : '' }}>
                                {{ $account->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="amount">Amount</label>
                    <input type="text" name="amount" id="amount" class="form-control" value="{{ $balanceTransfer->amount }}">
                </div>

                <div class="form-group">
                    <label for="transfer_date">Transfer Date</label>
                    <input type="text" name="transfer_date" id="transfer_date" class="form-control flatpickr" value="{{ $balanceTransfer->transfer_date->format('Y-m-d') }}">
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" class="form-control">{{ $balanceTransfer->description }}</textarea>
                </div>

                <button type="submit" class="btn btn-success">Update Transfer</button>
                <a href="{{ route('balance_transfers.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $(".select2").select2({
            theme: 'bootstrap',
            width: '100%',
        })
    </script>
@endsection
