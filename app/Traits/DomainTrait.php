<?php


namespace App\Traits;

use App\Models\Domain;
use Illuminate\Http\Request;

trait DomainTrait
{
    /**
     * Store the Domain.
     *
     * @param Request $request
     * @return Domain
     */
    protected function domainStore(Request $request)
    {
        $domain = new Domain;

        $domain->name = $request->input('name');
        $domain->user_id = ($request->is('admin/*') ? 0 : $request->user()->id);
        $domain->index_page = $request->input('index_page');
        $domain->not_found_page = $request->input('not_found_page');
        $domain->save();

        return $domain;
    }

    /**
     * Update the Domain.
     *
     * @param Request $request
     * @param Domain $domain
     * @return Domain
     */
    protected function domainUpdate(Request $request, Domain $domain)
    {
        if ($request->has('index_page')) {
            $domain->index_page = $request->input('index_page');
        }

        if ($request->has('not_found_page')) {
            $domain->not_found_page = $request->input('not_found_page');
        }

        $domain->save();

        return $domain;
    }
}