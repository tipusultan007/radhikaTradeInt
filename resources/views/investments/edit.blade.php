@extends('layouts.app')
@section('title','Edit Investment')
@section('create-button')
    <a href="{{ route('investments.index') }}" class="btn btn-primary">Back to List</a>
@endsection
@section('content')

    <div class="card">
        <div class="card-body">
            <form action="{{ route('investments.update', $investment->id) }}" method="POST">
                @csrf
                @method('PUT')


                <input type="hidden" name="user_id" id="user_id" value="{{ $investment->user_id }}" required>
                <div class="form-group">
                    <label for="account_id">Investment Account</label>
                    <select name="account_id" id="account_id" class="form-control select2" required>
                        <option value="" disabled selected>Select an Account</option>
                        @foreach ($accounts as $account)
                            <option value="{{ $account->id }}" {{$investment->account_id == $account->id ? 'selected' : '' }}>
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
                    <input type="number" name="amount" id="amount" class="form-control" step="0.01" value="{{ $investment->amount }}" required>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <input type="text" name="description" id="description" class="form-control" value="{{ $investment->description }}">
                </div>

                <div class="form-group">
                    <label for="date">Date</label>
                    <input type="text" name="date" id="date" class="form-control flatpickr" value="{{ $investment->date->format('Y-m-d') }}" required>
                </div>

               <div class="form-group">
                   <button type="submit" class="btn btn-primary">Update Investment</button>
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
