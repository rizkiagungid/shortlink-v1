<?php

namespace App\Observers;

use App\Models\Space;

class SpaceObserver
{
    /**
     * Handle the Space "deleting" event.
     *
     * @param  \App\Models\Space  $space
     * @return void
     */
    public function deleting(Space $space)
    {
        // Delete all the related links, it needs to be called in
        // a loop, otherwise the delete() method won't trigger for the targeted model
        if (isset($space->links))
        {
            foreach ($space->links as $link) {
                $link->delete();
            }
        }
    }
}
