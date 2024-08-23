<!-- resources/views/roles/create.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Create a New Role</h2>

        <form action="{{ route('roles.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="role">Role Name</label>
                <input type="text" class="form-control" id="role" name="role" required>
            </div>

            <div class="form-group">
                <label for="permissions">Assign Permissions</label>
                <div class="checkbox-list">
                    @foreach($permissions as $permission)
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="permission_{{ $permission->id }}" name="permissions[]" value="{{ $permission->name }}">
                            <label class="form-check-label" for="permission_{{ $permission->id }}">{{ $permission->name }}</label>
                        </div>
                    @endforeach
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Create Role</button>
        </form>
    </div>
@endsection
