@extends('layouts.app')
@section('title','Edit User')
@section('create-button')
    <a href="{{ route('users.index') }}" class="btn btn-secondary">Back to List</a>
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
            <form action="{{ route('users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-4 form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                    </div>

                    <div class="col-md-4 form-group">
                        <label for="phone">Phone</label>
                        <input type="text" name="phone" class="form-control" value="{{ $user->phone }}" required>
                    </div>

                    <div class="col-md-4 form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ $user->email }}">
                    </div>

                    <div class="col-md-4 form-group">
                        <label for="address">Address</label>
                        <input type="text" name="address" class="form-control" value="{{ $user->address }}">
                    </div>

                    <div class="col-md-4 form-group">
                        <label for="password">Password (leave blank to keep current password)</label>
                        <input type="password" name="password" class="form-control">
                    </div>

                    <div class="col-md-4 form-group">
                        <label for="password_confirmation">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>

                    <div class="col-md-12 form-group">
                        <button type="submit" class="btn btn-success">Update User</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
@endsection
