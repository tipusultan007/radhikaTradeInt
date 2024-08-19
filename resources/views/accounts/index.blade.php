@extends('layouts.app')
@section('title','Accounts')
@section('create-button')
    <a href="{{ route('accounts.create') }}" class="btn btn-primary">New Account</a>
@endsection
@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Parent ID</th>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Code</th>
                    <th>Opening Balance</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($accounts as $account)
                    <tr>
                        <td>{{ $account->id }}</td>
                        <td>{{ $account->parent_id }}</td>
                        <td>{{ $account->name }}</td>
                        <td>{{ ucfirst($account->type) }}</td>
                        <td>{{ $account->code }}</td>
                        <td>{{ number_format($account->opening_balance, 2) }}</td>
                        <td>
                            <a href="{{ route('accounts.edit', $account->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('accounts.destroy', $account->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
