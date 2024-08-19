@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ $asset->name }}</h1>
        <p>Description: {{ $asset->description }}</p>
        <p>Value: {{ $asset->value }}</p>
        <p>Purchase Date: {{ $asset->purchase_date }}</p>
        <a href="{{ route('assets.index') }}" class="btn btn-primary">Back to List</a>
    </div>
@endsection
