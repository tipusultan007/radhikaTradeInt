<!DOCTYPE html>
<html>
<head>
    <title>Customer List</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
<h1 style="text-align: center">Customer List</h1>
<table>
    <thead>
    <tr>
        <th>#</th>
        <th>Name</th>
        <th>Address</th>
        <th>Phone</th>
        <th>Type</th>
        <th>Balance</th>
    </tr>
    </thead>
    <tbody>
    @php
    $i = 1
    @endphp
    @foreach($customers as $customer)
        <tr>
            <td>{{ $i++ }}</td>
            <td>{{ ucwords(strtolower($customer->name)) }}</td>
            <td>{{ ucwords(strtolower($customer->address)) }}</td>
            <td>{{ $customer->phone }}</td>
            <td>{{ ucwords(str_replace('_', ' ', $customer->type)) }}</td>
            <td>{{ $customer->balance }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
