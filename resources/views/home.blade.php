@extends('layouts.app')
@section('title','Dashboard')
@section('content')
    <h5>Customer</h5>
    <div class="row">
        <!-- Customers -->
        <div class="col-sm-6 col-md-3 px-1">
            <div class="card card-stats card-primary card-round">
                <div class="card-body">
                    <div class="row">
                        <div class="col-4">
                            <div class="icon-big text-center">
                                <i class="fas fa-users"></i> <!-- Customers Icon -->
                            </div>
                        </div>
                        <div class="col-8 col-stats">
                            <div class="numbers">
                                <p class="card-category">Customers</p>
                                <h4 class="card-title">{{ $customerCounts['customer']->count ?? 0 }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dealer -->
        <div class="col-sm-6 col-md-3 px-1">
            <div class="card card-stats card-info card-round">
                <div class="card-body">
                    <div class="row">
                        <div class="col-4">
                            <div class="icon-big text-center">
                                <i class="fas fa-handshake"></i> <!-- Dealer Icon -->
                            </div>
                        </div>
                        <div class="col-8 col-stats">
                            <div class="numbers">
                                <p class="card-category">Dealer</p>
                                <h4 class="card-title">{{ $customerCounts['dealer']->count ?? 0 }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Commission Agent -->
        <div class="col-sm-6 col-md-3 px-1">
            <div class="card card-stats card-success card-round">
                <div class="card-body">
                    <div class="row">
                        <div class="col-4">
                            <div class="icon-big text-center">
                                <i class="fas fa-money-bill-wave"></i> <!-- Commission Agent Icon -->
                            </div>
                        </div>
                        <div class="col-8 col-stats">
                            <div class="numbers">
                                <p class="card-category">Commission Agent</p>
                                <h4 class="card-title">{{ $customerCounts['commission_agent']->count ?? 0 }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Wholesale -->
        <div class="col-sm-6 col-md-3 px-1">
            <div class="card card-stats card-secondary card-round">
                <div class="card-body">
                    <div class="row">
                        <div class="col-4">
                            <div class="icon-big text-center">
                                <i class="fas fa-boxes"></i> <!-- Wholesale Icon -->
                            </div>
                        </div>
                        <div class="col-8 col-stats">
                            <div class="numbers">
                                <p class="card-category">Wholesale</p>
                                <h4 class="card-title">{{ $customerCounts['wholesale']->count ?? 0 }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <h5>Warehouse</h5>
    <div class="row">
        @foreach($stocks as $stock)
            <div class="col-sm-6 col-md-3 px-1">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-4">
                                <div class="icon-big text-center">
                                    <i class="icon-pie-chart text-warning"></i>
                                </div>
                            </div>
                            <div class="col-8 col-stats">
                                <div class="numbers">
                                    <p class="card-category">{{ $stock->type }} ({{ $stock->weight_kg }} kg)</p>
                                    <h4 class="card-title">{{ number_format($stock->stock,0) }} <small>Packs</small></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <h5>Sales</h5>
    <div class="row">
        <!-- Today's Sales -->
        <div class="col-sm-6 col-lg-3 px-1">
            <div class="card p-3">
                <div class="d-flex align-items-center">
                <span class="stamp stamp-md bg-secondary me-3">
                    <i class="fas fa-calendar"></i> <!-- Today's Sales Icon -->
                </span>
                    <div>
                        <h5 class="mb-1">
                            <b><a href="#">{{ $todaySalesCount }} <small>Sales</small></a></b>
                        </h5>
                        <small class="text-muted">Today's amount: ৳{{ number_format($todaySalesAmount, 0) }}</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- This Month's Sales -->
        <div class="col-sm-6 col-lg-3 px-1">
            <div class="card p-3">
                <div class="d-flex align-items-center">
                <span class="stamp stamp-md bg-success me-3">
                    <i class="fas fa-calendar-alt"></i> <!-- This Month's Sales Icon -->
                </span>
                    <div>
                        <h5 class="mb-1">
                            <b><a href="#">{{ $thisMonthSalesCount }} <small>Sales</small></a></b>
                        </h5>
                        <small class="text-muted">This month: ৳{{ number_format($thisMonthSalesAmount, 0) }}</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Last Month's Sales -->
        <div class="col-sm-6 col-lg-3 px-1">
            <div class="card p-3">
                <div class="d-flex align-items-center">
                <span class="stamp stamp-md bg-danger me-3">
                    <i class="fas fa-calendar-minus"></i> <!-- Last Month's Sales Icon -->
                </span>
                    <div>
                        <h5 class="mb-1">
                            <b><a href="#">{{ $lastMonthSalesCount }} <small>Sales</small></a></b>
                        </h5>
                        <small class="text-muted">Last month: ৳{{ number_format($lastMonthSalesAmount, 0) }}</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- This Year's Sales -->
        <div class="col-sm-6 col-lg-3 px-1">
            <div class="card p-3">
                <div class="d-flex align-items-center">
                <span class="stamp stamp-md bg-warning me-3">
                    <i class="fas fa-calendar"></i> <!-- This Year's Sales Icon -->
                </span>
                    <div>
                        <h5 class="mb-1">
                            <b><a href="#">{{ $thisYearSalesCount }} <small>Sales</small></a></b>
                        </h5>
                        <small class="text-muted">This year: ৳{{ number_format($thisYearSalesAmount, 0) }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 px-1">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 text-uppercase">Today's Expenses ({{ \Carbon\Carbon::now()->format('d M, Y') }}) - <small><strong>৳{{ number_format($todaysExpenses->sum('total'), 2) }}</small></h5>
                </div>
                <div class="card-body p-0">
                    <!-- Scrollable table -->
                    <div style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Category</th>
                                <th>Total Expenses </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($todaysExpenses as $expense)
                                <tr>
                                    <td>{{ $expense->expenseCategory->name ?? 'Uncategorized' }}</td>
                                    <td>{{ number_format($expense->total, 2) }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
