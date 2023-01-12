<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Link;
use Illuminate\Auth\Access\HandlesAuthorization;

class LinkPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any links.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the link.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Link  $link
     * @return mixed
     */
    public function view(User $user, Link $link)
    {
        //
    }

    /**
     * Determine whether the user can create links.
     *
     * @param \App\Models\User $user
     * @return mixed
     */
    public function create(User $user)
    {
        if ($user->plan->features->links == -1) {
            return true;
        } elseif($user->plan->features->links > 0) {
            // Set the count for multi links counter
            $mCount = 0;

            // If the request is for a multi links creation
            if (request()->input('multiple_links')) {
                // Get the links
                $links = preg_split('/\n|\r/', request()->input('urls'), -1, PREG_SPLIT_NO_EMPTY);

                // If the request contains more than one link
                if (count(preg_split('/\n|\r/', request()->input('urls'), -1, PREG_SPLIT_NO_EMPTY)) > 1) {

                    // Get the links count, and subtract 1 value, the remaining will be used to emulate the total links count against the limit
                    $mCount = (count($links)-1);
                }
            }

            $count = Link::where('user_id', '=', $user->id)->count();

            // If the total links count (including multi links, if any in the request) exceeds the limits
            if (($count+$mCount) < $user->plan->features->links) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine whether the user can update the link.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Link  $link
     * @return mixed
     */
    public function update(User $user, Link $link)
    {
        //
    }

    /**
     * Determine whether the user can delete the link.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Link  $link
     * @return mixed
     */
    public function delete(User $user, Link $link)
    {
        //
    }

    /**
     * Determine whether the user can restore the link.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Link  $link
     * @return mixed
     */
    public function restore(User $user, Link $link)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the link.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Link  $link
     * @return mixed
     */
    public function forceDelete(User $user, Link $link)
    {
        //
    }

    /**
     * Determine whether the user can use Spaces.
     *
     * @param User $user
     * @return bool
     */
    public function spaces(User $user)
    {
        return $user->plan->features->spaces;
    }

    /**
     * Determine whether the user can use Domains.
     *
     * @param User $user
     * @return bool
     */
    public function domains(User $user)
    {
        return $user->plan->features->domains;
    }

    /**
     * Determine whether the user can use Pixels.
     *
     * @param User $user
     * @return bool
     */
    public function pixels(User $user)
    {
        return $user->plan->features->pixels;
    }

    /**
     * Determine whether the user can see the Link Stats.
     *
     * @param ?User $user
     * @return bool
     */
    public function stats(?User $user)
    {
        return !$user || $user->plan->features->link_stats;
    }

    /**
     * Determine whether the user can Disable links.
     *
     * @param User $user
     * @return bool
     */
    public function disabled(User $user)
    {
        return $user->plan->features->link_disabling;
    }

    /**
     * Determine whether the user can use Targeting.
     *
     * @param User $user
     * @return bool
     */
    public function targeting(User $user)
    {
        return $user->plan->features->link_targeting;
    }

    /**
     * Determine whether the user can use UTM.
     *
     * @param User $user
     * @return bool
     */
    public function utm(User $user)
    {
        return $user->plan->features->link_utm;
    }

    /**
     * Determine whether the user can use Password.
     *
     * @param User $user
     * @return bool
     */
    public function password(User $user)
    {
        return $user->plan->features->link_password;
    }

    /**
     * Determine whether the user can use Expire.
     *
     * @param User $user
     * @return bool
     */
    public function expiration(User $user)
    {
        return $user->plan->features->link_expiration;
    }

    /**
     * Determine whether the user can use Global Domains.
     *
     * @param User $user
     * @return bool
     */
    public function globalDomains(User $user)
    {
        return $user->plan->features->global_domains;
    }

    /**
     * Determine whether the user can use Deep Links.
     *
     * @param User $user
     * @return bool
     */
    public function deepLinks(User $user)
    {
        return $user->plan->features->link_deep;
    }
}
