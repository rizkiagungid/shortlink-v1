<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSpaceRequest;
use App\Http\Requests\UpdateSpaceRequest;
use App\Models\Space;
use App\Traits\SpaceTrait;
use Illuminate\Http\Request;

class SpaceController extends Controller
{
    use SpaceTrait;

    /**
     * List the Spaces.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $searchBy = in_array($request->input('search_by'), ['name']) ? $request->input('search_by') : 'name';
        $sortBy = in_array($request->input('sort_by'), ['id', 'name']) ? $request->input('sort_by') : 'id';
        $sort = in_array($request->input('sort'), ['asc', 'desc']) ? $request->input('sort') : 'desc';
        $perPage = in_array($request->input('per_page'), [10, 25, 50, 100]) ? $request->input('per_page') : config('settings.paginate');

        $spaces = Space::where('user_id', $request->user()->id)
            ->when($search, function ($query) use ($search, $searchBy) {
                return $query->searchName($search);
            })
            ->orderBy($sortBy, $sort)
            ->paginate($perPage)
            ->appends(['search' => $search, 'search_by' => $searchBy, 'sort_by' => $sortBy, 'sort' => $sort, 'per_page' => $perPage]);

        return view('spaces.container', ['view' => 'list', 'spaces' => $spaces]);
    }

    /**
     * Show the create Space form.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('spaces.container', ['view' => 'new']);
    }

    /**
     * Show the edit Space form.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request, $id)
    {
        $space = Space::where([['id', '=', $id], ['user_id', '=', $request->user()->id]])->firstOrFail();

        return view('spaces.container', ['view' => 'edit', 'space' => $space]);
    }

    /**
     * Store the Space.
     *
     * @param StoreSpaceRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreSpaceRequest $request)
    {
        $this->spaceStore($request);

        return redirect()->route('spaces')->with('success', __(':name has been created.', ['name' => $request->input('name')]));
    }

    /**
     * Update the Space.
     *
     * @param UpdateSpaceRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateSpaceRequest $request, $id)
    {
        $space = Space::where([['id', '=', $id], ['user_id', '=', $request->user()->id]])->firstOrFail();

        $this->spaceUpdate($request, $space);

        return back()->with('success', __('Settings saved.'));
    }

    /**
     * Delete the Space.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Request $request, $id)
    {
        $space = Space::where([['id', '=', $id], ['user_id', '=', $request->user()->id]])->firstOrFail();

        $space->delete();

        return redirect()->route('spaces')->with('success', __(':name has been deleted.', ['name' => $space->name]));
    }
}
