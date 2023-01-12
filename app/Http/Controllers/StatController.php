<?php

namespace App\Http\Controllers;

use App\Http\Requests\ValidateLinkStatsPasswordRequest;
use App\Models\Link;
use App\Models\Stat;
use App\Traits\DateRangeTrait;
use Carbon\Carbon;
use Carbon\CarbonTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use League\Csv as CSV;

class StatController extends Controller
{
    use DateRangeTrait;

    /**
     * Show the Overview stats page.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request, $id)
    {
        $link = Link::where('id', $id)->firstOrFail();

        // If the link is from a Guest
        if (!$link->user) {
            return view('stats.container', ['view' => 'guest', 'link' => $link]);
        }

        if ($this->statsGuard($link)) {
            return view('stats.password', ['link' => $link]);
        }

        $range = $this->range($link->created_at);

        $clicksMap = $this->getTraffic($link, $range);

        $totalClicks = 0;
        foreach ($clicksMap as $key => $value) {
            $totalClicks = $totalClicks + $value;
        }

        $totalClicksOld = Stat::where([['link_id', '=', $link->id], ['name', '=', 'clicks']])
            ->whereBetween('date', [$range['from_old'], $range['to_old']])
            ->sum('count');

        $totalReferrers = Stat::where([['link_id', '=', $link->id], ['name', '=', 'referrer']])
            ->whereBetween('date', [$range['from'], $range['to']])
            ->sum('count');

        $referrers = $this->getReferrers($link, $range, null, null, 'count', 'desc')
            ->limit(5)
            ->get();

        $countries = $this->getCountries($link, $range, null, null, 'count', 'desc')
            ->limit(5)
            ->get();

        $browsers = $this->getBrowsers($link, $range, null, null, 'count', 'desc')
            ->limit(5)
            ->get();

        $platforms = $this->getPlatforms($link, $range, null, null, 'count', 'desc')
            ->limit(5)
            ->get();

        return view('stats.container', ['view' => 'overview', 'link' => $link, 'range' => $range, 'referrers' => $referrers, 'clicksMap' => $clicksMap, 'countries' => $countries, 'browsers' => $browsers, 'platforms' => $platforms, 'totalClicks' => $totalClicks, 'totalClicksOld' => $totalClicksOld, 'totalReferrers' => $totalReferrers]);
    }

    /**
     * Show the Referrers stats page.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function referrers(Request $request, $id)
    {
        $link = Link::where([['id', '=', $id], ['user_id', '<>', 0]])->firstOrFail();

        if ($this->statsGuard($link)) {
            return view('stats.password', ['link' => $link]);
        }

        $range = $this->range($link->created_at);
        $search = $request->input('search');
        $searchBy = in_array($request->input('search_by'), ['value']) ? $request->input('search_by') : 'value';
        $sortBy = in_array($request->input('sort_by'), ['count', 'value']) ? $request->input('sort_by') : 'count';
        $sort = in_array($request->input('sort'), ['asc', 'desc']) ? $request->input('sort') : 'desc';
        $perPage = in_array($request->input('per_page'), [10, 25, 50, 100]) ? $request->input('per_page') : config('settings.paginate');

        $total = Stat::selectRaw('SUM(`count`) as `count`')
            ->where([['link_id', '=', $link->id], ['name', '=', 'referrer'], ['value', '<>', '']])
            ->whereBetween('date', [$range['from'], $range['to']])
            ->first();

        $referrers = $this->getReferrers($link, $range, $search, $searchBy, $sortBy, $sort)
            ->paginate($perPage)
            ->appends(['from' => $range['from'], 'to' => $range['to'], 'search' => $search, 'search_by' => $searchBy, 'sort_by' => $sortBy, 'sort' => $sort]);

        $first = $this->getReferrers($link, $range, $search, $searchBy, 'count', 'desc')
            ->first();

        $last = $this->getReferrers($link, $range, $search, $searchBy, 'count', 'asc')
            ->first();

        return view('stats.container', ['view' => 'referrers', 'link' => $link, 'range' => $range, 'export' => 'stats.export.referrers', 'referrers' => $referrers, 'first' => $first, 'last' => $last, 'total' => $total]);
    }

    /**
     * Show the Countries stats page.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function countries(Request $request, $id)
    {
        $link = Link::where([['id', '=', $id], ['user_id', '<>', 0]])->firstOrFail();

        if ($this->statsGuard($link)) {
            return view('stats.password', ['link' => $link]);
        }

        $range = $this->range($link->created_at);
        $search = $request->input('search');
        $searchBy = in_array($request->input('search_by'), ['value']) ? $request->input('search_by') : 'value';
        $sortBy = in_array($request->input('sort_by'), ['count', 'value']) ? $request->input('sort_by') : 'count';
        $sort = in_array($request->input('sort'), ['asc', 'desc']) ? $request->input('sort') : 'desc';
        $perPage = in_array($request->input('per_page'), [10, 25, 50, 100]) ? $request->input('per_page') : config('settings.paginate');

        $total = Stat::selectRaw('SUM(`count`) as `count`')
            ->where([['link_id', '=', $link->id], ['name', '=', 'country']])
            ->whereBetween('date', [$range['from'], $range['to']])
            ->first();

        $countriesChart = $this->getCountries($link, $range, $search, $searchBy, $sortBy, $sort)
            ->get();

        $countries = $this->getCountries($link, $range, $search, $searchBy, $sortBy, $sort)
            ->paginate($perPage)
            ->appends(['from' => $range['from'], 'to' => $range['to'], 'search' => $search, 'search_by' => $searchBy, 'sort_by' => $sortBy, 'sort' => $sort]);

        $first = $this->getCountries($link, $range, $search, $searchBy, 'count', 'desc')
            ->first();

        $last = $this->getCountries($link, $range, $search, $searchBy, 'count', 'asc')
            ->first();

        return view('stats.container', ['view' => 'countries', 'link' => $link, 'range' => $range, 'export' => 'stats.export.countries', 'countries' => $countries, 'countriesChart' => $countriesChart, 'first' => $first, 'last' => $last, 'total' => $total]);
    }

    /**
     * Show the Cities stats page.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function cities(Request $request, $id)
    {
        $link = Link::where([['id', '=', $id], ['user_id', '<>', 0]])->firstOrFail();

        if ($this->statsGuard($link)) {
            return view('stats.password', ['link' => $link]);
        }

        $range = $this->range($link->created_at);
        $search = $request->input('search');
        $searchBy = in_array($request->input('search_by'), ['value']) ? $request->input('search_by') : 'value';
        $sortBy = in_array($request->input('sort_by'), ['count', 'value']) ? $request->input('sort_by') : 'count';
        $sort = in_array($request->input('sort'), ['asc', 'desc']) ? $request->input('sort') : 'desc';
        $perPage = in_array($request->input('per_page'), [10, 25, 50, 100]) ? $request->input('per_page') : config('settings.paginate');

        $total = Stat::selectRaw('SUM(`count`) as `count`')
            ->where([['link_id', '=', $link->id], ['name', '=', 'city']])
            ->whereBetween('date', [$range['from'], $range['to']])
            ->first();

        $cities = $this->getCities($link, $range, $search, $searchBy, $sortBy, $sort)
            ->paginate($perPage)
            ->appends(['from' => $range['from'], 'to' => $range['to'], 'search' => $search, 'search_by' => $searchBy, 'sort_by' => $sortBy, 'sort' => $sort]);

        $first = $this->getCities($link, $range, $search, $searchBy, 'count', 'desc')
            ->first();

        $last = $this->getCities($link, $range, $search, $searchBy, 'count', 'asc')
            ->first();

        return view('stats.container', ['view' => 'cities', 'link' => $link, 'range' => $range, 'export' => 'stats.export.cities', 'cities' => $cities, 'first' => $first, 'last' => $last, 'total' => $total]);
    }

    /**
     * Show the Languages stats page.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function languages(Request $request, $id)
    {
        $link = Link::where([['id', '=', $id], ['user_id', '<>', 0]])->firstOrFail();

        if ($this->statsGuard($link)) {
            return view('stats.password', ['link' => $link]);
        }

        $range = $this->range($link->created_at);
        $search = $request->input('search');
        $searchBy = in_array($request->input('search_by'), ['value']) ? $request->input('search_by') : 'value';
        $sortBy = in_array($request->input('sort_by'), ['count', 'value']) ? $request->input('sort_by') : 'count';
        $sort = in_array($request->input('sort'), ['asc', 'desc']) ? $request->input('sort') : 'desc';
        $perPage = in_array($request->input('per_page'), [10, 25, 50, 100]) ? $request->input('per_page') : config('settings.paginate');

        $total = Stat::selectRaw('SUM(`count`) as `count`')
            ->where([['link_id', '=', $link->id], ['name', '=', 'language']])
            ->whereBetween('date', [$range['from'], $range['to']])
            ->first();

        $languages = $this->getLanguages($link, $range, $search, $searchBy, $sortBy, $sort)
            ->paginate($perPage)
            ->appends(['from' => $range['from'], 'to' => $range['to'], 'search' => $search, 'search_by' => $searchBy, 'sort_by' => $sortBy, 'sort' => $sort]);

        $first = $this->getLanguages($link, $range, $search, $searchBy, 'count', 'desc')
            ->first();

        $last = $this->getLanguages($link, $range, $search, $searchBy, 'count', 'asc')
            ->first();

        return view('stats.container', ['view' => 'languages', 'link' => $link, 'range' => $range, 'export' => 'stats.export.languages', 'languages' => $languages, 'first' => $first, 'last' => $last, 'total' => $total]);
    }

    /**
     * Show the Platforms stats page.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function platforms(Request $request, $id)
    {
        $link = Link::where([['id', '=', $id], ['user_id', '<>', 0]])->firstOrFail();

        if ($this->statsGuard($link)) {
            return view('stats.password', ['link' => $link]);
        }

        $range = $this->range($link->created_at);
        $search = $request->input('search');
        $searchBy = in_array($request->input('search_by'), ['value']) ? $request->input('search_by') : 'value';
        $sortBy = in_array($request->input('sort_by'), ['count', 'value']) ? $request->input('sort_by') : 'count';
        $sort = in_array($request->input('sort'), ['asc', 'desc']) ? $request->input('sort') : 'desc';
        $perPage = in_array($request->input('per_page'), [10, 25, 50, 100]) ? $request->input('per_page') : config('settings.paginate');

        $total = Stat::selectRaw('SUM(`count`) as `count`')
            ->where([['link_id', '=', $link->id], ['name', '=', 'platform']])
            ->whereBetween('date', [$range['from'], $range['to']])
            ->first();

        $platforms = $this->getPlatforms($link, $range, $search, $searchBy, $sortBy, $sort)
            ->paginate($perPage)
            ->appends(['from' => $range['from'], 'to' => $range['to'], 'search' => $search, 'search_by' => $searchBy, 'sort_by' => $sortBy, 'sort' => $sort]);

        $first = $this->getPlatforms($link, $range, $search, $searchBy, 'count', 'desc')
            ->first();

        $last = $this->getPlatforms($link, $range, $search, $searchBy, 'count', 'asc')
            ->first();

        return view('stats.container', ['view' => 'platforms', 'link' => $link, 'range' => $range, 'export' => 'stats.export.platforms', 'platforms' => $platforms, 'first' => $first, 'last' => $last, 'total' => $total]);
    }

    /**
     * Show the Browsers stats page.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function browsers(Request $request, $id)
    {
        $link = Link::where([['id', '=', $id], ['user_id', '<>', 0]])->firstOrFail();

        if ($this->statsGuard($link)) {
            return view('stats.password', ['link' => $link]);
        }

        $range = $this->range($link->created_at);
        $search = $request->input('search');
        $searchBy = in_array($request->input('search_by'), ['value']) ? $request->input('search_by') : 'value';
        $sortBy = in_array($request->input('sort_by'), ['count', 'value']) ? $request->input('sort_by') : 'count';
        $sort = in_array($request->input('sort'), ['asc', 'desc']) ? $request->input('sort') : 'desc';
        $perPage = in_array($request->input('per_page'), [10, 25, 50, 100]) ? $request->input('per_page') : config('settings.paginate');

        $total = Stat::selectRaw('SUM(`count`) as `count`')
            ->where([['link_id', '=', $link->id], ['name', '=', 'browser']])
            ->whereBetween('date', [$range['from'], $range['to']])
            ->first();

        $browsers = $this->getBrowsers($link, $range, $search, $searchBy, $sortBy, $sort)
            ->paginate($perPage)
            ->appends(['from' => $range['from'], 'to' => $range['to'], 'search' => $search, 'search_by' => $searchBy, 'sort_by' => $sortBy, 'sort' => $sort]);

        $first = $this->getBrowsers($link, $range, $search, $searchBy, 'count', 'desc')
            ->first();

        $last = $this->getBrowsers($link, $range, $search, $searchBy, 'count', 'asc')
            ->first();

        return view('stats.container', ['view' => 'browsers', 'link' => $link, 'range' => $range, 'export' => 'stats.export.browsers', 'browsers' => $browsers, 'first' => $first, 'last' => $last, 'total' => $total]);
    }

    /**
     * Show the Devices stats page.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function devices(Request $request, $id)
    {
        $link = Link::where([['id', '=', $id], ['user_id', '<>', 0]])->firstOrFail();

        if ($this->statsGuard($link)) {
            return view('stats.password', ['link' => $link]);
        }

        $range = $this->range($link->created_at);
        $search = $request->input('search');
        $searchBy = in_array($request->input('search_by'), ['value']) ? $request->input('search_by') : 'value';
        $sortBy = in_array($request->input('sort_by'), ['count', 'value']) ? $request->input('sort_by') : 'count';
        $sort = in_array($request->input('sort'), ['asc', 'desc']) ? $request->input('sort') : 'desc';
        $perPage = in_array($request->input('per_page'), [10, 25, 50, 100]) ? $request->input('per_page') : config('settings.paginate');

        $total = Stat::selectRaw('SUM(`count`) as `count`')
            ->where([['link_id', '=', $link->id], ['name', '=', 'device']])
            ->whereBetween('date', [$range['from'], $range['to']])
            ->first();

        $devices = $this->getDevices($link, $range, $search, $searchBy, $sortBy, $sort)
            ->paginate($perPage)
            ->appends(['from' => $range['from'], 'to' => $range['to'], 'search' => $search, 'search_by' => $searchBy, 'sort_by' => $sortBy, 'sort' => $sort]);

        $first = $this->getDevices($link, $range, $search, $searchBy, 'count', 'desc')
            ->first();

        $last = $this->getDevices($link, $range, $search, $searchBy, 'count', 'asc')
            ->first();

        return view('stats.container', ['view' => 'devices', 'link' => $link, 'range' => $range, 'export' => 'stats.export.devices', 'devices' => $devices, 'first' => $first, 'last' => $last, 'total' => $total]);
    }

    /**
     * Export the Referrers stats.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|CSV\Writer
     * @throws CSV\CannotInsertRecord
     */
    public function exportReferrers(Request $request, $id)
    {
        $link = Link::where([['id', '=', $id], ['user_id', '<>', 0]])->firstOrFail();

        if ($this->statsGuard($link)) {
            return view('stats.password', ['link' => $link]);
        }

        $range = $this->range($link->created_at);
        $search = $request->input('search');
        $searchBy = in_array($request->input('search_by'), ['value']) ? $request->input('search_by') : 'value';
        $sortBy = in_array($request->input('sort_by'), ['count', 'value']) ? $request->input('sort_by') : 'count';
        $sort = in_array($request->input('sort'), ['asc', 'desc']) ? $request->input('sort') : 'desc';

        return $this->exportCSV($request, $link, __('Referrers'), $range, __('URL'), __('Clicks'), $this->getReferrers($link, $range, $search, $searchBy, $sortBy, $sort)->get());
    }

    /**
     * Export the Countries stats.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|CSV\Writer
     * @throws CSV\CannotInsertRecord
     */
    public function exportCountries(Request $request, $id)
    {
        $link = Link::where([['id', '=', $id], ['user_id', '<>', 0]])->firstOrFail();

        if ($this->statsGuard($link)) {
            return view('stats.password', ['link' => $link]);
        }

        $range = $this->range($link->created_at);
        $search = $request->input('search');
        $searchBy = in_array($request->input('search_by'), ['value']) ? $request->input('search_by') : 'value';
        $sortBy = in_array($request->input('sort_by'), ['count', 'value']) ? $request->input('sort_by') : 'count';
        $sort = in_array($request->input('sort'), ['asc', 'desc']) ? $request->input('sort') : 'desc';

        return $this->exportCSV($request, $link, __('Countries'), $range, __('Name'), __('Clicks'), $this->getCountries($link, $range, $search, $searchBy, $sortBy, $sort)->get());
    }

    /**
     * Export the Cities stats.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|CSV\Writer
     * @throws CSV\CannotInsertRecord
     */
    public function exportCities(Request $request, $id)
    {
        $link = Link::where([['id', '=', $id], ['user_id', '<>', 0]])->firstOrFail();

        if ($this->statsGuard($link)) {
            return view('stats.password', ['link' => $link]);
        }

        $range = $this->range($link->created_at);
        $search = $request->input('search');
        $searchBy = in_array($request->input('search_by'), ['value']) ? $request->input('search_by') : 'value';
        $sortBy = in_array($request->input('sort_by'), ['count', 'value']) ? $request->input('sort_by') : 'count';
        $sort = in_array($request->input('sort'), ['asc', 'desc']) ? $request->input('sort') : 'desc';

        return $this->exportCSV($request, $link, __('Cities'), $range, __('Name'), __('Clicks'), $this->getCities($link, $range, $search, $searchBy, $sortBy, $sort)->get());
    }

    /**
     * Export the Languages stats.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|CSV\Writer
     * @throws CSV\CannotInsertRecord
     */
    public function exportLanguages(Request $request, $id)
    {
        $link = Link::where([['id', '=', $id], ['user_id', '<>', 0]])->firstOrFail();

        if ($this->statsGuard($link)) {
            return view('stats.password', ['link' => $link]);
        }

        $range = $this->range($link->created_at);
        $search = $request->input('search');
        $searchBy = in_array($request->input('search_by'), ['value']) ? $request->input('search_by') : 'value';
        $sortBy = in_array($request->input('sort_by'), ['count', 'value']) ? $request->input('sort_by') : 'count';
        $sort = in_array($request->input('sort'), ['asc', 'desc']) ? $request->input('sort') : 'desc';

        return $this->exportCSV($request, $link, __('Languages'), $range, __('Name'), __('Clicks'), $this->getLanguages($link, $range, $search, $searchBy, $sortBy, $sort)->get());
    }

    /**
     * Export the Platforms stats.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|CSV\Writer
     * @throws CSV\CannotInsertRecord
     */
    public function exportPlatforms(Request $request, $id)
    {
        $link = Link::where([['id', '=', $id], ['user_id', '<>', 0]])->firstOrFail();

        if ($this->statsGuard($link)) {
            return view('stats.password', ['link' => $link]);
        }

        $range = $this->range($link->created_at);
        $search = $request->input('search');
        $searchBy = in_array($request->input('search_by'), ['value']) ? $request->input('search_by') : 'value';
        $sortBy = in_array($request->input('sort_by'), ['count', 'value']) ? $request->input('sort_by') : 'count';
        $sort = in_array($request->input('sort'), ['asc', 'desc']) ? $request->input('sort') : 'desc';

        return $this->exportCSV($request, $link, __('Platforms'), $range, __('Name'), __('Clicks'), $this->getPlatforms($link, $range, $search, $searchBy, $sortBy, $sort)->get());
    }

    /**
     * Export the Browsers stats.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|CSV\Writer
     * @throws CSV\CannotInsertRecord
     */
    public function exportBrowsers(Request $request, $id)
    {
        $link = Link::where([['id', '=', $id], ['user_id', '<>', 0]])->firstOrFail();

        if ($this->statsGuard($link)) {
            return view('stats.password', ['link' => $link]);
        }

        $range = $this->range($link->created_at);
        $search = $request->input('search');
        $searchBy = in_array($request->input('search_by'), ['value']) ? $request->input('search_by') : 'value';
        $sortBy = in_array($request->input('sort_by'), ['count', 'value']) ? $request->input('sort_by') : 'count';
        $sort = in_array($request->input('sort'), ['asc', 'desc']) ? $request->input('sort') : 'desc';

        return $this->exportCSV($request, $link, __('Browsers'), $range, __('Name'), __('Clicks'), $this->getBrowsers($link, $range, $search, $searchBy, $sortBy, $sort)->get());
    }

    /**
     * Export the Devices stats.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|CSV\Writer
     * @throws CSV\CannotInsertRecord
     */
    public function exportDevices(Request $request, $id)
    {
        $link = Link::where([['id', '=', $id], ['user_id', '<>', 0]])->firstOrFail();

        if ($this->statsGuard($link)) {
            return view('stats.password', ['link' => $link]);
        }

        $range = $this->range($link->created_at);
        $search = $request->input('search');
        $searchBy = in_array($request->input('search_by'), ['value']) ? $request->input('search_by') : 'value';
        $sortBy = in_array($request->input('sort_by'), ['count', 'value']) ? $request->input('sort_by') : 'count';
        $sort = in_array($request->input('sort'), ['asc', 'desc']) ? $request->input('sort') : 'desc';
        
        return $this->exportCSV($request, $link, __('Devices'), $range, __('Type'), __('Clicks'), $this->getDevices($link, $range, $search, $searchBy, $sortBy, $sort)->get());
    }

    /**
     * Get the Referrers.
     *
     * @param $link
     * @param $range
     * @param null $search
     * @param null $sort
     * @return mixed
     */
    private function getReferrers($link, $range, $search = null, $searchBy = null, $sortBy = null, $sort = null)
    {
        return Stat::selectRaw('`value`, SUM(`count`) as `count`')
            ->where([['link_id', '=', $link->id], ['name', '=', 'referrer'], ['value', '<>', '']])
            ->when($search, function ($query) use ($search, $searchBy) {
                return $query->searchValue($search);
            })
            ->whereBetween('date', [$range['from'], $range['to']])
            ->groupBy('value')
            ->orderBy($sortBy, $sort);
    }

    /**
     * Get the Countries.
     *
     * @param $link
     * @param $range
     * @param null $search
     * @param null $sort
     * @return mixed
     */
    private function getCountries($link, $range, $search = null, $searchBy = null, $sortBy = null, $sort = null)
    {
        return Stat::selectRaw('`value`, SUM(`count`) as `count`')
            ->where([['link_id', '=', $link->id], ['name', '=', 'country']])
            ->when($search, function ($query) use ($search, $searchBy) {
                return $query->searchValue($search);
            })
            ->whereBetween('date', [$range['from'], $range['to']])
            ->groupBy('value')
            ->orderBy($sortBy, $sort);
    }

    /**
     * Get the Cities.
     *
     * @param $link
     * @param $range
     * @param null $search
     * @param null $sort
     * @return mixed
     */
    private function getCities($link, $range, $search = null, $searchBy = null, $sortBy = null, $sort = null)
    {
        return Stat::selectRaw('`value`, SUM(`count`) as `count`')
            ->where([['link_id', '=', $link->id], ['name', '=', 'city']])
            ->when($search, function ($query) use ($search, $searchBy) {
                return $query->searchValue($search);
            })
            ->whereBetween('date', [$range['from'], $range['to']])
            ->groupBy('value')
            ->orderBy($sortBy, $sort);
    }

    /**
     * Get the Languages.
     *
     * @param $link
     * @param $range
     * @param null $search
     * @param null $sort
     * @return mixed
     */
    private function getLanguages($link, $range, $search = null, $searchBy = null, $sortBy = null, $sort = null)
    {
        return Stat::selectRaw('`value`, SUM(`count`) as `count`')
            ->where([['link_id', '=', $link->id], ['name', '=', 'language']])
            ->when($search, function ($query) use ($search, $searchBy) {
                return $query->searchValue($search);
            })
            ->whereBetween('date', [$range['from'], $range['to']])
            ->groupBy('value')
            ->orderBy($sortBy, $sort);
    }

    /**
     * Get the Platforms.
     *
     * @param $link
     * @param $range
     * @param null $search
     * @param null $sort
     * @return mixed
     */
    private function getPlatforms($link, $range, $search = null, $searchBy = null, $sortBy = null, $sort = null)
    {
        return Stat::selectRaw('`value`, SUM(`count`) as `count`')
            ->where([['link_id', '=', $link->id], ['name', '=', 'platform']])
            ->when($search, function ($query) use ($search, $searchBy) {
                return $query->searchValue($search);
            })
            ->whereBetween('date', [$range['from'], $range['to']])
            ->groupBy('value')
            ->orderBy($sortBy, $sort);
    }

    private function getBrowsers($link, $range, $search = null, $searchBy = null, $sortBy = null, $sort = null)
    {
        return Stat::selectRaw('`value`, SUM(`count`) as `count`')
            ->where([['link_id', '=', $link->id], ['name', '=', 'browser']])
            ->when($search, function ($query) use ($search, $searchBy) {
                return $query->searchValue($search);
            })
            ->whereBetween('date', [$range['from'], $range['to']])
            ->groupBy('value')
            ->orderBy($sortBy, $sort);
    }

    /**
     * Get the Devices.
     *
     * @param $link
     * @param $range
     * @param null $search
     * @param null $sort
     * @return mixed
     */
    private function getDevices($link, $range, $search = null, $searchBy = null, $sortBy = null, $sort = null)
    {
        return Stat::selectRaw('`value`, SUM(`count`) as `count`')
            ->where([['link_id', '=', $link->id], ['name', '=', 'device']])
            ->when($search, function ($query) use ($search, $searchBy) {
                return $query->searchValue($search);
            })
            ->whereBetween('date', [$range['from'], $range['to']])
            ->groupBy('value')
            ->orderBy($sortBy, $sort);
    }

    /**
     * Get the visitors or pageviews in a formatted way, based on the date range
     *
     * @param $link
     * @param $range
     * @return array|int[]
     */
    private function getTraffic($link, $range)
    {
        // If the date range is for a single day
        if ($range['unit'] == 'hour') {
            $rows = Stat::where([['link_id', '=', $link->id], ['name', '=', 'clicks_hours']])
                ->whereBetween('date', [$range['from'], $range['to']])
                ->orderBy('date', 'asc')
                ->get();

            $output = ['00' => 0, '01' => 0, '02' => 0, '03' => 0, '04' => 0, '05' => 0, '06' => 0, '07' => 0, '08' => 0, '09' => 0, '10' => 0, '11' => 0, '12' => 0, '13' => 0, '14' => 0, '15' => 0, '16' => 0, '17' => 0, '18' => 0, '19' => 0, '20' => 0, '21' => 0, '22' => 0, '23' => 0];

            // Map the values to each date
            foreach ($rows as $row) {
                $output[$row->value] = $row->count;
            }
        } else {
            $rows = Stat::select([
                    DB::raw("date_format(`date`, '" . str_replace(['Y', 'm', 'd'], ['%Y', '%m', '%d'], $range['format']) . "') as `date_result`, SUM(`count`) as `aggregate`")
                ])
                ->where([['link_id', '=', $link->id], ['name', '=', 'clicks']])
                ->whereBetween('date', [$range['from'], $range['to']])
                ->groupBy('date_result')
                ->orderBy('date_result', 'asc')
                ->get();

            $rangeMap = $this->calcAllDates(Carbon::createFromFormat('Y-m-d', $range['from'])->format($range['format']), Carbon::createFromFormat('Y-m-d', $range['to'])->format($range['format']), $range['unit'], $range['format'], 0);

            // Remap the result set, and format the array
            $collection = $rows->mapWithKeys(function ($result) use ($range) {
                return [strval($range['unit'] == 'year' ? $result->date_result : Carbon::parse($result->date_result)->format($range['format'])) => $result->aggregate];
            })->all();

            // Merge the results with the pre-calculated possible time ranges
            $output = array_replace($rangeMap, $collection);
        }
        return $output;
    }

    /**
     * Export data in CSV format.
     *
     * @param $request
     * @param $link
     * @param $title
     * @param $range
     * @param $name
     * @param $count
     * @param $results
     * @return CSV\Writer
     * @throws CSV\CannotInsertRecord
     */
    private function exportCSV($request, $link, $title, $range, $name, $count, $results)
    {
        if ($link->user->cannot('dataExport', ['App\Models\User'])) {
            abort(403);
        }
        
        $content = CSV\Writer::createFromFileObject(new \SplTempFileObject);

        // Generate the header
        $content->insertOne([__('URL'), str_replace(['http://', 'https://'], '', (str_replace(['http://', 'https://'], '', $link->domain->url ?? config('app.url'))) . '/' . $link->alias)]);
        $content->insertOne([__('Type'), $title]);
        $content->insertOne([__('Interval'), $range['from'] . ' - ' . $range['to']]);
        $content->insertOne([__('Date'), Carbon::now()->format(__('Y-m-d')) . ' ' . Carbon::now()->format('H:i:s') . ' (' . CarbonTimeZone::create(config('app.timezone'))->toOffsetName() . ')']);
        $content->insertOne([__('URL'), $request->fullUrl()]);
        $content->insertOne([__(' ')]);

        // Generate the summary
        $content->insertOne([__('Clicks'), Stat::where([['link_id', '=', $link->id], ['name', '=', 'clicks']])
            ->whereBetween('date', [$range['from'], $range['to']])
            ->sum('count')]);
        $content->insertOne([__(' ')]);

        // Generate the content
        $content->insertOne([__($name), __($count)]);
        foreach ($results as $result) {
            $content->insertOne($result->toArray());
        }

        return response((string)$content, 200, [
            'Content-Type' => 'text/csv',
            'Content-Transfer-Encoding' => 'binary',
            'Content-Disposition' => 'attachment; filename="' . formatTitle([$link->alias, str_replace(['http://', 'https://'], '', ($link->domain->url ?? config('app.url'))), $title, $range['from'], $range['to'], config('settings.title')]) . '.csv"'
        ]);
    }

    /**
     * Validate the link's password
     *
     * @param ValidateLinkStatsPasswordRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function validatePassword(ValidateLinkStatsPasswordRequest $request, $id)
    {
        session([md5($id) => true]);
        return redirect()->back();
    }

    /**
     * Guard the stats pages.
     *
     * @param $link
     * @return bool
     */
    private function statsGuard($link)
    {
        // If the link stats is not set to public
        if ($link->privacy !== 0) {
            $user = Auth::user();

            // If the website's privacy is set to private
            if ($link->privacy == 1) {
                // If the user is not authenticated
                // Or if the user is not the owner of the link and not an admin
                if ($user == null || $user->id != $link->user_id && $user->role != 1) {
                    abort(403);
                }
            }

            // If the website's privacy is set to password
            if ($link->privacy == 2) {
                // If there's no password validation in the current session
                if (!session(md5($link->id))) {
                    // If the user is not authenticated
                    // Or if the user is not the owner of the link and not an admin
                    if ($user == null || $user->id != $link->user_id && $user->role != 1) {
                        return true;
                    }
                }
            }
        }

        return false;
    }
}
