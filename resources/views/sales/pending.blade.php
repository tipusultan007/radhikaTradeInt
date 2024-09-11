@extends('layouts.app')
@section('title','Pending Sales')
@section('create-button')
    <a href="{{ route('sales.create') }}" class="btn btn-primary">Create New Sale</a>
@endsection
@section('content')
    <table class="table table-striped table-bordered">
        <thead>
        <tr>
            <th>Date</th>
            <th>Invoice #</th>
            <th>Customer</th>
            <th>Type</th>
            <th class="text-end">Total Amount</th>
            <th class="text-end">Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($sales as $sale)
            <tr>
                <td>{{ $sale->date->format('d/m/Y') }}</td>
                <td>{{ $sale->invoice_no }}</td>
                <td>{{ $sale->customer->name }}</td>
                <td>
                    @php
                        $badgeClass = '';
                        switch($sale->customer->type) {
                            case 'dealer':
                                $badgeClass = 'badge-primary';
                                break;
                            case 'commission_agent':
                                $badgeClass = 'badge-success';
                                break;
                            case 'retailer':
                                $badgeClass = 'badge-info';
                                break;
                            case 'wholesale':
                                $badgeClass = 'badge-warning';
                                break;
                            case 'retail':
                                $badgeClass = 'badge-secondary';
                                break;
                            case 'customer':
                                $badgeClass = 'badge-black';
                                break;
                            default:
                                $badgeClass = 'badge-count';
                                break;
                        }
                    @endphp
                    <span class="badge {{ $badgeClass }}">{{ ucfirst(str_replace('_', ' ', $sale->customer->type)) }}</span>
                </td>
                <td class="text-end">{{ $sale->total }}</td>
                <td class="text-end">
                    <button class="btn btn-success make-deliver" data-id="{{ $sale->id }}">
                        <i class="fas fa-truck"></i> Make it Deliver
                    </button>
                </td>

            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $sales->links() }}
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const buttons = document.querySelectorAll('.make-deliver');
            buttons.forEach(button => {
                button.addEventListener('click', function() {
                    const saleId = this.getAttribute('data-id');

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You want to mark this sale as delivered?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, deliver it!'
                    }).then((result) => {
                        if (result && result.isConfirmed) {
                            // Perform the AJAX request to update the status
                            fetch(`/admin/sales/${saleId}/deliver`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({ status: 'delivered' })
                            })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        Swal.fire(
                                            'Delivered!',
                                            'The sale has been marked as delivered.',
                                            'success'
                                        );
                                        location.reload();
                                    } else {
                                        Swal.fire(
                                            'Error!',
                                            'There was an issue marking the sale as delivered.',
                                            'error'
                                        );
                                    }
                                })
                                .catch(error => {
                                    // Catch any errors from the fetch request
                                    Swal.fire(
                                        'Error!',
                                        'An error occurred while processing your request. Please try again later.',
                                        'error'
                                    );
                                    console.error('Error:', error); // Log the error for debugging
                                });
                        }
                    });
                });
            });
        });
    </script>


@endsection
