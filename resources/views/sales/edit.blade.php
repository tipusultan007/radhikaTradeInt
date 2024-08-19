@extends('layouts.app')
@section('title','Edit Sale')
@section('content')
    <form action="{{ route('sales.update', $sale->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-3 form-group">
                <label for="date" class="form-label">Date</label>
                <input type="text" name="date" class="form-control flatpickr" value="{{ old('date', $sale->date) }}">
            </div>
            <div class="col-md-4 form-group">
                <label for="customer_id">Customer</label>
                <select name="customer_id" id="customer_id" class="form-select select2" required>
                    <option value=""></option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}" {{ $customer->id == old('customer_id', $sale->customer_id) ? 'selected' : '' }}>
                            {{ $customer->name }} - {{ strtoupper($customer->type) }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <h3>Sale Items</h3>

        <table class="table table-sm" id="sale-items-table">
            <thead>
            <tr>
                <th>Product</th>
                <th>Packaging Type</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody id="sale-items">
            @foreach($sale->details as $index => $detail)
                <tr class="sale-item">
                    <td>
                        <select name="items[{{ $index }}][product_id]" class="form-control select2" required>
                            <option value="">Select Product</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" {{ $product->id == $detail->product_id ? 'selected' : '' }}>
                                    {{ $product->name }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select name="items[{{ $index }}][packaging_type_id]" class="form-control select2" required>
                            <option value="">Package</option>
                            @foreach($packagingTypes as $packagingType)
                                <option value="{{ $packagingType->id }}" {{ $packagingType->id == $detail->packaging_type_id ? 'selected' : '' }}>
                                    {{ $packagingType->type }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td style="width: 200px">
                        <input type="number" name="items[{{ $index }}][quantity]" class="form-control" value="{{ $detail->quantity }}" required>
                    </td>
                    <td style="width: 200px">
                        <input type="number" name="items[{{ $index }}][price]" class="form-control" value="{{ $detail->price }}" required>
                    </td>
                    <td style="width: 105px">
                        <button type="button" class="btn btn-secondary btn-icon add-item">
                            <i class="fa fa-plus"></i>
                        </button>
                        <button type="button" class="btn btn-icon btn-danger remove-item">
                            <i class="fa fa-minus"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="row justify-content-end">
            <div class="col-md-4">
                <table class="table">
                    <tr>
                        <th>Subtotal</th> <td><input type="text" name="subtotal" class="form-control subtotal" value="{{ $sale->subtotal }}"></td>
                    </tr>
                    <tr>
                        <th>Carrying Cost</th> <td><input type="text" name="carrying_cost" class="form-control carrying_cost" value="{{ $sale->carrying_cost }}"></td>
                    </tr>
                    <tr>
                        <th>Carrying Cost Bearer</th> <td>
                            <select name="carrying_cost_bearer" class="carrying_cost_bearer select2">
                                <option value="customer" {{ $sale->carrying_cost_bearer == 'customer' ? 'selected' : '' }}>Customer</option>
                                <option value="owner" {{ $sale->carrying_cost_bearer == 'owner' ? 'selected' : '' }}>Owner</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>Discount</th> <td><input type="text" name="discount" class="form-control discount" value="{{ $sale->discount }}"></td>
                    </tr>
                    <tr>
                        <th>Total</th> <td><input type="text" name="total" class="form-control total" value="{{ $sale->total }}"></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-success w-50">Update Sale</button>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </form>
@endsection

@section('js')
    <script>
        let itemCount = {{ $sale->details->count() }};

        $(document).on('click','.add-item', function() {
            const newRow = `
        <tr class="sale-item">
            <td>
                <select name="items[${itemCount}][product_id]" class="form-control select2" required>
                    <option value="">Product</option>
                    @foreach($products as $product)
            <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
            </select>
        </td>
        <td>
            <select name="items[${itemCount}][packaging_type_id]" class="form-control select2" required>
                    <option value="">Package</option>
                    @foreach($packagingTypes as $packagingType)
            <option value="{{ $packagingType->id }}">{{ $packagingType->type }}</option>
                    @endforeach
            </select>
        </td>
        <td>
            <input type="number" name="items[${itemCount}][quantity]" class="form-control" required>
            </td>
            <td>
                <input type="number" name="items[${itemCount}][price]" class="form-control" required>
            </td>
            <td style="width:105px">
                <button type="button" class="btn btn-secondary btn-icon add-item">
                    <i class="fa fa-plus"></i>
                </button>
                <button type="button" class="btn btn-danger btn-icon remove-item">
                    <i class="fa fa-minus"></i>
                </button>
            </td>
        </tr>
    `;

            $('#sale-items').append(newRow);

            // Initialize Select2 for the newly added selects
            $('.select2').select2({
                theme: 'bootstrap',
                width: '100%'
            });

            itemCount++;
        });

        // Event delegation for removing items
        $(document).on('click', '.remove-item', function() {
            const row = $(this).closest('tr');
            // Ensure that the first row is not deleted

            row.remove();
            calculateTotals();

        });

        function calculateTotals() {
            let subtotal = 0;

            // Iterate over each sale item row and sum the subtotal
            $('#sale-items .sale-item').each(function() {
                const quantity = parseFloat($(this).find('input[name^="items"][name$="[quantity]"]').val()) || 0;
                const price = parseFloat($(this).find('input[name^="items"][name$="[price]"]').val()) || 0;
                subtotal += quantity * price;
            });

            // Display subtotal
            $('.subtotal').val(subtotal.toFixed(2));

            // Calculate total
            const carryingCost = parseFloat($('.carrying_cost').val()) || 0;
            const discount = parseFloat($('.discount').val()) || 0;
            const carryingCostBearer = $('.carrying_cost_bearer').val();

            let total = subtotal;

            // Apply fixed discount
            total -= discount;

            // Add carrying cost if bearer is customer
            if (carryingCostBearer === 'customer') {
                total += carryingCost;
            }

            // Display total
            $('.total').val(total.toFixed(2));
        }

        // Calculate totals on input changes
        $(document).on('input', '#sale-items input[name^="items"][name$="[quantity]"], #sale-items input[name^="items"][name$="[price]"], .carrying_cost, .discount', calculateTotals);
        $(document).on('change', '.carrying_cost_bearer', calculateTotals);

        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
        };
        // When product or packaging type changes
        $(document).on('change', 'select[name^="items"]', function() {
            var $row = $(this).closest('tr');
            var product_id = $row.find('select[name$="[product_id]"]').val();
            var packaging_type_id = $row.find('select[name$="[packaging_type_id]"]').val();

            if (product_id && packaging_type_id) {
                $.ajax({
                    url: '{{ route("warehouse.info") }}',
                    method: 'GET',
                    data: {
                        product_id: product_id,
                        packaging_type_id: packaging_type_id
                    },
                    success: function(response) {
                        $row.find('input[name$="[price]"]').val(response.sale_price);
                        $row.find('input[name$="[quantity]"]').attr('max', response.stock);

                        if (response.stock <= 0) {
                            //alert('Selected product is out of stock!');
                            toastr.warning('Selected product is out of stock!');
                        }else {
                            $row.find('input[name$="[quantity]"]').val(1);
                            calculateTotals();
                        }
                    },
                    error: function() {
                        //alert('Product or packaging type not found in warehouse.');
                        toastr.error('Product or packaging type not found in warehouse.');
                        $row.find('input[name$="[price]"]').val(0);
                        $row.find('input[name$="[quantity]"]').val(0).attr('max', '');
                        calculateTotals();
                    }
                });
            }
        });
    </script>
    <script>
        $(".select2").select2({
            theme: "bootstrap",
            width: "100%",
            placeholder: " -- Select --"
        });
    </script>
@endsection

