@extends('layouts.app')
@section('title','Create Account')
@section('create-button')
    <a href="{{ route('accounts.index') }}" class="btn btn-secondary">
        <i class="fas fa-reply"></i>
        Back to Accounts</a>
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
    <div class="card">
        <div class="card-body">
            <form action="{{ route('accounts.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-4 form-group">
                        <label for="parent_id">Parent ID (Optional)</label>
                        <select name="parent_id" id="parent_id" class="select2">
                            <option value=""></option>
                            @forelse($accounts as $account)
                                <option value="{{ $account->id }}">{{ $account->name }}</option>
                            @empty
                            @endforelse
                        </select>
                    </div>

                    <div class="col-md-4 form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                    </div>

                    <div class="col-md-4 form-group">
                        <label for="type">Type</label>
                        <select name="type" class="select2" required>
                            <option value="asset" {{ old('type') == 'asset' ? 'selected' : '' }}>Asset</option>
                            <option value="liability" {{ old('type') == 'liability' ? 'selected' : '' }}>Liability</option>
                            <option value="equity" {{ old('type') == 'equity' ? 'selected' : '' }}>Equity</option>
                            <option value="revenue" {{ old('type') == 'revenue' ? 'selected' : '' }}>Revenue</option>
                            <option value="expense" {{ old('type') == 'expense' ? 'selected' : '' }}>Expense</option>
                        </select>
                    </div>

                    <div class="col-md-4 form-group">
                        <label for="code">Code</label>
                        <input type="text" name="code" class="form-control" value="{{ old('code') }}" required>
                    </div>

                    <div class="col-md-4 form-group">
                        <label for="opening_balance">Opening Balance</label>
                        <input type="number" step="0.01" name="opening_balance" class="form-control" value="{{ old('opening_balance') }}">
                    </div>

                    <div class="col-md-4 form-group">
                        <label for="opening_balance_date">Opening Balance Date</label>
                        <input type="text" name="opening_balance_date" class="form-control flatpickr" value="{{ old('opening_balance_date') }}">
                    </div>

                    <div class="col-md-4 form-group">
                        <button type="submit" class="btn btn-success">Create Account</button>
                    </div>
                </div>


            </form>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $(".select2").select2({
            theme: 'bootstrap',
            width: '100%',
            placeholder: '-- Select Parent Account --',
            allowClear: true
        })
    </script>
@endsection
