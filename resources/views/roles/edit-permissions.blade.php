<!-- resources/views/roles/edit-permissions.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Edit Permissions for Role: {{ $role->name }}</h2>

        <form action="{{ route('roles.update-permissions', $role->id) }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="permissions">Assign Permissions</label>
                <div class="checkbox-list">
                    @foreach($permissions as $permission)
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="permission_{{ $permission->id }}" name="permissions[]" value="{{ $permission->name }}"
                                   @if(in_array($permission->name, $rolePermissions)) checked @endif>
                            <label class="form-check-label" for="permission_{{ $permission->id }}">{{ $permission->name }}</label>
                        </div>
                    @endforeach
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Update Permissions</button>
        </form>
    </div>
@endsection
