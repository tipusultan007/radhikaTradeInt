@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Edit Permission: {{ $permission->name }}</h2>

        <form action="{{ route('permissions.update', $permission->id) }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="name">Permission Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $permission->name }}" required>
            </div>

            <button type="submit" class="btn btn-primary">Update Permission</button>
        </form>
    </div>
@endsection
