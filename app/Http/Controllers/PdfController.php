<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfController extends Controller
{
    public function generateInvoice($id)
    {
        $sale = Sale::with('customer', 'details.product', 'details.packagingType', 'account')
            ->findOrFail($id);

        $invoiceNo = $sale->invoice_no;
        $invoiceDate = $sale->date->format('dMY');
        $fileName = 'invoice-' . $invoiceNo . '_' . $invoiceDate . '.pdf';
        // Load the view and pass the sale data to it
        $pdf = Pdf::loadView('pdf.invoice', compact('sale'));

        // Return the generated PDF file
        return $pdf->download($fileName);
    }
}
