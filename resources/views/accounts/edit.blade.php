@extends('layouts.app')
@section('title','Edit Account')
@section('create-button')
    <a href="{{ route('accounts.index') }}" class="btn btn-secondary"><i class="fas fa-reply"></i> Back to Accounts</a>
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
            <form action="{{ route('accounts.update', $account->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-4 form-group">
                        <label for="parent_id">Parent ID (Optional)</label>
                        <select name="parent_id" id="parent_id" class="select2">
                            <option value=""></option>
                            @forelse($parentAccounts as $parentAccount)
                                <option value="{{ $parentAccount->id }}" {{ $parentAccount->id == $account->parent_id?'selected':'' }}>{{ $parentAccount->name }}</option>
                            @empty
                            @endforelse
                        </select>
                    </div>

                    <div class="col-md-4 form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" class="form-control" value="{{ $account->name }}" required>
                    </div>

                    <div class="col-md-4 form-group">
                        <label for="type">Type</label>
                        <select name="type" class="select2" required>
                            <option value="asset" {{ $account->type == 'asset' ? 'selected' : '' }}>Asset</option>
                            <option value="liability" {{ $account->type == 'liability' ? 'selected' : '' }}>Liability</option>
                            <option value="equity" {{ $account->type == 'equity' ? 'selected' : '' }}>Equity</option>
                            <option value="revenue" {{ $account->type == 'revenue' ? 'selected' : '' }}>Revenue</option>
                            <option value="expense" {{ $account->type == 'expense' ? 'selected' : '' }}>Expense</option>
                        </select>
                    </div>

                    <div class="col-md-4 form-group">
                        <label for="code">Code</label>
                        <input type="text" name="code" class="form-control" value="{{ $account->code }}" required>
                    </div>

                    <div class="col-md-4 form-group">
                        <label for="opening_balance">Opening Balance</label>
                        <input type="number" step="0.01" name="opening_balance" class="form-control" value="{{ $account->opening_balance }}">
                    </div>

                    <div class="col-md-4 form-group">
                        <label for="opening_balance_date">Opening Balance Date</label>
                        <input type="text" name="opening_balance_date" class="form-control flatpickr" value="{{ $account->opening_balance_date??'' }}">
                    </div>
                    <div class="col-md-4 form-group">
                        <button type="submit" class="btn btn-success">Update Account</button>
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
