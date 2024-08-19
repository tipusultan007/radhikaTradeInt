@extends('layouts.app')
@section('title','Users')
@section('create-button')
    <a href="{{ route('users.create') }}" class="btn btn-primary">Create New User</a>
@endsection
@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Name</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Address</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
            <tr id="user-row-{{ $user->id }}">
                <td>{{ $user->name }}</td>
                <td>{{ $user->phone }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->address }}</td>
                <td>
                    <a href="{{ route('users.show', $user->id) }}" class="btn btn-info btn-sm">View</a>
                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <button class="btn btn-danger btn-sm" data-id="{{ $user->id }}" onclick="confirmDelete({{ $user->id }})">Delete</button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
@section('js')
    <script>
        function confirmDelete(userId) {
            swal({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                type: "warning",
                buttons: {
                    confirm: {
                        text: "Yes, delete it!",
                        className: "btn btn-success",
                    },
                    cancel: {
                        visible: true,
                        className: "btn btn-danger",
                    },
                },
            }).then((result) => {
                if (result) {  // Check for result instead of result.isConfirmed
                    $.ajax({
                        url: '/users/' + userId,
                        type: 'DELETE',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            swal({
                                title: "Deleted!",
                                text: response.message,
                                icon: "success",
                                buttons: false,
                                timer: 1500
                            });

                            // Remove the row from the table
                            $('#user-row-' + userId).remove();
                        },
                        error: function(xhr) {
                            swal({
                                title: "Error!",
                                text: xhr.responseJSON.message,
                                icon: "error",
                            });
                        }
                    });
                }
            });
        }


    </script>
@endsection
