<!DOCTYPE html>
<html>
<head>
    <title>Invoice</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .table { width: 100%; border-collapse: collapse; }
        .table-bordered { border: 1px solid #ddd; }
        .table-bordered th, .table-bordered td { border: 1px solid #ddd; padding: 8px; text-align: left}
        .table-striped tbody tr:nth-of-type(odd) { background-color: #f9f9f9; }
        .text-end { text-align: right; }
        .text-start { text-align: left; }
        .text-center { text-align: center; }
        .badge { padding: 0.5em 1em; border-radius: 0.25em; color: #fff; }
        .badge-primary { background-color: #007bff; }
        .badge-success { background-color: #28a745; }
        .badge-info { background-color: #17a2b8; }
        .badge-warning { background-color: #ffc107; }
        .badge-secondary { background-color: #6c757d; }
        .badge-black { background-color: #000; }
        .separator-solid { border-top: 1px solid #ddd; margin: 1em 0; }
    </style>
</head>
<body>
<div class="card card-invoice">
    <div class="card-header" style="padding-bottom: 0; margin-bottom: 10px;">
        <div style="width: 100% !important;">
            <div style="float: left; width: 20% !important;">
                <img src="{{ public_path('assets/img/radhikas-logo.png') }}" height="100" alt="company logo" style="width: 100px;">
            </div>
            <div style="float: left; width: 75%; padding-left: 15px;">
                <h3 style="margin-bottom: 5px; text-transform: uppercase;">Radhikas Trade International</h3>
                <p style="margin: 0; font-size: 12px;">88/89, Sadarghat Road, Chattogram, Bangladesh 4000</p>
                <p style="margin: 0; font-size: 12px;">018 9770 1188, 019 9984 8389, 017 3222 6604</p>
            </div>
        </div>
    </div>
    <div style="clear: both; border-bottom: 1px solid #ccc;padding-top: 15px"></div>
    <div class="card-body">
        <div style="width: 100% !important;  margin-bottom: 40px;">
            <div style="float: left; width: 33.33%; padding-right: 15px;">
                <h5 style="margin-bottom: 0px; font-weight: bold;">Date</h5>
                <p style="margin-top: 0px; font-size: 13px">{{ $sale->date->format('M d, Y') }}</p>
            </div>
            <div style="float: left; width: 33.33%; padding-right: 15px;">
                <h5 style="margin-bottom: 0px; font-weight: bold;">Invoice No</h5>
                <p style="margin-top: 0px; font-size: 13px">#{{ $sale->invoice_no }}</p>
            </div>
            <div style="float: left; width: 33.33%; padding-right: 15px;">
                <h5 style="margin-bottom: 0px; font-weight: bold;">Invoice To</h5>
                <p style="font-size: 13px;margin-top: 0px">
                    {{ $sale->customer->name }}<br>
                    {{ $sale->customer->address }}<br>
                    {{ $sale->customer->phone }}
                </p>
            </div>
        </div>
        <div style="clear: both;"></div>
        <div class="row">
            <div class="col-md-12">
                <div class="invoice-detail">
                    <div class="invoice-top">
                        <h3 class="title" style="text-align: left"><strong>Order summary</strong></h3>
                    </div>
                    <div class="invoice-item">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="text-center">
                                <tr>
                                    <th style="font-size: 14px" class="text-start">Product</th>
                                    <th style="font-size: 14px">Packaging Type</th>
                                    <th style="font-size: 14px">Quantity</th>
                                    <th style="font-size:14px; text-align: right">Price</th>
                                    <th style="font-size:14px; text-align: right">Total</th>
                                </tr>
                                </thead>
                                <tbody class="text-center">
                                @foreach($sale->details as $detail)
                                    <tr>
                                        <td style="font-size: 13px" class="text-start">{{ $detail->product->name }}</td>
                                        <td style="font-size: 13px">{{ $detail->packagingType->type }}</td>
                                        <td style="font-size: 13px">{{ $detail->quantity }}</td>
                                        <td style="font-size: 13px; text-align: right">{{ number_format($detail->price, 0) }}</td>
                                        <td style="font-size: 13px; text-align: right">{{ number_format($detail->quantity * $detail->price, 0) }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th colspan="4" style="font-size: 13px;text-align: right; border: none">Subtotal</th><td style="font-size: 13px;text-align: right">{{ number_format($sale->subtotal,0) }}</td>
                                </tr>
                                @if($sale->customer_delivery_cost >0)
                                    <tr>
                                        <th colspan="4" style="font-size: 13px;text-align: right; border: none">Delivery Charge</th><td style="font-size: 13px;text-align: right">{{ number_format($sale->customer_delivery_cost,0) }}</td>
                                    </tr>
                                @endif
                                @if($sale->discount >0)
                                    <tr>
                                        <th colspan="4" style="font-size: 13px;text-align: right; border: none">Discount</th><td style="font-size: 13px;text-align: right">{{ number_format($sale->discount,0) }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <th colspan="4" style="font-size: 13px;text-align: right; border: none">Total</th><td style="font-size: 13px;text-align: right">{{ number_format($sale->total,0) }}</td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="card-footer">
        <div class="row">
            <div class="col-sm-7 col-md-7 mb-3 mb-md-0 transfer-to">
                <h4><strong>Bkash: 01852-173672 </strong><small>(Personal)</small></h4>
                <table style="width: 50%; border-collapse: collapse;">
                    <thead>
                    <tr>
                        <th colspan="2" style="border: 1px solid #000; text-align: center; padding: 5px;">Company Bank Information</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <th style="font-size: 12px; border: 1px solid #000; padding: 5px; text-align: left;">Bank Name</th>
                        <td style="font-size: 12px; border: 1px solid #000; padding: 5px; text-align: left;">Southeast Bank PLC</td>
                    </tr>
                    <tr>
                        <th style="font-size: 12px; border: 1px solid #000; padding: 5px; text-align: left;">Account Name</th>
                        <td style="font-size: 12px; border: 1px solid #000; padding: 5px; text-align: left;">Radhikas Trade International</td>
                    </tr>
                    <tr>
                        <th style="font-size: 12px; border: 1px solid #000; padding: 5px; text-align: left;">Account No</th>
                        <td style="font-size: 12px; border: 1px solid #000; padding: 5px; text-align: left;">000311100027215</td>
                    </tr>
                    <tr>
                        <th style="font-size: 12px; border: 1px solid #000; padding: 5px; text-align: left;">Branch Name</th>
                        <td style="font-size: 12px; border: 1px solid #000; padding: 5px; text-align: left;">Agrabad, Chattogram, Bangladesh</td>
                    </tr>
                    <tr>
                        <th style="font-size: 12px; border: 1px solid #000; padding: 5px; text-align: left;">Swift Code</th>
                        <td style="font-size: 12px; border: 1px solid #000; padding: 5px; text-align: left;">SEBDBDDHAGR</td>
                    </tr>
                    <tr>
                        <th style="font-size: 12px; border: 1px solid #000; padding: 5px; text-align: left;">Account Type</th>
                        <td style="font-size: 12px; border: 1px solid #000; padding: 5px; text-align: left;">CD</td>
                    </tr>
                    </tbody>
                </table>

            @if($sale->account_id != '')
                    <h5 class="sub">Payment VIA - {{ $sale->account->name }}</h5>
                    @if($sale->payment_details != '')
                        <div class="account-transfer">
                            <div><span>Details:</span><span>{{ $sale->payment_details }}</span></div>
                        </div>
                    @endif
                @endif
            </div>

        </div>

        @if($sale->note !='')
            <h6 class="text-uppercase mt-4 mb-1 fw-bold">Notes</h6>
            <p class="text-muted mb-0">{{ $sale->note }}</p>
        @endif

        @if($sale->created_by != '')
            <p style="margin-top: 30px">Sold By: <strong>{{ $sale->creator->name }}</strong></p>
        @endif
    </div>
</div>
</body>
</html>
