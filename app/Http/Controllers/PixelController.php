<?php

namespace App\Http\Controllers;

use App\Models\Pixel;
use App\Http\Requests\StorePixelRequest;
use App\Http\Requests\UpdatePixelRequest;
use App\Traits\PixelTrait;
use Illuminate\Http\Request;

class PixelController extends Controller
{
    use PixelTrait;

    /**
     * List the Pixels.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $searchBy = in_array($request->input('search_by'), ['name']) ? $request->input('search_by') : 'name';
        $type = $request->input('type');
        $sortBy = in_array($request->input('sort_by'), ['id', 'name']) ? $request->input('sort_by') : 'id';
        $sort = in_array($request->input('sort'), ['asc', 'desc']) ? $request->input('sort') : 'desc';
        $perPage = in_array($request->input('per_page'), [10, 25, 50, 100]) ? $request->input('per_page') : config('settings.paginate');

        $pixels = Pixel::where('user_id', $request->user()->id)
            ->when($search, function ($query) use ($search, $searchBy) {
                return $query->searchName($search);
            })->when($type, function ($query) use ($type) {
                return $query->ofType($type);
            })
            ->orderBy($sortBy, $sort)
            ->paginate($perPage)
            ->appends(['search' => $search, 'search_by' => $searchBy, 'type' => $type, 'sort_by' => $sortBy, 'sort' => $sort, 'per_page' => $perPage]);

        return view('pixels.container', ['view' => 'list', 'pixels' => $pixels]);
    }

    /**
     * Show the create Pixel form.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('pixels.container', ['view' => 'new']);
    }

    /**
     * Show the edit Pixel form.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request, $id)
    {
        $pixel = Pixel::where([['id', '=', $id], ['user_id', '=', $request->user()->id]])->firstOrFail();

        return view('pixels.container', ['view' => 'edit', 'pixel' => $pixel]);
    }

    /**
     * Store the Pixel.
     *
     * @param StorePixelRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StorePixelRequest $request)
    {
        $this->pixelStore($request);

        return redirect()->route('pixels')->with('success', __(':name has been created.', ['name' => str_replace(['http://', 'https://'], '', $request->input('name'))]));
    }

    /**
     * Update the Pixel.
     *
     * @param UpdatePixelRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdatePixelRequest $request, $id)
    {
        $pixel = Pixel::where([['id', '=', $id], ['user_id', '=', $request->user()->id]])->firstOrFail();

        $this->pixelUpdate($request, $pixel);

        return back()->with('success', __('Settings saved.'));
    }

    /**
     * Delete the Pixel.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Request $request, $id)
    {
        $pixel = Pixel::where([['id', '=', $id], ['user_id', '=', $request->user()->id]])->firstOrFail();

        $pixel->delete();

        return redirect()->route('pixels')->with('success', __(':name has been deleted.', ['name' => str_replace(['http://', 'https://'], '', $pixel->name)]));
    }
}
