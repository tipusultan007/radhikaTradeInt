@extends('layouts.app')
@section('title','Add New Asset')
@section('create-button')
    <a href="{{ route('assets.index') }}" class="btn btn-secondary">Back to Asset List</a>
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('assets.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="description" class="form-control"></textarea>
                </div>
                <div class="form-group">
                    <label for="value">Value</label>
                    <input type="number" name="value" class="form-control" step="0.01" required>
                </div>
                <div class="form-group">
                    <label for="">Account</label>
                    <select name="account_id" id="account_id" class="select2">
                        <option value=""></option>
                        @forelse($accounts as $account)
                            <option value="{{ $account->id }}">{{ $account->name }}</option>
                        @empty
                            <option value="" disabled>No payment method found!</option>
                        @endforelse
                    </select>
                </div>
                <div class="form-group">
                    <label for="purchase_date">Purchase Date</label>
                    <input type="text" name="purchase_date" class="form-control flatpickr" value="{{ date('Y-m-d') }}" required>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Add Asset</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $(".select2").select2({
            theme:'bootstrap',
            width: '100%',
            placeholder: 'Select Account'
        });
    </script>
@endsection
