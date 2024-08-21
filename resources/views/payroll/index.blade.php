@extends('layouts.app')
@section('title','All Payroll Entries')
@section('create-button')
    <a href="{{ route('payroll.create') }}" class="btn btn-primary">New Payroll</a>
@endsection
@section('content')
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table table-striped">
        <thead>
        <tr>
            <th>User Name</th>
            <th>Month</th>
            <th>Salary</th>
            <th>Bonus</th>
            <th>Deductions</th>
            <th>Net Pay</th>
            <th>Pay Date</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($payrolls as $payroll)
            <tr>
                <td class="text-left">{{ $payroll->user->name }}</td>
                <td class="text-left">{{ \Carbon\Carbon::parse($payroll->month)->format('M-Y') }}</td>
                <td class="text-left">{{ $payroll->salary }}</td>
                <td class="text-left">{{ $payroll->bonus }}</td>
                <td class="text-left">{{ $payroll->deductions }}</td>
                <td class="text-left">{{ $payroll->net_pay }}</td>
                <td class="text-left">{{ \Carbon\Carbon::parse($payroll->pay_date)->format('d/m/Y') }}</td>
                <td class="text-right">
                    <a href="{{ route('payroll.edit', [$payroll->id]) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('payroll.destroy', [$payroll->id]) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
