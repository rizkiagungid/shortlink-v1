<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Domain;
use Illuminate\Auth\Access\HandlesAuthorization;

class DomainPolicy
{
    use HandlesAuthorization;
    
    /**
     * Determine whether the user can view any domains.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the domain.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Domain  $domain
     * @return mixed
     */
    public function view(User $user, Domain $domain)
    {
        //
    }

    /**
     * Determine whether the user can create domains.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        if ($user->plan->features->domains == -1) {
            return true;
        } elseif($user->plan->features->domains > 0) {
            $count = Domain::where('user_id', '=', $user->id)->count();

            if ($count < $user->plan->features->domains) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine whether the user can update the domain.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Domain  $domain
     * @return mixed
     */
    public function update(User $user, Domain $domain)
    {
        //
    }

    /**
     * Determine whether the user can delete the domain.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Domain  $domain
     * @return mixed
     */
    public function delete(User $user, Domain $domain)
    {
        //
    }

    /**
     * Determine whether the user can restore the domain.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Domain  $domain
     * @return mixed
     */
    public function restore(User $user, Domain $domain)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the domain.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Domain  $domain
     * @return mixed
     */
    public function forceDelete(User $user, Domain $domain)
    {
        //
    }
}
