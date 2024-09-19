<style>
    .bank-information table, .bank-information td, .bank-information th {
        border: 1px solid #ccc;
        padding: 2px 5px;
    }
    table.bank-information {
        width: 85%;
    }
    .bank-information table {
        border-collapse: collapse;
    }
</style>
<div class="div">
    <div class="d-flex gap-4 justify-content-start align-items-center">
        <div class="invoice-logo">
            <img class="img-fluid" width="150" src="{{asset('assets/img/radhikas-logo.png')}}"
                 alt="company logo">
        </div>
        <div class="invoice-description">
            <h3 class="mb-1 text-uppercase">Radhikas Trade International</h3>
            <p class="mb-0">88/89, Sadarghat Road, Chattogram, Bangladesh 4000</p>
            <p class="mb-0">018 9770 1188, 019 9984 8389, 017 3222 6604</p>
        </div>
    </div>
</div>
<hr>
<div class="row">
    <div class="col-md-4 info-invoice">
        <h5 class="sub">Date</h5>
        <p>{{ $sale->date->format('M d, Y') }}</p>
    </div>
    <div class="col-md-4 info-invoice">
        <h5 class="sub">Invoice ID</h5>
        <p>#{{ $sale->invoice_no }}</p>
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
<div class="row">
    <div class="col-sm-7 col-md-7 mb-3 mb-md-0 transfer-to">
        <h6><strong>Bkash: 01852-173672 </strong><small>(Personal)</small></h6>
        <table class="bank-information">
            <thead>
            <tr>
                <th colspan="2" class="text-center">Company Bank Information</th>
            </tr>
            </thead>
            <tr>
                <th>Bank Name</th>
                <td>Southeast Bank PLC</td>
            </tr>
            <tr>
                <th>Account Name</th>
                <td>Radhikas Trade International</td>
            </tr>
            <tr>
                <th>Account No</th>
                <td>000311100027215</td>
            </tr>
            <tr>
                <th>Branch Name</th>
                <td>Agrabad, Chattogram, Bangladesh</td>
            </tr>
            <tr>
                <th>Swift Code</th>
                <td>SEBDBDDHAGR</td>
            </tr>
            <tr>
                <th>Account Type</th>
                <td>CD</td>
            </tr>
        </table>
        @if($sale->account_id != '')
            <h5 class="sub mt-3">Paid VIA - {{ $sale->account->name }}</h5>
            @if($sale->payment_details != '')
                <div class="account-transfer">
                    <div><span>Details:</span><span>{{ $sale->payment_details }}</span></div>
                </div>
            @endif
        @endif
    </div>
    <div class="col-sm-5 col-md-5 transfer-total text-end">
        <h6 class="sub">Total Amount</h6>
        <h4 class="price">৳{{ number_format($sale->total,0) }}</h4>
    </div>
</div>
<div class="separator-solid"></div>
@if($sale->note !='')
    <h6 class="text-uppercase mt-4 mb-0 fw-bold">
        Notes
    </h6>
    <p class="text-muted mb-0">
        {{ $sale->note }}
    </p>
@endif

@if($sale->created_by != '')
    <p>Sold By: <strong>{{ $sale->creator->name }}</strong></p>
@endif
<div class="text-center no-print mt-3">
    <a href="{{ route('invoice.pdf', $sale->id) }}" class="btn btn-danger">
        <i class="fas fa-file-pdf"></i> Download PDF
    </a>
</div>
