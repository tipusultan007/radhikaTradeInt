@extends('layouts.app')

@section('title', 'Product Details')

@section('create-button')
    <a href="{{ route('warehouses.index') }}" class="btn btn-secondary">Back to Warehouse</a>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4">
            <h5>Product Info</h5>
            <table class="table table-bordered table-striped">
                <tr>
                    <th>Product Name</th> <td>{{ $warehouseProduct->product->name }}</td>
                </tr>
                <tr>
                    <th>Package Type</th> <td>{{ $warehouseProduct->packagingType->type }}</td>
                </tr>
                <tr>
                    <th>Quantity</th> <td>{{ number_format($warehouseProduct->stock,0) }}</td>
                </tr>
            </table>
        </div>
        <div class="col-md-8">
            <h5>Product Price Table</h5>
            <table class="table table-bordered table-striped">
                <tr>
                    <th>Sale Price</th> <td>{{ $warehouseProduct->sale_price }}</td>
                    <th>Dealer Price</th> <td>{{ $warehouseProduct->dealer_price }}</td>
                </tr>
                <tr>
                    <th>Commission Agent Price</th> <td>{{ $warehouseProduct->commission_agent_price }}</td>
                    <th>Retailer Price</th> <td>{{ $warehouseProduct->retailer_price }}</td>
                </tr>
                <tr>
                    <th>Retail Price</th> <td>{{ $warehouseProduct->retail_price }}</td>
                    <th>Wholesale Price</th> <td>{{ $warehouseProduct->wholesale_price }}</td>
                </tr>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-md-5">
            <h5>Product Stock In List</h5>
            <table class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th class="text-left">Date</th>
                    <th class="text-center">Quantity</th>
                    <th class="text-center">Action</th>
                </tr>
                </thead>
                @forelse($warehouseProduct->productStocks as $stock)
                <tr>
                    <td>{{ $stock->date->format('d/m/Y') }}</td>
                    <td class="text-end">{{ $stock->quantity }}</td>
                    <td class="text-end">
                        <button type="button"
                                data-id="{{ $stock->id }}"
                                data-quantity="{{ $stock->quantity }}"
                                data-date="{{ $stock->date }}"
                                class="btn btn-primary btn-icon edit-stock">
                            <i class="fas fa-edit"></i>
                        </button>
                        <form action="{{ route('product-stock.destroy', $stock->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-icon">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                @endforelse
            </table>
        </div>
        <div class="col-md-7">
            <h5>Product Sale List</h5>
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>Date</th>
                    <th class="text-center">Invoice</th>
                    <th>Customer</th>
                    <th class="text-center">Quantity</th>
                </tr>
                </thead>
                @forelse($soldItems as $item)
                <tr>
                    <td>{{ $item->sale->date->format('d/m/Y') }}</td>
                    <td class="text-center"><a href="{{ route('sales.show', $item->sale_id) }}" class="badge text-white bg-secondary">#{{ $item->sale->invoice_no }}</a></td>
                    <td>{{ $item->sale->customer->name }}</td>
                    <td class="text-center">{{ $item->quantity }}</td>
                </tr>
                @empty
                @endforelse
            </table>
            {{ $soldItems->links() }}
        </div>
    </div>

    <div class="modal fade" id="stockEditModal" tabindex="-1" aria-labelledby="stockModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="stockModalLabel">Edit Product Stock</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editStockForm" action="{{ route('product-stock.update', ['product_stock' => ':id']) }}" method="POST" class="mt-4">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="date" class="form-label">Date:</label>
                                <input type="text" name="date" id="date" class="form-control flatpickr" required>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="quantity" class="form-label">Quantity</label>
                                <input type="number" name="quantity" id="quantity" step="1" class="form-control" required>
                            </div>

                            <div class="form-group col-md-12 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function() {
            $('.edit-stock').on('click', function() {
                var id = $(this).data('id');
                var quantity = $(this).data('quantity');
                var date = $(this).data('date');

                // Fill the form fields with the current stock data
                $('#quantity').val(quantity);
                $('#date').val(date);

                $("#date").flatpickr({
                    altInput: true,
                    altFormat: "d/m/Y",
                    dateFormat: "Y-m-d",
                })

                // Update the form action to point to the correct update route
                var actionUrl = $('#editStockForm').attr('action').replace(':id', id);
                $('#editStockForm').attr('action', actionUrl);

                // Show the modal
                $('#stockEditModal').modal('show');
            });
        });

    </script>
@endsection
