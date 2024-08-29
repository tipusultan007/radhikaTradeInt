@extends('layouts.app')
@section('title','Advance Salaries')
@section('content')

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('advance_salary.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="month">Month</label>
                        <input type="month" name="month" id="month" class="form-control" value="{{ date('Y-m') }}">
                    </div>
                    <div class="form-group">
                        <label for="user_id">User</label>
                        <select name="user_id" id="user_id" class="form-control" required>
                            <option value="" disabled selected>Select a user</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="px-2" id="salaryInfo"></div>
                    <div class="form-group">
                        <label for="taken_on">Date</label>
                        <input type="text" name="taken_on" id="taken_on" class="form-control flatpickr" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="amount">Amount</label>
                        <input type="number" name="amount" id="amount" class="form-control" value="{{ old('amount') }}" required>
                    </div>


                    <div class="form-group">
                        <label for="account_id">Account</label>
                        <select name="account_id" id="account_id" class="form-control" required>
                            <option value="" disabled selected>Select an account</option>
                            @foreach($accounts as $account)
                                <option value="{{ $account->id }}">{{ $account->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>User</th>
                            <th>Amount</th>
                            <th>Month</th>
                            <th>Taken On</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($advanceSalaries as $advanceSalary)
                            <tr>
                                <td>{{ $advanceSalary->user->name }}</td>
                                <td>{{ $advanceSalary->amount }}</td>
                                <td>{{ \Carbon\Carbon::parse($advanceSalary->month)->format('F-Y') }}</td>
                                <td>{{ $advanceSalary->taken_on->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('advance_salary.edit', $advanceSalary->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                    <form action="{{ route('advance_salary.destroy', $advanceSalary->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this advance salary?');">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {{ $advanceSalaries->links() }}
                </div>


            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
    <script>
        $("select").select2({
            theme: 'bootstrap',
            width: '100%'
        })

        const $userSelect = $('#user_id');
        const $monthInput = $('#month');

        function getSalaryAndAdvance() {
            const userId = $userSelect.val();
            const month = $monthInput.val();

            $("#salaryInfo").html('');

            if (userId && month) {
                $.ajax({
                    url: '{{ route('get.salary') }}', // Update with your route for getting salary
                    method: 'GET',
                    data: {
                        user_id: userId,
                        month: month
                    },
                    success: function(response) {
                        if (response.is_paid === 'yes') {
                            $("#salaryInfo").html(`
                        <table class="table table-bordered">
                            <tr>
                                <td class="text-danger">Salary of <b>${response.month}</b> has already been paid!</td>
                            </tr>
                        </table>
                        `);
                        }else {
                            $("#salaryInfo").html(`
                        <table class="table table-bordered">
                            <tr>
                                <th>Salary Month</th><td>${response.month}</td>
                            </tr>
                            <tr>
                                <th>Salary</th><td>${response.salary}</td>
                            </tr>
                            <tr>
                                <th>Advance</th><td>${response.advance}</td>
                            </tr>
                        </table>
                        `);
                        }
                        $salaryInput.val(response.salary);
                        $advanceInput.val(response.advance);
                        $totalInput.val(response.salary); // Initialize total with salary

                    },
                    error: function(xhr, status, error) {
                        toastr.error('Error fetching salary information. Please try again.');
                    }
                });
            }
        }

        $userSelect.on('change', getSalaryAndAdvance);
        $monthInput.on('change', getSalaryAndAdvance);
    </script>
@endsection
