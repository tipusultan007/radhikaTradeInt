@extends('layouts.app')
@section('title','Create User')
@section('create-button')
    <a href="{{ route('users.index') }}" class="btn btn-secondary">Back to User List</a>
@endsection
@section('content')
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="card">
        <div class="card-body">
            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-3 form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                    </div>

                    <div class="col-md-3 form-group">
                        <label for="phone">Phone</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required>
                    </div>

                    <div class="col-md-3 form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                    </div>

                    <div class="col-md-3 form-group">
                        <label for="address">Address</label>
                        <input type="text" name="address" class="form-control" value="{{ old('address') }}">
                    </div>

                    <div class="col-md-3 form-group">
                        <label for="basic_salary">Basic Salary</label>
                        <input type="text" name="basic_salary" class="form-control" value="{{ old('basic_salary') }}">
                    </div>

                    <div class="col-md-3 form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <div class="col-md-3 form-group">
                        <label for="password_confirmation">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>

                    <div class="col-md-3 form-group d-flex align-items-end">
                        <button type="submit" class="btn btn-success">Create User</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
@endsection
