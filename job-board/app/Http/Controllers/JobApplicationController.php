<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\JobApplication;
use App\Policies\JobApplicationPolicy;
use Illuminate\Http\Request;

class JobApplicationController extends Controller
{
    public function __construct()
    {
        // $this->authorizeResource(JobApplication::class, 'jobApplication');
    }


    public function create(Job $job)
    {
        $this->authorize('create', [JobApplication::class, $job]);
        return view('job_application.create', ['job' => $job]);
    }

    public function store(Job $job, Request $request)
    {

        $validData = $request->validate([
            'expected_salary' => 'required|min:1',
            'cv' => 'required|file|mimes:pdf|max:2048'
        ]);

        $file = $request->file('cv');
        $path = $file->store('cvs', 'private');

        $job->jobApplications()->create([
            'expected_salary' => $validData['expected_salary'],
            'user_id' => $request->user()->id,
            'cv_path' => $path
        ]);

        return redirect()->route('jobs.show', $job)->with('success', 'Job Application Submitted.');
    }


    public function destroy(Job $job)
    {
        //
    }
}
