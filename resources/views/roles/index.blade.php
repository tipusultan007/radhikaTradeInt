<!-- resources/views/roles/index.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <h2>Roles</h2>

        <a href="{{ route('roles.create') }}" class="btn btn-primary mb-3">Create New Role</a>

        <table class="table table-striped">
            <thead>
            <tr>
                <th>Role Name</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($roles as $role)
                <tr>
                    <td>{{ $role->name }}</td>
                    <td>
                        <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <a href="{{ route('roles.edit-permissions', $role->id) }}" class="btn btn-sm btn-secondary">Edit Permissions</a>
                        <form action="{{ route('roles.destroy', $role->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this role?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
