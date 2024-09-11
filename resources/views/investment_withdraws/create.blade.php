@extends('layouts.app')

@section('content')
    <h1>Create Investment Withdrawal</h1>

    <form action="{{ route('investment_withdraws.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="investment_id">Investment</label>
            <select name="investment_id" id="investment_id" class="form-control select2" required>
                <option value="" disabled selected>Select an Investment</option>
                @foreach ($investments as $investment)
                    <option value="{{ $investment->id }}" {{ old('investment_id') == $investment->id ? 'selected' : '' }}>
                        {{ $investment->id }} - ৳{{ number_format($investment->amount, 2) }} - {{ $investment->date->format('d/m/Y') }}
                    </option>
                @endforeach
            </select>
            @error('investment_id')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="account_id">Withdrawal Account</label>
            <select name="account_id" id="account_id" class="form-control select2" required>
                <option value="" disabled selected>Select an Account</option>
                @foreach ($accounts as $account)
                    <option value="{{ $account->id }}" {{ old('account_id') == $account->id ? 'selected' : '' }}>
                        {{ $account->name }}
                    </option>
                @endforeach
            </select>
            @error('account_id')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="amount">Amount</label>
            <input type="number" name="amount" id="amount" class="form-control" step="0.01" value="{{ old('amount') }}" required>
            @error('amount')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="date">Date</label>
            <input type="text" name="date" id="date" class="form-control flatpickr" value="{{ old('date',date('Y-m-d')) }}" required>
            @error('date')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary">Create Withdrawal</button>
        </div>
    </form>
@endsection
@section('js')
    <script>
        $(".select2").select2({
            theme: 'bootstrap',
            width: '100%'
        })
    </script>
@endsection
