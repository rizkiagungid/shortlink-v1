<?php

namespace App\Observers;

use App\Models\Domain;

class DomainObserver
{
    /**
     * Handle the Domain "deleting" event.
     *
     * @param  \App\Models\Domain  $domain
     * @return void
     */
    public function deleting(Domain $domain)
    {
        // Delete all the related links, it needs to be called in
        // a loop, otherwise the delete() method won't trigger for the targeted model
        if (isset($domain->links))
        {
            foreach ($domain->links as $link) {
                $link->delete();
            }
        }
    }
}
