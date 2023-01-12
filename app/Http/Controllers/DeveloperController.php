<?php

namespace App\Http\Controllers;

class DeveloperController extends Controller
{
    /**
     * Show the Developer index page.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('developers.index');
    }

    /**
     * Show the Developer Links page.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function links()
    {
        return view('developers.links.index');
    }

    /**
     * Show the Developer Spaces page.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function spaces()
    {
        return view('developers.spaces.index');
    }

    /**
     * Show the Developer Domains page.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function domains()
    {
        return view('developers.domains.index');
    }

    /**
     * Show the Developer Pixels page.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function pixels()
    {
        return view('developers.pixels.index');
    }

    /**
     * Show the Developer Stats page.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function stats()
    {
        return view('developers.stats.index');
    }

    /**
     * Show the Developer Account page.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function account()
    {
        return view('developers.account.index');
    }
}
