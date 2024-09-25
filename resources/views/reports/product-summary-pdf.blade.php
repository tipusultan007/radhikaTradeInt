<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px 12px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        /* Set page margins with space for footer */
        @page {
            margin: 10mm; /* top right bottom left */
        }

    </style>
</head>
<body>
<div style="margin-top:0px;margin-bottom: 10px;border-bottom: 1px solid #222">
    <h1 style="text-align: center">Radhikas Trade International</h1>
    <p style="text-align: center">88/89, Sadarghat Road, Chattogram, Bangladesh 4000</p>
    <p style="text-align: center">018 9770 1188, 019 9984 8389, 017 3222 6604</p>
</div>
<div class="page-heading" style="max-width: 300px;margin: 0 auto; border-bottom: 1px solid #ddd;">
    <h3 style="text-align: center">Summary Report</h3>
    <p style="text-align: center;">Date Range: {{ date('d/m/Y',strtotime($startDate)) }} to {{ date('d/m/Y',strtotime($endDate)) }}</p>
</div>
<div style="margin-bottom: 30px"></div>
<h3>Product Summary</h3>
<table>
    <thead>
    <tr>
        <th style="text-align: left">#</th>
        <th class="text-center">Product Name</th>
        <th class="text-center">Packaging Type</th>
        <th style="text-align: right">Quantity</th>
        <th style="text-align: right">Price</th>
    </tr>
    </thead>
    <tbody>
    @php
        $totalQty = 0;
        $totalPrice = 0;
    @endphp
    @if($productSummary->isEmpty())
        <tr>
            <td colspan="4">No data found</td>
        </tr>
    @else
        @php
        $i = 1;
        @endphp
        @foreach($productSummary as $item)
            @php
                $totalQty += $item->total_quantity;
                $totalPrice += $item->total_price;
            @endphp
            <tr>
                <td>{{ $i++ }}</td>
                <td class="text-center">{{ $item->product->name }}</td>
                <td class="text-center">{{ $item->packagingType->type }}</td>
                <td style="text-align: right">{{ $item->total_quantity }}</td>
                <td style="text-align: right">{{ number_format($item->total_price, 0) }}</td>
            </tr>
        @endforeach
    @endif
    </tbody>
    <tr>
        <td colspan="3"></td>
        <td style="text-align: right"><strong> {{ $totalQty }}</strong></td>
        <td style="text-align: right"><strong> {{ number_format($totalPrice,0) }}</strong></td>
    </tr>
</table>

<h3>Sales</h3>
<table>
    <thead>
    <tr>
        <th>#</th>
        <th>Date</th>
        <th>Inv</th>
        <th style="width: 150px">Customer</th>
        <th>Created</th>
        <th>Status</th>
        <th style="text-align: right">Qty</th>
        <th style="text-align: right">Total</th>
        <th style="text-align: right">Discount</th>
    </tr>
    </thead>
    <tbody>
    @php
        $saleQty = 0;
        $salePrice = 0;
        $saleDiscount = 0;
    @endphp
    @php
 $j = 1;
  @endphp
    @foreach($sales as $sale)
        @php
            $saleQty += $sale->details->sum('quantity');
            $salePrice += $sale->total;
            $saleDiscount += $sale->discount;
        @endphp
        <tr>
            <td>{{ $j++ }}</td>
            <td>{{ $sale->date->format('d/m/Y') }}</td>
            <td>{{ $sale->invoice_no }}</td>
            <td>{{ ucwords(strtolower($sale->customer->name)) }}</td>
            <td>{{ $sale->creator->name??'' }}</td>
            <td>{{ ucfirst($sale->status) }}</td>
            <td style="text-align: right">{{ $sale->details->sum('quantity') }}</td>
            <td style="text-align: right">{{ number_format($sale->total,0) }}</td>
            <td style="text-align: right">{{ number_format($sale->discount,0) }}</td>
        </tr>
    @endforeach
    </tbody>
    <tr>
        <td colspan="6"></td>
        <td style="text-align: right"><strong> {{ $saleQty }}</strong></td>
        <td style="text-align: right"><strong> {{ number_format($salePrice,0) }}</strong></td>
        <td style="text-align: right"><strong> {{ number_format($saleDiscount,0) }}</strong></td>
    </tr>
</table>
</body>
</html>
