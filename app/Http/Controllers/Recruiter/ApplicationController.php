<?php

namespace App\Http\Controllers\Recruiter;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateApplicationStatusRequest;
use App\Models\Application;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ApplicationController extends Controller
{
    public function index(string $locale, Job $job)
    {
        $this->authorize('view', $job);

        $applications = $job->applications()
            ->with(['user.candidateProfile'])
            ->latest()
            ->paginate(20);

        return view('recruiter.applications.index', compact('job', 'applications'));
    }

    public function update(UpdateApplicationStatusRequest $request, string $locale, Application $application)
    {
        $this->authorize('update', $application);

        $previousStatus = $application->status;
        $application->applyStatusUpdate($request->validated());

        // Send notification if status changed
        if ($previousStatus != $application->status) {
            $application->user->notify(
                new \App\Notifications\ApplicationStatusUpdatedNotification($application)
            );
        }

        return back()->with('success', 'Application status updated successfully!');
    }

    public function downloadResume(string $locale, Application $application)
    {
        $this->authorize('view', $application);

        $profile = $application->user->candidateProfile;

        if (!$profile || !$profile->resume_path) {
            return back()->with('error', 'Resume not found.');
        }

        return Storage::disk('public')->download($profile->resume_path);
    }
}

