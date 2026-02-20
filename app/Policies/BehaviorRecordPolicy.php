<?php

namespace App\Policies;

use App\Models\BehaviorRecord;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BehaviorRecordPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['Admin', 'Teacher', 'Parent']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, BehaviorRecord $behaviorRecord): bool
    {
        // Admin can view all
        if ($user->hasRole('Admin')) {
            return true;
        }

        // Teacher can view their own records
        if ($user->hasRole('Teacher') && $behaviorRecord->teacher_id === $user->id) {
            return true;
        }

        // Parent can view their children's records
        if ($user->hasRole('Parent')) {
            return $behaviorRecord->child->parent_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole(['Admin', 'Teacher']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, BehaviorRecord $behaviorRecord): bool
    {
        // Admin can update all
        if ($user->hasRole('Admin')) {
            return true;
        }

        // Teacher can update their own records
        if ($user->hasRole('Teacher') && $behaviorRecord->teacher_id === $user->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, BehaviorRecord $behaviorRecord): bool
    {
        // Only admin can delete
        if ($user->hasRole('Admin')) {
            return true;
        }

        // Teacher can delete their own records within 24 hours
        if ($user->hasRole('Teacher') && $behaviorRecord->teacher_id === $user->id) {
            return $behaviorRecord->created_at->diffInHours(now()) < 24;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, BehaviorRecord $behaviorRecord): bool
    {
        return $user->hasRole('Admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, BehaviorRecord $behaviorRecord): bool
    {
        return $user->hasRole('Admin');
    }
}
