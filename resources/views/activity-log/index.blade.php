{{-- resources/views/activity-log/index.blade.php --}}
@extends('layouts.app')
@section('title','Activity Log')
@section('content')
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Action</th>
            <th>Description</th>
            <th>Date</th>
            <th>Causer</th>
            <th>Action</th> {{-- New column for action links --}}
        </tr>
        </thead>
        <tbody>
        @foreach($activityLogs as $log)
            <tr>
                <td>
                    @php
                        $badgeClass = '';
                        switch ($log->event) {
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
                    <span class="{{ $badgeClass }}">{{ ucfirst($log->event) }}</span>
                </td>
                <td>{{ $log->description }}</td>
                <td>{{ $log->created_at->format('d/m/Y h:i:s A') }}</td>
                <td>{{ $log->causer ? $log->causer->name : 'System' }}</td>
                <td>
                    {{-- Create a link to the details page --}}
                    <a href="{{ route('activity.log.details', $log->id) }}" class="btn btn-primary btn-sm">
                        View
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="pagination">
        {{ $activityLogs->links() }}  <!-- This will generate the pagination links -->
    </div>
@endsection
