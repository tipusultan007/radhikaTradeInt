@extends('layouts.app')
@section('title','Create Investment')
@section('content')

    <div class="card">
        <div class="card-body">
            <form action="{{ route('investments.store') }}" method="POST">
                @csrf

                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">

                <div class="form-group">
                    <label for="account_id">Investment Account</label>
                    <select name="account_id" id="account_id" class="form-control select2" required>
                        <option value="" disabled selected>Select an Account</option>
                        @foreach ($accounts as $account)
                            <option value="{{ $account->id }}" {{ old('account_id') == $account->id ? 'selected' : '' }}>
                                {{ $account->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('account_id')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="amount">Amount</label>
                    <input type="number" name="amount" id="amount" class="form-control" step="0.01" value="{{ old('amount') }}" required>
                    @error('amount')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <input type="text" name="description" id="description" class="form-control" value="{{ old('description') }}">
                    @error('description')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="date">Date</label>
                    <input type="text" name="date" id="date" class="form-control flatpickr" value="{{ old('date',date('Y-m-d')) }}" required>
                    @error('date')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Create Investment</button>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('js')
    <script>
        $(".select2").select2({
            theme: 'bootstrap',
            width: '100%'
        })
    </script>
@endsection
