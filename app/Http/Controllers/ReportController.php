<?php

namespace App\Http\Controllers;

use App\Models\PackagingType;
use App\Models\Sale;
use App\Models\SaleDetail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function productSummary(Request $request)
    {
        // Retrieve filters from request
        $startDate = $request->input('start_date', date('Y-m-d'));
        $endDate = $request->input('end_date', date('Y-m-d'));
        $downloadPdf = $request->input('download_pdf', false); // Check if PDF download is requested

        // Query to get total quantity and price grouped by product_id and packaging_type_id
        $productQuery = SaleDetail::select('product_id', 'packaging_type_id')
            ->selectRaw('SUM(quantity) as total_quantity')
            ->selectRaw('SUM(quantity * price) as total_price')
            ->groupBy('product_id', 'packaging_type_id')
            ->with(['product', 'packagingType']);

        $salesQuery = Sale::query();

        // Apply date range filter based on Sale model's created_at field using whereHas
        if ($startDate && $endDate) {
            $productQuery->whereHas('sale', function ($q) use ($startDate, $endDate) {
                $q->whereBetween('date', [$startDate, $endDate]);
            });

            $salesQuery->whereBetween('date', [$startDate, $endDate]);
        }

        $productSummary = $productQuery->get();
        $sales = $salesQuery->orderBy('date', 'desc')->get();

        // Handle PDF generation if requested
        if ($downloadPdf) {
            $pdf = Pdf::loadView('reports.product-summary-pdf', compact('productSummary', 'sales', 'startDate', 'endDate'));

            return $pdf->download('product_summary_' . now()->format('Y_m_d') . '.pdf');
        }

        // Return to the view for normal rendering
        return view('reports.product-summary', compact('productSummary', 'sales', 'startDate', 'endDate'));
    }


    public function productSales()
    {

    }
}
