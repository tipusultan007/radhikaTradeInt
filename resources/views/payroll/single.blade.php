@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Payroll Entries for {{ $user->name }}</h2>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table class="table">
            <thead>
            <tr>
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
                    <td>{{ $payroll->month }}</td>
                    <td>{{ $payroll->salary }}</td>
                    <td>{{ $payroll->bonus }}</td>
                    <td>{{ $payroll->deductions }}</td>
                    <td>{{ $payroll->net_pay }}</td>
                    <td>{{ $payroll->pay_date->format('Y-m-d') }}</td>
                    <td>
                        <a href="{{ route('payroll.edit', [$user->id, $payroll->id]) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('payroll.destroy', [$user->id, $payroll->id]) }}" method="POST" style="display:inline;">
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
@endsection
