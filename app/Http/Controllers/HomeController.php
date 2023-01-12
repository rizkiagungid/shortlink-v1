<?php

namespace App\Http\Controllers;

use App\Models\Domain;
use App\Http\Requests\StoreLinkRequest;
use App\Models\Link;
use App\Models\Plan;
use App\Traits\LinkTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    use LinkTrait;

    /**
     * Show the application dashboard.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Support\Renderable|\Illuminate\Http\RedirectResponse
     */
    public function index(Request $request)
    {
        // If there's no DB connection setup
        if (!env('DB_DATABASE')) {
            return redirect()->route('install');
        }

        // If the user is logged-in, redirect to dashboard
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        // Get the local host
        $local = parse_url(config('app.url'));

        // Get the request host
        $remote = $request->getHost();

        if ($local['host'] != $remote) {
            // Get the remote domain
            $domain = Domain::where('name', '=', $remote)->first();

            // If the domain exists
            if ($domain) {
                // If the domain has an index page defined
                if ($domain->index_page) {
                    return redirect()->to($domain->index_page, 301)->header('Cache-Control', 'no-store, no-cache, must-revalidate');
                } else {
                    return redirect()->to(config('app.url'), 301)->header('Cache-Control', 'no-store, no-cache, must-revalidate');
                }
            }
        }

        // If there's a custom site index
        if (config('settings.index')) {
            return redirect()->to(config('settings.index'), 301)->header('Cache-Control', 'no-store, no-cache, must-revalidate');
        }

        // If there's a payment processor enabled
        if (paymentProcessors()) {
            $user = Auth::user();

            $plans = Plan::where('visibility', 1)->orderBy('position')->orderBy('id')->get();

            $domains = Domain::select('name')->where('user_id', '=', 0)
                ->whereNotIn('id', [config('settings.short_domain')])
                ->get()
                ->map(function ($item) {
                    return $item->name;
                })
                ->toArray();
        } else {
            $user = null;
            $plans = null;
            $domains = null;
        }

        $defaultDomain = null;

        if (Domain::where([['user_id', '=', 0], ['id', '=', config('settings.short_domain')]])->exists()) {
            $defaultDomain = config('settings.short_domain');
        }

        return view('home.index', ['plans' => $plans, 'user' => $user, 'domains' => $domains, 'defaultDomain' => $defaultDomain]);
    }

    /**
     * Store the Link.
     *
     * @param StoreLinkRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function createLink(StoreLinkRequest $request)
    {
        if (!config('settings.short_guest')) {
            abort(404);
        }

        $this->linkStore($request);

        return redirect()->back()->with('link', Link::where('user_id', '=', 0)->orderBy('id', 'desc')->limit(1)->get());
    }
}
