<?php

namespace App\Exports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CustomersExport implements FromCollection, WithHeadings
{
    protected $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    /**
     * Return the collection of data that will be exported.
     */
    public function collection()
    {
        return $this->query->get()->map(function ($customer) {
            return [
                'name'    => $customer->name,
                'address' => $customer->address,
                'phone'   => $customer->phone,
                'type'    => $customer->type,
                'balance' => $customer->balance, // Accessing the getBalanceAttribute
            ];
        });
    }

    /**
     * Set the headings for the exported file.
     */
    public function headings(): array
    {
        return [
            'Name',
            'Address',
            'Phone',
            'Type',
            'Balance'
        ];
    }
}
