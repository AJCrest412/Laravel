<?php

namespace App\Policies;

use App\Models\Job;
use App\Models\JobApplication;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class JobApplicationPolicy
{
    public function create(User $user, Job $job): bool
    {
        return !$job->jobApplications()->where('user_id', $user->id)->exists();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, JobApplication $jobApplication): bool
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, JobApplication $jobApplication): bool
    {
        return true;
    }
}
