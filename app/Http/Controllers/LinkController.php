<?php

namespace App\Http\Controllers;

use App\Models\Domain;
use App\Http\Requests\StoreLinkRequest;
use App\Http\Requests\UpdateLinkRequest;
use App\Models\Link;
use App\Models\LinkPixel;
use App\Models\Pixel;
use App\Models\Space;
use App\Traits\LinkTrait;
use Carbon\Carbon;
use Carbon\CarbonTimeZone;
use Illuminate\Http\Request;
use League\Csv as CSV;

class LinkController extends Controller
{
    use LinkTrait;

    /**
     * List the Links.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Get the user's spaces
        $spaces = Space::where('user_id', $request->user()->id)->get();

        // Get the user's domains
        $domains = Domain::whereIn('user_id', [0, $request->user()->id])->orderBy('name')->get();

        // Get the user's pixels
        $pixels = Pixel::where('user_id', $request->user()->id)->get();

        $search = $request->input('search');
        $searchBy = in_array($request->input('search_by'), ['title', 'alias', 'url']) ? $request->input('search_by') : 'title';
        $space = $request->input('space');
        $domain = $request->input('domain');
        $pixel = $request->input('pixel');
        $status = $request->input('status');
        $sortBy = in_array($request->input('sort_by'), ['id', 'clicks', 'title', 'alias', 'url']) ? $request->input('sort_by') : 'id';
        $sort = in_array($request->input('sort'), ['asc', 'desc']) ? $request->input('sort') : 'desc';
        $perPage = in_array($request->input('per_page'), [10, 25, 50, 100]) ? $request->input('per_page') : config('settings.paginate');

        // If there's no toast notification
        if ($request->session()->get('toast') == false) {
            // Set the session to a countable object
            $request->session()->put('toast', []);
        }

        $links = Link::with('domain', 'space')
            ->where('user_id', $request->user()->id)
            ->when($domain, function ($query) use ($domain) {
                return $query->ofDomain($domain);
            })
            ->when(isset($space) && is_numeric($space), function ($query) use ($space) {
                return $query->ofSpace($space);
            })
            ->when($pixel, function ($query) use ($pixel) {
                return $query->whereIn('id', LinkPixel::select('link_id')->where('pixel_id', '=', $pixel)->get());
            })
            ->when($status, function ($query) use ($status) {
                if($status == 1) {
                    return $query->active();
                } elseif($status == 2) {
                    return $query->expired();
                } else {
                    return $query->disabled();
                }
            })
            ->when($search, function ($query) use ($search, $searchBy) {
                if($searchBy == 'url') {
                    return $query->searchUrl($search);
                } elseif ($searchBy == 'alias') {
                    return $query->searchAlias($search);
                }
                return $query->searchTitle($search);
            })
            ->orderBy($sortBy, $sort)
            ->paginate($perPage)
            ->appends(['search' => $search, 'search_by' => $searchBy, 'domain' => $domain, 'space' => $space, 'pixel' => $pixel, 'sort_by' => $sortBy, 'sort' => $sort, 'per_page' => $perPage]);

        return view('links.container', ['view' => 'list', 'links' => $links, 'spaces' => $spaces, 'domains' => $domains, 'pixels' => $pixels]);
    }

    /**
     * Export the Links.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws CSV\CannotInsertRecord
     */
    public function export(Request $request)
    {
        if ($request->user()->cannot('dataExport', ['App\Models\User'])) {
            abort(403);
        }

        $search = $request->input('search');
        $space = $request->input('space');
        $domain = $request->input('domain');
        $pixel = $request->input('pixel');
        $status = $request->input('status');
        $by = $request->input('by');

        if ($request->input('sort') == 'min') {
            $sort = ['clicks', 'asc'];
        } elseif ($request->input('sort') == 'max') {
            $sort = ['clicks', 'desc'];
        } elseif ($request->input('sort') == 'asc') {
            $sort = ['id', 'asc'];
        } else {
            $sort = ['id', 'desc'];
        }

        $links = Link::with('domain', 'space')
            ->where('user_id', $request->user()->id)
            ->when($domain, function ($query) use ($domain) {
                return $query->ofDomain($domain);
            })
            ->when(isset($space) && is_numeric($space), function ($query) use ($space) {
                return $query->ofSpace($space);
            })
            ->when($pixel, function ($query) use ($pixel) {
                return $query->whereIn('id', LinkPixel::select('link_id')->where('pixel_id', '=', $pixel)->get());
            })
            ->when($status, function ($query) use ($status) {
                if($status == 1) {
                    return $query->active();
                } elseif($status == 2) {
                    return $query->expired();
                } else {
                    return $query->disabled();
                }
            })
            ->when($search, function ($query) use ($search, $by) {
                if($by == 'url') {
                    return $query->searchUrl($search);

                } elseif ($by == 'alias') {
                    return $query->searchAlias($search);
                }
                return $query->searchTitle($search);
            })
            ->orderBy($sort[0], $sort[1])
            ->get();

        $content = CSV\Writer::createFromFileObject(new \SplTempFileObject);

        // Generate the header
        $content->insertOne([__('Type'), __('Links')]);
        $content->insertOne([__('Date'), Carbon::now()->tz($request->user()->timezone ?? config('app.timezone'))->format(__('Y-m-d')) . ' ' . Carbon::now()->tz($request->user()->timezone ?? config('app.timezone'))->format('H:i:s') . ' (' . CarbonTimeZone::create($request->user()->timezone ?? config('app.timezone'))->toOffsetName() . ')']);
        $content->insertOne([__('URL'), $request->fullUrl()]);
        $content->insertOne([__(' ')]);

        // Generate the content
        $content->insertOne([__('Short'), __('Original'), __('Alias'), __('Title'), __('Created at')]);
        foreach ($links as $link) {
            $content->insertOne([(($link->domain->url ?? config('app.url')) . '/' . $link->alias), $link->url, $link->alias, $link->title, $link->created_at->tz($request->user()->timezone ?? config('app.timezone'))->format(__('Y-m-d'))]);
        }

        return response((string) $content, 200, [
            'Content-Type' => 'text/csv',
            'Content-Transfer-Encoding' => 'binary',
            'Content-Disposition' => 'attachment; filename="' . formatTitle([__('Links'), config('settings.title')]) . '.csv"',
        ]);
    }

    /**
     * Show the edit Link form.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request, $id)
    {
        // Get the user's spaces
        $spaces = Space::where('user_id', $request->user()->id)->get();

        // Get the user's domains
        $domains = Domain::where('user_id', $request->user()->id)->get();

        // Get the user's pixels
        $pixels = Pixel::where('user_id', $request->user()->id)->get();

        $link = Link::where([['id', '=', $id], ['user_id', '=', $request->user()->id]])->firstOrFail();

        return view('links.container', ['view' => 'edit', 'spaces' => $spaces, 'domains' => $domains, 'pixels' => $pixels, 'link' => $link]);
    }

    /**
     * Store the Link.
     * 
     * @param StoreLinkRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function store(StoreLinkRequest $request)
    {
        if ($request->multiple_links) {
            $links = $this->linksStore($request);

            return redirect()->back()->with('toast', Link::where('user_id', '=', $request->user()->id)->orderBy('id', 'desc')->limit(count($links))->get());
        } else {
            $this->linkStore($request);

            return redirect()->back()->with('toast', Link::where('user_id', '=', $request->user()->id)->orderBy('id', 'desc')->limit(1)->get());
        }
    }

    /**
     * Update the Link.
     * 
     * @param UpdateLinkRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function update(UpdateLinkRequest $request, $id)
    {
        $link = Link::where([['id', '=', $id], ['user_id', '=', $request->user()->id]])->firstOrFail();

        $this->linkUpdate($request, $link);

        return redirect()->route('links.edit', $id)->with('success', __('Settings saved.'));
    }

    /**
     * Delete the Link.
     * 
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Request $request, $id)
    {
        $link = Link::where([['id', '=', $id], ['user_id', '=', $request->user()->id]])->firstOrFail();

        $link->delete();

        return redirect()->route('links')->with('success', __(':name has been deleted.', ['name' => str_replace(['http://', 'https://'], '', ($link->domain->url ?? config('app.url'))) . '/' . $link->alias]));
    }
}
