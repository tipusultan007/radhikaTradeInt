@extends('layouts.app')
@section('title','Dispatched Sales')
@section('create-button')
    <a href="{{ route('sales.create') }}" class="btn btn-primary">Create New Sale</a>
@endsection
@section('content')
    <table class="table table-striped table-bordered table-info">
        <thead>
        <tr>
            <th>Date</th>
            <th>Inv #</th>
            <th>Customer</th>
            <th>Type</th>
            <th class="text-end">Total</th>
            <th>Dispatched At</th>
            <th>Dispatched By</th>
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
                <td>{{ $sale->dispatched_at->format('d/m/Y') }}</td>
                <td>{{ $sale->dispatchedBy->name }}</td>
                <td class="text-end">
                    <div class="d-flex justify-content-end gap-2">
                        <button class="btn btn-primary btn-details" data-id="{{ $sale->id }}">Details</button>
                        {{--<a target="_blank" href="{{ route('sales.show', $sale->id) }}" class="btn btn-icon btn-secondary">
                            <i class="fas fa-eye"></i>
                        </a>--}}
                       @if($sale->status == 'dispatched')
                            <button class="btn btn-sm btn-success make-deliver" data-id="{{ $sale->id }}">
                                <i class="fas fa-truck"></i> Deliver
                            </button>
                        @else
                            <button class="btn btn-sm btn-info make-dispatch" data-id="{{ $sale->id }}">
                                <i class="fas fa-box"></i> Dispatch
                            </button>
                       @endif
                    </div>
                </td>

            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $sales->links() }}

    <!-- Modal HTML -->
    <div class="modal fade" id="saleDetailsModal" tabindex="-1" aria-labelledby="saleDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="saleDetailsModalLabel">Sale Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-5" id="modalContent">
                    <!-- Sale details will be loaded here dynamically -->
                </div>
            </div>
        </div>
    </div>
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

            // Handle "Dispatch" button click
            const dispatchButtons = document.querySelectorAll('.make-dispatch');
            dispatchButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const saleId = this.getAttribute('data-id');

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You want to mark this sale as dispatched?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, dispatch it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch(`/admin/sales/${saleId}/dispatch`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({ status: 'dispatched' })
                            })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        Swal.fire(
                                            'Dispatched!',
                                            'The sale has been marked as dispatched.',
                                            'success'
                                        );
                                        location.reload();
                                    } else {
                                        Swal.fire(
                                            'Error!',
                                            'There was an issue marking the sale as dispatched.',
                                            'error'
                                        );
                                    }
                                })
                                .catch(error => {
                                    Swal.fire(
                                        'Error!',
                                        'An error occurred while processing your request. Please try again later.',
                                        'error'
                                    );
                                    console.error('Error:', error);
                                });
                        }
                    });
                });
            });
        });
    </script>

    <script>
        $(document).ready(function () {
            $('.btn-details').on('click', function () {
                var saleId = $(this).data('id');

                // Make an Ajax request to fetch sale details
                $.ajax({
                    url: '/admin/sale-details/' + saleId, // Assuming a route like /sales/{id}
                    method: 'GET',
                    success: function (response) {
                        // Populate modal with the sale details
                        $('#modalContent').html(response);
                        $('#saleDetailsModal').modal('show'); // Show the modal
                    }
                });
            });
        });

    </script>
@endsection
