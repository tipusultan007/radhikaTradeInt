@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Create a New Permission</h2>

        <form action="{{ route('permissions.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="name">Permission Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>

            <button type="submit" class="btn btn-primary">Create Permission</button>
        </form>
    </div>
@endsection
