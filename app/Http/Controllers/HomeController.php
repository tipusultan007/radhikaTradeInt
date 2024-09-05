<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Expense;
use App\Models\Sale;
use App\Models\Warehouse;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Get the counts for each customer type
        $customerCounts = Customer::selectRaw('type, COUNT(*) as count')
            ->groupBy('type')
            ->get()
            ->keyBy('type'); // Organize results by 'type' for easier access

        // Fetch warehouse stock along with packaging type
        $stocks = Warehouse::join('packaging_types', 'warehouses.packaging_type_id', '=', 'packaging_types.id')
            ->select('warehouses.stock', 'packaging_types.type', 'packaging_types.weight_kg')
            ->get();

        // Get today's sales count and amount
        $todaySales = Sale::whereDate('date', Carbon::today());
        $todaySalesCount = $todaySales->count();
        $todaySalesAmount = $todaySales->sum('total');

        // Get this month's sales count and amount
        $thisMonthSales = Sale::whereMonth('date', Carbon::now()->month)
            ->whereYear('date', Carbon::now()->year);
        $thisMonthSalesCount = $thisMonthSales->count();
        $thisMonthSalesAmount = $thisMonthSales->sum('total');

        // Get last month's sales count and amount
        $lastMonth = Carbon::now()->subMonth();
        $lastMonthSales = Sale::whereMonth('date', $lastMonth->month)
            ->whereYear('date', $lastMonth->year);
        $lastMonthSalesCount = $lastMonthSales->count();
        $lastMonthSalesAmount = $lastMonthSales->sum('total');

        // Get this year's sales count and amount
        $thisYearSales = Sale::whereYear('date', Carbon::now()->year);
        $thisYearSalesCount = $thisYearSales->count();
        $thisYearSalesAmount = $thisYearSales->sum('total');


        // Fetch total expenses for today, grouped by category
        $todaysExpenses = Expense::whereDate('date', date('Y-m-d'))
            ->selectRaw('expense_category_id, SUM(amount) as total')
            ->with('expenseCategory')
            ->groupBy('expense_category_id')
            ->get();

        return view('home', compact(
            'customerCounts', 'stocks',
            'todaySalesCount', 'todaySalesAmount',
            'thisMonthSalesCount', 'thisMonthSalesAmount',
            'lastMonthSalesCount', 'lastMonthSalesAmount',
            'thisYearSalesCount', 'thisYearSalesAmount',
            'todaysExpenses'
        ));
    }
}
