<!DOCTYPE html>
<html>
<head>
    <title>Sales PDF</title>
</head>
<body>
<h1>Sales Report</h1>
<table style="width: 100%" border="1" cellspacing="0" cellpadding="5">
    <thead>
    <tr>
        <th>Date</th>
        <th>Invoice</th>
        <th style="width: 250px">Customer</th>
        <th>Created By</th>
        <th>Status</th>
        <th style="text-align: center">Total</th>
    </tr>
    </thead>
    <tbody>
    @foreach($sales as $sale)
        <tr>
            <td>{{ $sale->date->format('d/m/Y') }}</td>
            <td>{{ $sale->invoice_no }}</td>
            <td>{{ ucwords(strtolower($sale->customer->name)) }}</td>
            <td>{{ $sale->creator->name??'' }}</td>
            <td>{{ ucfirst($sale->status) }}</td>
            <td style="text-align: right">{{ number_format($sale->total,0) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
