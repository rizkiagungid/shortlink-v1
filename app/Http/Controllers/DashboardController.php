<?php

namespace App\Http\Controllers;

use App\Models\Domain;
use App\Models\Link;
use App\Models\Pixel;
use App\Models\Space;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show the Dashboard page.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        // If the user previously selected a plan
        if (!empty($request->session()->get('plan_redirect'))) {
            return redirect()->route('checkout.index', ['id' => $request->session()->get('plan_redirect')['id'], 'interval' => $request->session()->get('plan_redirect')['interval']]);
        }

        $latestLinks = Link::with('domain')->where('user_id', $request->user()->id)->orderBy('id', 'desc')->limit(5)->get();

        $clicks = [];

        $popularLinks = Link::with('domain')->where('user_id', $request->user()->id)->orderBy('clicks', 'desc')->limit(5)->get();

        $stats = [
            'spaces' => Space::where('user_id', $request->user()->id)->count(),
            'domains' => Domain::where('user_id', $request->user()->id)->count(),
            'pixels' => Pixel::where('user_id', $request->user()->id)->count()
        ];

        return view('dashboard.index', ['user' => $request->user(), 'latestLinks' => $latestLinks, 'clicks' => $clicks, 'popularLinks' => $popularLinks, 'stats' => $stats]);
    }
}
