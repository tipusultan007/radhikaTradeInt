@extends('layouts.app')

@section('title', 'Sale Details')

@section('content')
    <div class="card">
        <div class="card-header mb-0">
            <div class="d-flex justify-content-between">
                <h4 class="card-title mb-0">Sale Details</h4>
                <a href="{{ route('sales.index') }}" class="btn btn-secondary">Back to Sales</a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5>Customer Information</h5>
                    <p><strong>Name:</strong> {{ $sale->customer->name }}</p>
                    <p><strong>Address:</strong> {{ $sale->customer->address }}</p>
                    <p><strong>Phone:</strong> {{ $sale->customer->phone }}</p>
                </div>
                <div class="col-md-6">
                    <h5>Sale Information</h5>
                    <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($sale->date)->format('d/m/Y') }}</p>
                    <p><strong>Subtotal:</strong> {{ number_format($sale->subtotal, 2) }}</p>
                    <p><strong>Customer Delivery Cost:</strong> {{ number_format($sale->customer_delivery_cost, 2) }}</p>
                    <p><strong>Owner Delivery Cost:</strong> {{ number_format($sale->owner_delivery_cost, 2) }}</p>
                    <p><strong>Discount:</strong> {{ number_format($sale->discount, 2) }}</p>
                    <p><strong>Total:</strong> {{ number_format($sale->total, 2) }}</p>
                    <p><strong>Paid Amount:</strong> {{ number_format($sale->paid_amount, 2) }}</p>
                    <p><strong>Note:</strong> {{ $sale->note }}</p>
                </div>
            </div>

            <h5 class="mt-4">Sale Details</h5>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Product</th>
                    <th>Packaging Type</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
                </thead>
                <tbody>
                @foreach($sale->details as $detail)
                    <tr>
                        <td>{{ $detail->product->name }}</td>
                        <td>{{ $detail->packagingType->type }}</td>
                        <td>{{ $detail->quantity }}</td>
                        <td>{{ number_format($detail->price, 2) }}</td>
                        <td>{{ number_format($detail->quantity * $detail->price, 2) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <h5 class="mt-4">Journal Entry</h5>
            @if($sale->journalEntry)
                <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($sale->journalEntry->date)->format('d/m/Y') }}</p>
                <p><strong>Description:</strong> {{ $sale->journalEntry->description }}</p>
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Account</th>
                        <th>Debit</th>
                        <th>Credit</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($sale->journalEntry->lineItems as $lineItem)
                        <tr>
                            <td>{{ $lineItem->account->name }}</td>
                            <td>{{ number_format($lineItem->debit, 2) }}</td>
                            <td>{{ number_format($lineItem->credit, 2) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                <p>No journal entry found for this sale.</p>
            @endif
        </div>
    </div>
@endsection
