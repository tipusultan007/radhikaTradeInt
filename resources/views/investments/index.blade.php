@extends('layouts.app')
@section('title','Investments')
@section('create-button')
    <a href="{{ route('investments.create') }}" class="btn btn-primary">Create New Investment</a>
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
            <th>Account</th>
            <th>Amount</th>
            <th>Description</th>
            <th>Date</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($investments as $investment)
            <tr>
                <td>{{ $investment->account->name }}</td>
                <td>${{ number_format($investment->amount, 2) }}</td>
                <td>{{ $investment->description }}</td>
                <td>{{ $investment->date->format('Y-m-d') }}</td>
                <td>
                    <a href="{{ route('investments.edit', $investment->id) }}" class="btn btn-warning">Edit</a>
                    <form action="{{ route('investments.destroy', $investment->id) }}" method="POST" style="display:inline;">
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
