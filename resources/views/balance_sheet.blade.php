@extends('layouts.app')
@section('title','Balance Sheet')
@section('content')
    <div class="row">
        <div class="col-md-6">
            <h3>Assets</h3>
            <ul class="list-group">
                @foreach($assets as $asset)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $asset->name }}
                        <span>৳ {{ number_format($asset->balance(), 2) }}</span>
                    </li>
                @endforeach
            </ul>
            <h4>Total Assets: ৳ {{ number_format($totalAssets, 2) }}</h4>
        </div>

        <div class="col-md-6">
            <h3>Liabilities</h3>
            <ul class="list-group">
                @foreach($liabilities as $liability)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $liability->name }}
                        <span>৳ {{ number_format($liability->balance(), 2) }}</span>
                    </li>
                @endforeach
            </ul>
            <h4>Total Liabilities: ৳ {{ number_format($totalLiabilities, 2) }}</h4>

            <h3>Equity</h3>
            <ul class="list-group">
                @foreach($equity as $equityAccount)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $equityAccount->name }}
                        <span>৳ {{ number_format($equityAccount->balance(), 2) }}</span>
                    </li>
                @endforeach
            </ul>
            <h4>Total Equity: ৳ {{ number_format($totalEquity, 2) }}</h4>
        </div>
    </div>
@endsection
