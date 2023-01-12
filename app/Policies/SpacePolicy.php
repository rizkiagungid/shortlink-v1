<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Space;
use Illuminate\Auth\Access\HandlesAuthorization;

class SpacePolicy
{
    use HandlesAuthorization;
    
    /**
     * Determine whether the user can view any spaces.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the space.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Space  $space
     * @return mixed
     */
    public function view(User $user, Space $space)
    {
        //
    }

    /**
     * Determine whether the user can create spaces.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        if ($user->plan->features->spaces == -1) {
            return true;
        } elseif($user->plan->features->spaces > 0) {
            $count = Space::where('user_id', '=', $user->id)->count();

            if ($count < $user->plan->features->spaces) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine whether the user can update the space.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Space  $space
     * @return mixed
     */
    public function update(User $user, Space $space)
    {
        //
    }

    /**
     * Determine whether the user can delete the space.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Space  $space
     * @return mixed
     */
    public function delete(User $user, Space $space)
    {
        //
    }

    /**
     * Determine whether the user can restore the space.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Space  $space
     * @return mixed
     */
    public function restore(User $user, Space $space)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the space.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Space  $space
     * @return mixed
     */
    public function forceDelete(User $user, Space $space)
    {
        //
    }
}
