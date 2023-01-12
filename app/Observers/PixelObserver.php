<?php

namespace App\Observers;

use App\Models\Pixel;

class PixelObserver
{
    /**
     * Handle the Pixel "deleting" event.
     *
     * @param  \App\Models\Pixel  $pixel
     * @return void
     */
    public function deleting(Pixel $pixel)
    {
        $pixel->links()->detach();
    }
}
