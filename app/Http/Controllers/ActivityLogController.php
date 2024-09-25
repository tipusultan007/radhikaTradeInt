<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        // Fetch all activity logs
        $activityLogs = Activity::orderBy('created_at', 'desc')->paginate(50);

        return view('activity-log.index', compact('activityLogs'));
    }
    public function show($id)
    {
        // Fetch the activity log entry by ID
        $activityLog = Activity::findOrFail($id);

        // Return a view with the activity log details
        return view('activity-log.details', compact('activityLog'));
    }

}
