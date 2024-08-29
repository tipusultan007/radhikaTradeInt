@extends('layouts.app')
@section('title','Account Details')
@section('create-button')
    <a href="{{ route('accounts.index') }}" class="btn btn-secondary">Back to Accounts</a>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-4">
            <!-- Account Details and Totals Table -->
            <table class="table table-bordered">
                <thead class="thead-light">
                <tr>
                    <th colspan="2" class="text-center"> Account: <span class="text-dark">{{ $account->name }}</span></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <th>Type</th>
                    <td class="text-end">{{ ucfirst($account->type) }}</td>
                </tr>
                <tr>
                    <th>Code</th>
                    <td class="text-end">{{ $account->code }}</td>
                </tr>
                <tr>
                    <th>Opening Balance</th>
                    <td class="text-end">{{ number_format($account->opening_balance, 2) }}</td>
                </tr>
                <tr>
                    <th>Opening Balance Date</th>
                    <td class="text-end">{{ $account->opening_balance_date ? $account->opening_balance_date->format('d-m-Y') : 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Total Debit</th>
                    <td class="text-end">{{ number_format($totalDebit, 2) }}</td>
                </tr>
                <tr>
                    <th>Total Credit</th>
                    <td class="text-end">{{ number_format($totalCredit, 2) }} </td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="col-md-8">
            <!-- Date Range Filter Form -->
            <form method="GET" action="{{ route('accounts.show', $account->id) }}" class="mb-4">
                <div class="row">
                    <div class="col-md-4">
                        <input type="text" name="start_date" class="form-control flatpickr" value="{{ request('start_date') }}" placeholder="Start Date">
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="end_date" class="form-control flatpickr" value="{{ request('end_date') }}" placeholder="End Date">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary btn-block">Filter</button>
                        <a href="{{ route('accounts.show',$account->id) }}" class="btn btn-warning btn-block">Reset</a>
                    </div>
                </div>
            </form>
            @if ($journalEntryLineItems->isEmpty())
                <p>No journal entries found for this account.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th colspan="5">Journal Entries</th>
                        </tr>
                        <tr>
                            <th class="text-center">Date</th>
                            <th class="text-center">Type</th>
                            <th class="text-center">Description</th>
                            <th class="text-center">Debit</th>
                            <th class="text-center">Credit</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($journalEntryLineItems as $lineItem)
                            <tr>
                                <td>{{ $lineItem->journalEntry->date->format('d/m/Y') }}</td>
                                <td>{{ ucfirst($lineItem->journalEntry->type) }}</td>
                                <td>{{ $lineItem->journalEntry->description }}</td>
                                <td class="text-end">{{ number_format($lineItem->debit, 2) }}</td>
                                <td class="text-end">{{ number_format($lineItem->credit, 2) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <!-- Pagination Links -->
                    {{ $journalEntryLineItems->links() }}
                </div>

            @endif
        </div>
    </div>
@endsection
