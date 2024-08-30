@extends('layouts.app')
@section('title','Sales Commission')
@section('content')
    <table class="table table-striped">
        <thead>
        <tr>
            <th>Date</th>
            <th>Referrer</th>
            <th class="text-center">Sale ID</th>
            <th class="text-end">Sale Total</th>
            <th class="text-end">Commission</th>
            <th class="text-end">Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($salesCommissions as $commission)
            <tr>
                <td>{{ $commission->sale->date->format('d/m/Y') }}</td>
                <td>{{ $commission->customer->name }}</td>
                <td class="text-center">#{{ $commission->sale->id }}</td>
                <td class="text-end">{{ $commission->sale->total }}</td>
                <td class="text-end">{{ $commission->commission }}</td>
                <td class="text-end">
                    <a href="{{ route('sales.show', $commission->sale_id) }}" class="btn btn-info btn-sm">View Sale</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
