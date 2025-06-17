<?php

namespace App\Policies;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LeadPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, Lead $lead)
    {
        return $user->role === 'admin' || $lead->assigned_to === $user->id;
    }

    public function create(User $user)
    {
        return $user->role === 'admin';
    }

    public function update(User $user, Lead $lead)
    {
        return $user->role === 'admin' || $lead->assigned_to === $user->id;
    }

    public function delete(User $user, Lead $lead)
    {
        return $user->role === 'admin';
    }
} 