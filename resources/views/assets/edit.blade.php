@extends('layouts.app')
@section('title','Edit Asset')
@section('create-button')
    <a href="{{ route('assets.index') }}" class="btn btn-secondary">Back to Asset List</a>
@endsection
@section('content')
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    <div class="card">
        <div class="card-body">
            <form action="{{ route('assets.update', $asset->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" name="name" class="form-control" value="{{ $asset->name }}" required>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="description" class="form-control">{{ $asset->description }}</textarea>
                </div>
                <div class="form-group">
                    <label for="value">Value</label>
                    <input type="number" name="value" class="form-control" value="{{ $asset->value }}" step="0.01" required>
                </div>
                <div class="form-group">
                    <label for="">Account</label>
                    <select name="account_id" id="account_id" class="select2">
                        <option value=""></option>
                        @forelse($accounts as $account)
                            <option value="{{ $account->id }}" {{ $account->id == $asset->account_id?'selected':'' }}>{{ $account->name }}</option>
                        @empty
                            <option value="" disabled>No payment method found!</option>
                        @endforelse
                    </select>
                </div>
                <div class="form-group">
                    <label for="purchase_date">Purchase Date</label>
                    <input type="text" name="purchase_date" class="form-control flatpickr" value="{{ $asset->purchase_date }}" required>
                </div>
               <div class="form-group">
                   <button type="submit" class="btn btn-primary">Update Asset</button>
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
