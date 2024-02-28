<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Job::class);
        $filters = request()->only(
            'search',
            'max_salary',
            'min_salary',
            'experience',
            'category'
        );
        return view('job.index', ['jobs' => Job::with('employer')->latest()->filter($filters)->get()]); //without load employer its work auto lazy loading happening
    }

    public function show(Job $job)
    {
        $this->authorize('view', $job);
        return view('job.show', ['job' => $job->load('employer.jobs')]);  //here also load not needed we can use lazy loading
    }
}
