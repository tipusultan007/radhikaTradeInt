@extends('layouts.app')
@section('title','Create Balance Transfer')
@section('create-button')
    <a href="{{ route('balance_transfers.index') }}" class="btn btn-secondary">Cancel</a>
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
    <form action="{{ route('balance_transfers.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="from_account_id">From Account</label>
            <select name="from_account_id" id="from_account_id" class="form-control">
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
            <select name="to_account_id" id="to_account_id" class="form-control">
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
            <input type="date" name="transfer_date" id="transfer_date" class="form-control" value="{{ old('transfer_date') }}">
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" class="form-control">{{ old('description') }}</textarea>
        </div>

        <button type="submit" class="btn btn-success">Create Transfer</button>
        <a href="{{ route('balance_transfers.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
@endsection
