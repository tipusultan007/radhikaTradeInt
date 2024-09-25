@extends('layouts.app')
@section('title', 'Activity Log Details')
@section('create-button')
    <a href="{{ route('activity.logs') }}" class="btn btn-secondary">Back to Activity Log</a>
@endsection
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th>Action</th>
                        <td>
                            @php
                                $badgeClass = '';
                                switch ($activityLog->event) {
                                    case 'created':
                                        $badgeClass = 'badge bg-success'; // Green for created
                                        break;
                                    case 'updated':
                                        $badgeClass = 'badge bg-warning text-dark'; // Yellow for updated
                                        break;
                                    case 'deleted':
                                        $badgeClass = 'badge bg-danger'; // Red for deleted
                                        break;
                                    default:
                                        $badgeClass = 'badge bg-secondary'; // Grey for other events
                                        break;
                                }
                            @endphp
                            <span class="{{ $badgeClass }}">{{ ucfirst($activityLog->event) }}</span>
                        </td>
                    </tr>
                    <tr>
                        <th>Description</th>
                        <td>{{ htmlspecialchars($activityLog->description) }}</td>
                    </tr>
                    <tr>
                        <th>Date</th>
                        <td>{{ $activityLog->created_at->format('d/m/Y H:i:s') }}</td>
                    </tr>
                    <tr>
                        <th>Causer</th>
                        <td>{{ $activityLog->causer ? htmlspecialchars($activityLog->causer->name) : 'System' }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-8">
                @if ($activityLog->properties && !empty($activityLog->properties))
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Field</th>
                            <th>Old Value</th>
                            <th>New Value</th>
                        </tr>
                        </thead>
                        {{--<tbody>
                        @foreach ($activityLog->properties['old'] as $key => $oldValue)
                            <tr>
                                <th>{{ htmlspecialchars(ucfirst($key)) }}</th>
                                <td>{{ htmlspecialchars($oldValue) }}</td>
                                <td>{{ htmlspecialchars($activityLog->properties['attributes'][$key] ?? '-') }}</td>
                            </tr>
                        @endforeach
                        </tbody>--}}
                        <tbody>
                        @if(isset($activityLog->properties['old']))
                            @foreach($activityLog->properties['old'] as $field => $oldValue)
                                <tr>
                                    <td>{{ ucfirst($field) }}</td>
                                    <td>{{ $oldValue }}</td>
                                    <td>{{ $activityLog->properties['attributes'][$field] ?? '-' }}</td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                @else
                    <p>No properties logged for this activity.</p>
                @endif
            </div>
        </div>
    </div>
@endsection
