@extends('layouts.app')

@section('title', 'Add Product to Warehouse')
@section('create-button')
    <a href="{{ route('warehouses.index') }}" class="btn btn-secondary">Back to Warehouse</a>
@endsection
@section('content')

    <div class="card">
        <div class="card-body">
            <form action="{{ route('warehouses.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-4 form-group">
                        <label for="product_id">Product</label>
                        <select name="product_id" id="product_id" class="form-control" required>
                            <option value=""></option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 form-group">
                        <label for="packaging_type_id">Packaging Type</label>
                        <select name="packaging_type_id" id="packaging_type_id" class="form-control select2" required>
                            <option value="">-- Select --</option>
                            @foreach ($packagingTypes as $packagingType)
                                <option value="{{ $packagingType->id }}">{{ $packagingType->type }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 form-group">
                        <label for="stock">Stock</label>
                        <input type="number" name="stock" id="stock" class="form-control" required min="0" step="0.01">
                    </div>

                    <div class="col-md-4 form-group">
                        <label for="cost">Cost</label>
                        <input type="number" name="cost" id="cost" class="form-control" required min="0" step="0.01">
                    </div>

                    <div class="col-md-4 form-group">
                        <label for="sale_price">Sale Price</label>
                        <input type="number" name="sale_price" id="sale_price" class="form-control" required min="0" step="0.01">
                    </div>
                </div>


                <button type="submit" class="btn btn-success">Add Product</button>
                <a href="{{ route('warehouses.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>

@endsection
@section('js')
    <script>
        $("#packaging_type_id").select2({
            theme: 'bootstrap',
            width: '100%',
            placeholder: '--Select Package--'
        })
        $("#product_id").select2({
            theme: 'bootstrap',
            width: '100%',
            placeholder: '--Select Product--'
        })
    </script>
@endsection
