@extends('layouts.app')
@section('title','Edit Investment Withdrawal')
@section('create-button')
    <a href="{{ route('investment_withdraws.index') }}" class="btn btn-primary">Back to List</a>
@endsection
@section('content')

    <form action="{{ route('investment_withdraws.update', $investmentWithdraw->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="investment_id">Investment ID</label>
            <input type="text" name="investment_id" id="investment_id" class="form-control" value="{{ $investmentWithdraw->investment_id }}" required>
        </div>

        <div class="form-group">
            <label for="account_id">Account ID</label>
            <input type="text" name="account_id" id="account_id" class="form-control" value="{{ $investmentWithdraw->account_id }}" required>
        </div>

        <div class="form-group">
            <label for="amount">Amount</label>
            <input type="number" name="amount" id="amount" class="form-control" step="0.01" value="{{ $investmentWithdraw->amount }}" required>
        </div>

        <div class="form-group">
            <label for="date">Date</label>
            <input type="date" name="date" id="date" class="form-control" value="{{ $investmentWithdraw->date->format('Y-m-d') }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Update Withdrawal</button>
    </form>
@endsection
