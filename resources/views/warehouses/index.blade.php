@extends('layouts.app')

@section('title', 'Warehouse Products')
@section('create-button')
    <a href="{{ route('warehouses.create') }}" class="btn btn-primary">Add New Product to Warehouse</a>
@endsection
@section('content')
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif


    @if ($warehouses->count())
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Product</th>
                <th>Packaging Type</th>
                <th>Stock</th>
                <th>Cost</th>
                <th>Sale Price</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($warehouses as $warehouse)
                <tr>
                    <td>{{ $warehouse->product->name }}</td>
                    <td>{{ $warehouse->packagingType->type }}</td>
                    <td>{{ $warehouse->stock }}</td>
                    <td>{{ $warehouse->cost }}</td>
                    <td>{{ $warehouse->sale_price }}</td>
                    <td class="text-end">
                        <button type="button"
                                data-product="{{ $warehouse->product->name }}"
                                data-package="{{ $warehouse->packagingType->type }}"
                                data-id="{{ $warehouse->id }}"
                                class="btn btn-primary btn-icon btn-stock">
                            <i class="fas fa-plus"></i>
                        </button>
                        <a href="{{ route('warehouses.show', $warehouse->id) }}" class="btn btn-secondary btn-icon">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('warehouses.edit', $warehouse->id) }}" class="btn btn-warning btn-icon">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('warehouses.destroy', $warehouse->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-icon">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <p>No products found in warehouse.</p>
    @endif

    <div class="modal fade" id="stockModal" tabindex="-1" aria-labelledby="stockModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="stockModalLabel">Add Product Stock</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('product-stock.store') }}" method="POST" class="mt-4">
                        @csrf
                        <div class="row">
                            <input type="hidden" name="warehouse_id" id="warehouse_id">
                            <div class="form-group col-md-6">
                                <label for="productName" class="form-label">Product</label>
                                <input type="text" id="productName" class="form-control" readonly>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="productPackage" class="form-label">Package</label>
                                <input type="text" id="productPackage" class="form-control" readonly>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="date" class="form-label">Date:</label>
                                <input type="text" name="date" id="date" class="form-control flatpickr"
                                       value="{{ date('Y-m-d') }}" required>
                            </div>


                            <div class="form-group col-md-6">
                                <label for="quantity" class="form-label">Quantity</label>
                                <input type="number" name="quantity" id="quantity" step="1" class="form-control"
                                       required>
                            </div>

                            <div class="form-group col-md-12 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary">Add</button>
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
        $(".btn-stock").on("click",function () {
            var id = $(this).data('id');
            var product = $(this).data('product');
            var packageName = $(this).data('package');

            $("#warehouse_id").val(id);
            $("#productName").val(product);
            $("#productPackage").val(packageName);
            $("#stockModal").modal("show");
        })
    </script>
@endsection
