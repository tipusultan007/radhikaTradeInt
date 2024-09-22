<?php

namespace App\Exports;

use App\Models\Sale;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SalesExport implements FromQuery, WithHeadings, WithMapping
{
    public function query()
    {
        return Sale::query()->orderBy('date','asc'); // Apply any query filters if needed
    }

    public function headings(): array
    {
        return [
            'Customer','Phone','Address', 'Payment VIA', 'Invoice No', 'Subtotal', 'Customer Delivery Cost',
            'Owner Delivery Cost', 'Discount', 'Paid Amount',  'Total', 'Note',
            'Date', 'Payment Details', 'Status', 'Referrer ID','Dispatched At', 'Delivered At',
            'Dispatched By', 'Delivered By', 'Created By'
        ];
    }

    public function map($sale): array
    {
        return [
            $sale->customer->name,
            $sale->customer->phone??'',
            $sale->customer->address??'',
            $sale->account->name??'',
            $sale->invoice_no,
            $sale->subtotal,
            $sale->customer_delivery_cost,
            $sale->owner_delivery_cost,
            $sale->discount,
            $sale->paid_amount,
            $sale->total,
            $sale->note,
            $sale->date->format('d/m/Y'),
            $sale->payment_details,
            $sale->status,
            $sale->referrer->name??'',
            $sale->dispatched_at?$sale->dispatched_at->format('d/m/Y'):'',
            $sale->delivered_at?$sale->delivered_at->format('d/m/Y'):'',
            $sale->dispatchedBy->name??'',
            $sale->deliveredBy->name??'',
            $sale->creator->name??'',
        ];
    }
}

