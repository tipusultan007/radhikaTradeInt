@extends('layouts.app')
@section('title','Edit Payroll')
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
            <form action="{{ route('payroll.update', $payroll->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-4 form-group">
                        <label for="user_id">User</label>
                        <input type="text"  class="form-control" value="{{ $payroll->user->name }}" readonly>
                        <input type="hidden" name="user_id" value="{{ $payroll->user_id }}">
                    </div>

                    <div class="col-md-4 form-group">
                        <label for="salary">Basic Salary</label>
                        <input type="text" id="salary" class="form-control" value="{{ $payroll->salary }}" readonly>
                    </div>

                    <div class="col-md-4 form-group">
                        <label for="bonus">Bonus</label>
                        <input type="number" name="bonus" id="bonus" class="form-control" value="{{ old('bonus', $payroll->bonus) }}">
                    </div>

                    <div class="col-md-4 form-group">
                        <label for="deductions">Deductions</label>
                        <input type="number" name="deductions" id="deductions" class="form-control" value="{{ old('deductions', $payroll->deductions) }}">
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="advance">Advanced</label>
                        <input type="number" name="advance" id="advance" class="form-control" value="{{ old('advance', $payroll->advance) }}" readonly>
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="total">Total Salary</label>
                        <input type="text" id="total" class="form-control" value="{{ $payroll->net_pay }}" readonly>
                    </div>
                    @php
                        $accounts = \App\Models\Account::where('type','asset')
                        ->whereNotIn('id',[3,4])->get();
                    @endphp
                    <div class="col-md-4 form-group">
                        <label for="">Account</label>
                        <select name="account_id" id="account_id" class="select2">
                            @forelse($accounts as $account)
                                <option value="{{ $account->id }}" {{ $account->id === $payroll->account_id?'selected':'' }}>{{ $account->name }}</option>
                            @empty
                            @endforelse
                        </select>
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="pay_date">Pay Date</label>
                        <input type="text" name="pay_date" id="pay_date" class="form-control flatpickr" value="{{ $payroll->pay_date }}" required>
                    </div>

                    <div class="col-md-4 form-group">
                        <label for="month">Month</label>
                        <input type="month" name="month" id="month" class="form-control" value="{{ $payroll->month }}" required>
                    </div>

                    <div class="col-md-4 form-group d-flex align-items-end">
                        <button type="submit" class="btn btn-primary">Update Payroll</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function() {
            $(".select2").select2({
                theme:'bootstrap',
                width: '100%'
            })
            const $userSelect = $('#user_id');
            const $salaryInput = $('#salary');
            const $bonusInput = $('#bonus');
            const $advanceInput = $('#advance');
            const $deductionsInput = $('#deductions');
            const $totalInput = $('#total');

            // Set the initial total salary
            //updateTotal(parseFloat($salaryInput.val()));

            // Update total salary on bonus or deductions input change
            $bonusInput.on('input', function() {
                const salary = parseFloat($salaryInput.val()) || 0;
                const bonus = parseFloat($(this).val()) || 0;
                const deductions = parseFloat($deductionsInput.val()) || 0;
                const advance = parseFloat($advanceInput.val()) || 0;

                updateTotal(salary, bonus, deductions, advance);
            });

            $deductionsInput.on('input', function() {
                const salary = parseFloat($salaryInput.val()) || 0;
                const bonus = parseFloat($bonusInput.val()) || 0;
                const deductions = parseFloat($(this).val()) || 0;
                const advance = parseFloat($advanceInput.val()) || 0;

                updateTotal(salary, bonus, deductions, advance);
            });

            function updateTotal(salary, bonus = 0, deductions = 0, advance = 0) {
                const total = salary + bonus - deductions - advance;
                $totalInput.val(total.toFixed(2)); // Format to two decimal places
            }
        });
    </script>
@endsection
