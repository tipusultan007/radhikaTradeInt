@extends('layouts.app')
@section('title','Investment Withdrawals')
@section('create-button')
    <a href="{{ route('investment_withdraws.create') }}" class="btn btn-primary">Create New Withdrawal</a>
@endsection
@section('content')

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Investment ID</th>
            <th>Account ID</th>
            <th>Amount</th>
            <th>Date</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($withdrawals as $withdraw)
            <tr>
                <td>{{ $withdraw->id }}</td>
                <td>{{ $withdraw->investment_id }}</td>
                <td>{{ $withdraw->account_id }}</td>
                <td>${{ number_format($withdraw->amount, 2) }}</td>
                <td>{{ $withdraw->date->format('Y-m-d') }}</td>
                <td>
                    <a href="{{ route('investment_withdraws.edit', $withdraw->id) }}" class="btn btn-warning">Edit</a>
                    <form action="{{ route('investment_withdraws.destroy', $withdraw->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
