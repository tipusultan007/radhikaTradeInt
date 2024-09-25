@extends('layouts.app')
@section('title','Summary')
@section('content')
    <div class="card">
        <div class="card-body">
            <form method="GET" class="row" action="{{ route('product.summary') }}">
                <div class="form-group col-md-3">
                    <label for="start_date">Start Date:</label>
                    <input type="text" class="form-control flatpickr" id="start_date" name="start_date" value="{{ request('start_date',date('Y-m-d')) }}">
                </div>
                <div class="form-group col-md-3">
                    <label for="end_date">End Date:</label>
                    <input type="text" class="form-control flatpickr" id="end_date" name="end_date" value="{{ request('end_date',date('Y-m-d')) }}">
                </div>

                <div class="col-md-3 form-group gap-2 d-flex align-items-end">
                    <button class="btn btn-primary" type="submit">Search</button>
                    <button type="submit" class="btn btn-danger" name="download_pdf" value="true">Download PDF</button>
                </div>
            </form>

        </div>
    </div>
    <!-- Summary Table -->
    <table class="table table-sm table-bordered table-striped">
        <caption style="caption-side: top;font-weight: bold">Total Sale By Package</caption>
        <thead>
        <tr>
            <th>#</th>
            <th style="font-size: 12px" class="text-center">Product Name</th>
            <th style="font-size: 12px" class="text-center">Packaging Type</th>
            <th style="font-size: 12px" class="text-end">Total Quantity</th>
            <th style="font-size: 12px" class="text-end">Total Price</th>
        </tr>
        </thead>
        <tbody>
        @php
            $totalQty = 0;
            $totalPrice = 0;
            $i = 1;
        @endphp
        @if($productSummary->isEmpty())
            <tr>
                <td colspan="5">No data found</td>
            </tr>
        @else
            @foreach($productSummary as $item)
                @php
                    $totalQty += $item->total_quantity;
                    $totalPrice += $item->total_price;
                @endphp
                <tr>
                    <td>{{ $i++ }}</td>
                    <td class="text-center">{{ $item->product->name }}</td>
                    <td class="text-center">{{ $item->packagingType->type }}</td>
                    <td class="text-end">{{ $item->total_quantity }}</td>
                    <td class="text-end">{{ number_format($item->total_price, 0) }}</td>
                </tr>
            @endforeach
        @endif
        </tbody>
        <tr>
            <td colspan="3"></td>
            <td style="font-size: 12px" class="text-end"><strong> {{ $totalQty }}</strong></td>
            <td style="font-size: 12px" class="text-end"><strong> {{ number_format($totalPrice,0) }}</strong></td>
        </tr>
    </table>

    <table class="table table-bordered table-sm table-striped">
        <caption style="caption-side: top;font-weight: bold">Total Sale List</caption>
        <thead>
        <tr>
            <th>#</th>
            <th style="font-size: 12px">Date</th>
            <th style="font-size: 12px">Inv</th>
            <th style="width: 250px;font-size: 12px">Customer</th>
            <th style="font-size: 12px">Created</th>
            <th style="font-size: 12px">Status</th>
            <th style="font-size: 12px" class="text-center">Qty</th>
            <th style="text-align: center; font-size: 12px">Total</th>
            <th style="text-align: center; font-size: 12px">Discount</th>
        </tr>
        </thead>
        <tbody>
        @php
            $saleQty = 0;
            $salePrice = 0;
            $saleDiscount = 0;
            $j=1;
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
            <td class="text-end"><strong> {{ $saleQty }}</strong></td>
            <td class="text-end"><strong> {{ number_format($salePrice,0) }}</strong></td>
            <td class="text-end"><strong> {{ number_format($saleDiscount,0) }}</strong></td>
        </tr>
    </table>

@endsection

@section('js')
    <script>
        $(".select2").select2({
            theme: 'bootstrap',
            width: '100%',
        })
    </script>
@endsection
