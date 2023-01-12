<?php

namespace App\Http\Controllers;

use App\Models\Domain;
use App\Models\Plan;

class PricingController extends Controller
{
    /**
     * Show the Pricing page.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $plans = Plan::where('visibility', 1)->orderBy('position')->orderBy('id')->get();

        $domains = Domain::select('name')->where('user_id', '=', 0)
            ->whereNotIn('id', [config('settings.short_domain')])
            ->get()
            ->map(function ($item) {
                return $item->name;
            })
            ->toArray();

        return view('pricing.index', ['plans' => $plans, 'domains' => $domains]);
    }
}
