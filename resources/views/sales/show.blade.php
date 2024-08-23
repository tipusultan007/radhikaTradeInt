@extends('layouts.app')

@section('title', 'Sale Details')

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="card card-invoice">
                <div class="card-header pb-0">
                    <div class="d-flex gap-4 justify-content-start align-items-center">
                            <div class="invoice-logo">
                                <img class="img-fluid" width="150" src="{{asset('assets/img/radhikas-logo.png')}}" alt="company logo">
                            </div>
                        <div class="invoice-description">
                            <h3 class="mb-1 text-uppercase">Radhikas Trade International</h3>
                            <p class="mb-0">88/89, Sadarghat Road, Chattogram, Bangladesh 4000</p>
                            <p class="mb-0">018 9770 1188, 019 9984 8389, 017 3222 6604</p>
                        </div>
                    </div>

                </div>
                <div class="card-body">
                    <div class="separator-solid"></div>
                    <div class="row">
                        <div class="col-md-4 info-invoice">
                            <h5 class="sub">Date</h5>
                            <p>{{ $sale->date->format('M d, Y') }}</p>
                        </div>
                        <div class="col-md-4 info-invoice">
                            <h5 class="sub">Invoice ID</h5>
                            <p>#{{ $sale->id }}</p>
                        </div>
                        <div class="col-md-4 info-invoice">
                            <h5 class="sub">Invoice To</h5>
                            <p>
                                {{ $sale->customer->name }},<br>
                                {{ $sale->customer->address }} <br>
                                {{ $sale->customer->phone }} <br>
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="invoice-detail">
                                <div class="invoice-top">
                                    <h3 class="title"><strong>Order summary</strong></h3>
                                </div>
                                <div class="invoice-item">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead class="text-center">
                                            <tr>
                                                <th class="text-start">Product</th>
                                                <th>Packaging Type</th>
                                                <th>Quantity</th>
                                                <th>Price</th>
                                                <th class="text-end">Total</th>
                                            </tr>
                                            </thead>
                                            <tbody class="text-center">
                                            @foreach($sale->details as $detail)
                                                <tr>
                                                    <td class="text-start">{{ $detail->product->name }}</td>
                                                    <td>{{ $detail->packagingType->type }}</td>
                                                    <td>{{ $detail->quantity }}</td>
                                                    <td>{{ number_format($detail->price, 2) }}</td>
                                                    <td class="text-end">{{ number_format($detail->quantity * $detail->price, 2) }}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="separator-solid  mb-3"></div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-sm-7 col-md-5 mb-3 mb-md-0 transfer-to">
                            @if($sale->account_id != '')
                            <h5 class="sub">Payment VIA - {{ $sale->account->name }}</h5>
                            @if($sale->payment_details != '')
                            <div class="account-transfer">
                                <div><span>Details:</span><span>{{ $sale->payment_details }}</span></div>
                            </div>
                                @endif
                            @endif
                        </div>
                        <div class="col-sm-5 col-md-7 transfer-total">
                            <h5 class="sub">Total Amount</h5>
                            <div class="price">$685.99</div>
                            <span>Taxes Included</span>
                        </div>
                    </div>
                    <div class="separator-solid"></div>
                    <h6 class="text-uppercase mt-4 mb-3 fw-bold">
                        Notes
                    </h6>
                    <p class="text-muted mb-0">
{{ $sale->note }}
                    </p>
                </div>
            </div>
        </div>
    </div>
    {{--<div class="card">
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
    </div>--}}
@endsection
