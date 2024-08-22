@extends('layouts.app')
@section('title','User Details')
@section('create-button')
    <a href="{{ route('users.index') }}" class="btn btn-secondary">Back to Users</a>
@endsection
@section('content')
    <div class="card mb-3">
        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <th>Name</th>
                    <td>{{ $user->name }}</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>{{ $user->email }}</td>
                </tr>
                <tr>
                    <th>Address</th>
                    <td>{{ $user->address }}</td>
                </tr>
                <tr>
                    <th>Phone</th>
                    <td>{{ $user->phone }}</td>
                </tr>
                <tr>
                    <th>Basic Salary</th>
                    <td>৳ {{ number_format($user->salary, 2) }}</td>
                </tr>
            </table>
        </div>
    </div>

   <div class="d-flex justify-content-between">
       <h2>Salary Increments</h2>
       <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addIncrementModal">
           Add Salary Increment
       </button>
   </div>

    <div class="table-responsive mb-4">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Increment Amount</th>
                <th>New Salary</th>
                <th>Increment Date</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($user->salaryIncrements as $increment)
                <tr>
                    <td>৳ {{ number_format($increment->amount, 2) }}</td>
                    <td>৳ {{ number_format($increment->new_salary, 2) }}</td>
                    <td>{{ $increment->increment_date->format('d/m/Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">No salary increments found.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
@php
$accounts = \App\Models\Account::where('type','asset')->whereNotIn('id',[3,4])->get();
 @endphp
    <div class="d-flex justify-content-between">
        <h2>Payroll Records</h2>
        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addPayrollModal">
            Add Payroll
        </button>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Month</th>
                <th>Salary</th>
                <th>Bonus</th>
                <th>Deductions</th>
                <th>Net Pay</th>
                <th>Pay Date</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($user->payrolls as $payroll)
                <tr>
                    <td>{{ $payroll->month }}</td>
                    <td>৳ {{ number_format($payroll->salary, 2) }}</td>
                    <td>৳ {{ number_format($payroll->bonus, 2) }}</td>
                    <td>৳ {{ number_format($payroll->deductions, 2) }}</td>
                    <td>৳ {{ number_format($payroll->net_pay, 2) }}</td>
                    <td>{{ $payroll->pay_date->format('d/m/Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">No payroll records found.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <a href="{{ route('users.index') }}" class="btn btn-secondary">Back to Users</a>

    <!-- Modal -->
    <!-- Modal -->
    <div class="modal fade" id="addIncrementModal" tabindex="-1" aria-labelledby="addIncrementModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addIncrementModalLabel">Add Salary Increment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addIncrementForm" action="{{ route('salary-increments.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ $user->id }}">

                        <div class="mb-3">
                            <label for="amount" class="form-label">Increment Amount</label>
                            <input type="number" class="form-control" id="amount" name="amount" required>
                        </div>

                        <div class="mb-3">
                            <label for="new_salary" class="form-label">New Salary</label>
                            <input type="number" class="form-control" id="new_salary" name="new_salary" value="{{ $user->salary }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="increment_date" class="form-label">Increment Date</label>
                            <input type="text" class="form-control flatpickr" value="{{ date('Y-m-d') }}" id="increment_date" name="increment_date" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" form="addIncrementForm">Save Increment</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Payroll Modal -->
    <div class="modal fade" id="addPayrollModal" tabindex="-1" aria-labelledby="addPayrollModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPayrollModalLabel">Add Payroll</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addPayrollForm" action="{{ route('payroll.store') }}" class="row g-2" method="POST">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ $user->id }}">

                        <div class="col-md-6 mb-3">
                            <label for="salary" class="form-label">Basic Salary</label>
                            <input type="number" class="form-control" id="salary" name="salary" value="{{ $user->salary }}" readonly>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="bonus" class="form-label">Bonus</label>
                            <input type="number" class="form-control" id="bonus" name="bonus" value="0">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="deductions" class="form-label">Deductions</label>
                            <input type="number" class="form-control" id="deductions" name="deductions" value="0">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="net_pay" class="form-label">Net Pay</label>
                            <input type="number" class="form-control" id="net_pay" name="net_pay" value="{{ $user->salary }}" readonly>
                        </div>

                        <div class="col-md-6 mb-3 position-relative">
                            <label for="account_id" class="form-label">Account</label>
                            <select class="form-control select2" id="account_id" name="account_id">
                                @foreach($accounts as $account)
                                    <option value="{{ $account->id }}">{{ $account->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="pay_date" class="form-label">Pay Date</label>
                            <input type="text" class="form-control flatpickr" id="pay_date" value="{{ date('Y-m-d') }}" name="pay_date" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="month" class="form-label">Month</label>
                            <input type="month" class="form-control" id="month" name="month" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" form="addPayrollForm">Save Payroll</button>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('js')
    <script>
        $('#addPayrollModal').on('shown.bs.modal', function () {
            $('#account_id').select2({
                dropdownParent: $('#addPayrollModal'),
                theme: 'bootstrap',
                width: '100%'
            });
        });


        document.addEventListener('DOMContentLoaded', function () {
            const amountInput = document.getElementById('amount');
            const newSalaryInput = document.getElementById('new_salary');
            const lastSalary = {{ $user->salary }};

            amountInput.addEventListener('input', function () {
                const incrementAmount = parseFloat(amountInput.value) || 0;
                const newSalary = lastSalary + incrementAmount;
                newSalaryInput.value = newSalary.toFixed(2); // Update the new salary field
            });
        });
        document.addEventListener('DOMContentLoaded', function () {
            const salaryInput = document.getElementById('salary');
            const bonusInput = document.getElementById('bonus');
            const deductionsInput = document.getElementById('deductions');
            const netPayInput = document.getElementById('net_pay');

            function calculateNetPay() {
                const salary = parseFloat(salaryInput.value) || 0;
                const bonus = parseFloat(bonusInput.value) || 0;
                const deductions = parseFloat(deductionsInput.value) || 0;
                const netPay = salary + bonus - deductions;
                netPayInput.value = netPay.toFixed(2); // Update the net pay field
            }

            bonusInput.addEventListener('input', calculateNetPay);
            deductionsInput.addEventListener('input', calculateNetPay);
        });

    </script>
@endsection
