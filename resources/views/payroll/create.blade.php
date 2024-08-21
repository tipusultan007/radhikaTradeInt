@extends('layouts.app')
@section('title','Create Payroll')
@section('create-button')
    <a href="{{ route('payroll.index') }}" class="btn btn-secondary">Back to Payroll</a>
@endsection
@section('content')
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    <div class="card">
        <div class="card-body">
            <form action="{{ route('payroll.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-4 form-group">
                        <label for="user_id">User</label>
                        <select name="user_id" id="user_id" class="select2" required>
                            <option value="">Select a user</option>
                            @forelse($users as $user)
                                <option data-salary="{{ $user->getLastIncrementedSalary() }}" value="{{ $user->id }}" {{ old('user_id') === $user->id?'selected': '' }}>{{ $user->name }}</option>
                            @empty
                                <option value="">No users available</option>
                            @endforelse
                        </select>
                    </div>

                    <div class="col-md-4 form-group">
                        <label for="salary">Basic Salary</label>
                        <input type="text" id="salary" name="salary" class="form-control" value="{{ old('salary') }}" readonly>
                    </div>

                    <div class="col-md-4 form-group">
                        <label for="bonus">Bonus</label>
                        <input type="number" name="bonus" id="bonus" class="form-control" value="{{ old('bonus', 0) }}">
                    </div>

                    <div class="col-md-4 form-group">
                        <label for="deductions">Deductions</label>
                        <input type="number" name="deductions" id="deductions" class="form-control" value="{{ old('deductions', 0) }}">
                    </div>

                    <div class="col-md-4 form-group">
                        <label for="total">Total Salary</label>
                        <input type="text" id="total" class="form-control" name="total" value="{{ old('total') }}" readonly>
                    </div>

                    <div class="col-md-4 form-group">
                        <label for="pay_date">Pay Date</label>
                        <input type="text" name="pay_date" id="pay_date" class="form-control flatpickr" value="{{ old('pay_date') }}" required>
                    </div>

                    <div class="col-md-4 form-group">
                        <label for="month">Month</label>
                        <input type="month" name="month" id="month" class="form-control" value="{{ old('month') }}" required>
                    </div>

                    <div class="col-md-4 form-group d-flex align-items-end">
                        <button type="submit" class="btn btn-primary mt-3">Submit Payroll</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @section('js')
        <script>
            $(".select2").select2({
                theme:'bootstrap',
                width: '100%'
            })
            const $userSelect = $('#user_id');
            const $salaryInput = $('#salary');
            const $bonusInput = $('#bonus');
            const $deductionsInput = $('#deductions');
            const $totalInput = $('#total');

            $userSelect.on('change', function() {
                const salary = parseFloat($('option:selected', this).data('salary'));

                // Set the Basic Salary input to the selected user's salary
                $salaryInput.val(salary ? salary : '');

                // Update total salary when user changes
                updateTotal(salary);
            });

            // Update total salary on bonus or deductions input change
            $bonusInput.on('input', function() {
                const salary = parseFloat($salaryInput.val()) || 0;
                const bonus = parseFloat($(this).val()) || 0;
                const deductions = parseFloat($deductionsInput.val()) || 0;

                updateTotal(salary, bonus, deductions);
            });

            $deductionsInput.on('input', function() {
                const salary = parseFloat($salaryInput.val()) || 0;
                const bonus = parseFloat($bonusInput.val()) || 0;
                const deductions = parseFloat($(this).val()) || 0;

                updateTotal(salary, bonus, deductions);
            });

            function updateTotal(salary, bonus = 0, deductions = 0) {
                const total = salary + bonus - deductions;
                $totalInput.val(total.toFixed(2)); // Format to two decimal places
            }
        </script>
    @endsection
@endsection
