@extends('layouts.app')
@section('title','Assets')
@section('create-button')
    <a href="{{ route('assets.create') }}" class="btn btn-primary">Add Asset</a>
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Value</th>
                    <th>Purchase Date</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($assets as $asset)
                    <tr>
                        <td>{{ $asset->name }}</td>
                        <td>{{ $asset->description }}</td>
                        <td>{{ $asset->value }}</td>
                        <td>{{ $asset->purchase_date }}</td>
                        <td>
                            <a href="{{ route('assets.show', $asset->id) }}" class="btn btn-info btn-sm">View</a>
                            <a href="{{ route('assets.edit', $asset->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('assets.destroy', $asset->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
