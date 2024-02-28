<?php

namespace App\Http\Controllers;

use App\Http\Requests\JobRequest;
use App\Models\Job;
use Illuminate\Http\Request;

class MyJobController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $this->authorize('viewAnyEmployer', Job::class);
        return view('my_job.index', [
            'jobs' => auth()->user()->employer->jobs()
                ->with(['employer', 'jobApplications.user'])
                ->withTrashed()
                ->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $this->authorize('create', Job::class);
        return view('my_job.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(JobRequest $request)
    {

        $this->authorize('create', Job::class);
        $validData = $request->validated();

        auth()->user()->employer->jobs()->create($validData);

        return redirect()->route('my-jobs.index')->with('success', 'Job Created Successfully');
    }

    public function edit(Job $myJob)
    {
        $this->authorize('update', $myJob);
        return view('my_job.edit', ['job' => $myJob]);
    }

    public function update(JobRequest $request, Job $myJob)
    {
        $this->authorize('update', $myJob);
        $myJob->update($request->validated());
        return redirect()->route('my-jobs.index')
            ->with('success', 'job Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Job $myJob)
    {
        $myJob->delete();

        return redirect()->route('my-jobs.index')->with('success', 'job deleted');
    }
}
