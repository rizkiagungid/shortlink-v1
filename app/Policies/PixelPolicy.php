<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Pixel;
use Illuminate\Auth\Access\HandlesAuthorization;

class PixelPolicy
{
    use HandlesAuthorization;
    
    /**
     * Determine whether the user can view any pixels.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the pixel.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Pixel  $pixel
     * @return mixed
     */
    public function view(User $user, Pixel $pixel)
    {
        //
    }

    /**
     * Determine whether the user can create pixels.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        if ($user->plan->features->pixels == -1) {
            return true;
        } elseif($user->plan->features->pixels > 0) {
            $count = Pixel::where('user_id', '=', $user->id)->count();

            if ($count < $user->plan->features->pixels) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine whether the user can update the pixel.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Pixel  $pixel
     * @return mixed
     */
    public function update(User $user, Pixel $pixel)
    {
        //
    }

    /**
     * Determine whether the user can delete the pixel.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Pixel  $pixel
     * @return mixed
     */
    public function delete(User $user, Pixel $pixel)
    {
        //
    }

    /**
     * Determine whether the user can restore the pixel.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Pixel  $pixel
     * @return mixed
     */
    public function restore(User $user, Pixel $pixel)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the pixel.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Pixel  $pixel
     * @return mixed
     */
    public function forceDelete(User $user, Pixel $pixel)
    {
        //
    }
}
