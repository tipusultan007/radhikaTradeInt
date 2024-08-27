@extends('layouts.app')
@section('title','Edit Advance Salary')
@section('create-button')
    <a href="{{ route('advance_salary.index') }}" class="btn btn-primary">Back to List</a>
@endsection
@section('content')
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    <div class="card">
        <form action="{{ route('advance_salary.update', $advanceSalary->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="user_id">User</label>
                <select name="user_id" id="user_id" class="form-control" required>
                    <option value="" disabled selected>Select a user</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ $advanceSalary->user_id == $user->id?'selected':'' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="taken_on">Date</label>
                <input type="text" name="taken_on" id="taken_on" class="form-control flatpickr" value="{{ $advanceSalary->taken_on }}" required>
            </div>
            <div class="form-group">
                <label for="amount">Amount</label>
                <input type="number" name="amount" id="amount" class="form-control" value="{{ $advanceSalary->amount }}" required>
            </div>

            <div class="form-group">
                <label for="month">Month</label>
                <input type="month" name="month" class="form-control" value="{{ \Carbon\Carbon::parse($advanceSalary->month)->format('Y-m') }}">
            </div>

            <div class="form-group">
                <label for="account_id">Account</label>
                <select name="account_id" id="account_id" class="form-control" required>
                    <option value="" disabled selected>Select an account</option>
                    @foreach($accounts as $account)
                        <option value="{{ $account->id }}" {{ $advanceSalary->account_id == $account->id?'selected':'' }}>{{ $account->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Create</button>
            </div>
        </form>
    </div>
@endsection

@section('js')
    <script>
        $("select").select2({
            theme: 'bootstrap',
            width: '100%'
        })
    </script>
@endsection
