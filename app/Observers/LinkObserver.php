<?php

namespace App\Observers;

use App\Models\Link;

class LinkObserver
{
    /**
     * Handle the Link "deleting" event.
     *
     * @param  \App\Models\Link  $link
     * @return void
     */
    public function deleting(Link $link)
    {
        $link->stats()->delete();
        $link->pixels()->detach();
    }
}
