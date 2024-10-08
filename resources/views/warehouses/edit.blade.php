@extends('layouts.app')

@section('title', 'Edit Warehouse Product')

@section('create-button')
    <a href="{{ route('warehouses.index') }}" class="btn btn-secondary">Back to Warehouse</a>
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('warehouses.update', $warehouse->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-4 form-group">
                        <label for="product_id">Product</label>
                        <select name="product_id" id="product_id" class="form-control select2" data-placeholder="--Select product--" required>
                            <option value=""></option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}" {{ $warehouse->product_id == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 form-group">
                        <label for="packaging_type_id">Packaging Type</label>
                        <select name="packaging_type_id" id="packaging_type_id" class="form-control select2" required>
                            @foreach ($packagingTypes as $packagingType)
                                <option value="{{ $packagingType->id }}" {{ $warehouse->packaging_type_id == $packagingType->id ? 'selected' : '' }}>
                                    {{ $packagingType->type }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 form-group">
                        <label for="stock">Stock</label>
                        <input type="number" name="stock" id="stock" class="form-control" value="{{ $warehouse->stock }}" required min="0" step="0.01">
                    </div>


                    <div class="col-md-4 form-group">
                        <label for="sale_price">Sale Price</label>
                        <input type="number" name="sale_price" id="sale_price" class="form-control" value="{{ $warehouse->sale_price }}" required min="0" step="0.01">
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="retail_price">Retail Price</label>
                        <input type="number" name="retail_price" id="sale_price" class="form-control" required min="0" step="0.01" value="{{ $warehouse->retail_price }}">
                    </div>

                    <div class="col-md-4 form-group">
                        <label for="dealer_price">Dealer Price</label>
                        <input type="number" name="dealer_price" id="dealer_price" class="form-control" required min="0" step="0.01" value="{{ $warehouse->dealer_price }}">
                    </div>

                    <div class="col-md-4 form-group">
                        <label for="commission_agent_price">Commission Agent Price</label>
                        <input type="number" name="commission_agent_price" id="commission_agent_price" class="form-control" required min="0" step="0.01" value="{{ $warehouse->commission_agent_price }}">
                    </div>

                    <div class="col-md-4 form-group">
                        <label for="retailer_price">Retailer Price</label>
                        <input type="number" name="retailer_price" id="retailer_price" class="form-control" required min="0" step="0.01" value="{{ $warehouse->retailer_price }}">
                    </div>

                    <div class="col-md-4 form-group">
                        <label for="wholesale_price">Wholesale Price</label>
                        <input type="number" name="wholesale_price" id="wholesale_price" class="form-control" required min="0" step="0.01" value="{{ $warehouse->wholesale_price }}">
                    </div>
                    <div class="col-md-12 form-group">
                        <button type="submit" class="btn btn-success">Update Product</button>
                        <a href="{{ route('warehouses.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </div>
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
