<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\StoreSpaceRequest;
use App\Http\Requests\API\UpdateSpaceRequest;
use App\Http\Resources\SpaceResource;
use App\Models\Space;
use App\Traits\SpaceTrait;
use Illuminate\Http\Request;

class SpaceController extends Controller
{
    use SpaceTrait;

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $searchBy = in_array($request->input('search_by'), ['name']) ? $request->input('search_by') : 'name';
        $sortBy = in_array($request->input('sort_by'), ['id', 'name']) ? $request->input('sort_by') : 'id';
        $sort = in_array($request->input('sort'), ['asc', 'desc']) ? $request->input('sort') : 'desc';
        $perPage = in_array($request->input('per_page'), [10, 25, 50, 100]) ? $request->input('per_page') : config('settings.paginate');

        return SpaceResource::collection(Space::where('user_id', $request->user()->id)
            ->when($search, function ($query) use ($search, $searchBy) {
                return $query->searchName($search);
            })
            ->orderBy($sortBy, $sort)
            ->paginate($perPage)
            ->appends(['search' => $search, 'search_by' => $searchBy, 'sort_by' => $sortBy, 'sort' => $sort, 'per_page' => $perPage]))
            ->additional(['status' => 200]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreSpaceRequest $request
     * @return SpaceResource|\Illuminate\Http\JsonResponse
     */
    public function store(StoreSpaceRequest $request)
    {
        $created = $this->spaceStore($request);

        if ($created) {
            return SpaceResource::make($created);
        }

        return response()->json([
            'message' => __('Resource not found.'),
            'status' => 404
        ], 404);
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param int $id
     * @return SpaceResource|\Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id)
    {
        $link = Space::where([['id', '=', $id], ['user_id', $request->user()->id]])->first();

        if ($link) {
            return SpaceResource::make($link);
        }

        return response()->json([
            'message' => __('Resource not found.'),
            'status' => 404
        ], 404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateSpaceRequest $request
     * @param int $id
     * @return SpaceResource
     */
    public function update(UpdateSpaceRequest $request, $id)
    {
        $space = Space::where([['id', '=', $id], ['user_id', '=', $request->user()->id]])->firstOrFail();

        $updated = $this->spaceUpdate($request, $space);

        if ($updated) {
            return SpaceResource::make($updated);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Request $request, $id)
    {
        $space = Space::where([['id', '=', $id], ['user_id', '=', $request->user()->id]])->first();

        if ($space) {
            $space->delete();

            return response()->json([
                'id' => $space->id,
                'object' => 'space',
                'deleted' => true,
                'status' => 200
            ], 200);
        }

        return response()->json([
            'message' => __('Resource not found.'),
            'status' => 404
        ], 404);
    }
}
