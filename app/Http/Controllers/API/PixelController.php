<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\StorePixelRequest;
use App\Http\Requests\API\UpdatePixelRequest;
use App\Http\Resources\PixelResource;
use App\Models\Pixel;
use App\Traits\PixelTrait;
use Illuminate\Http\Request;

class PixelController extends Controller
{
    use PixelTrait;

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
        $type = $request->input('type');
        $sortBy = in_array($request->input('sort_by'), ['id', 'name']) ? $request->input('sort_by') : 'id';
        $sort = in_array($request->input('sort'), ['asc', 'desc']) ? $request->input('sort') : 'desc';
        $perPage = in_array($request->input('per_page'), [10, 25, 50, 100]) ? $request->input('per_page') : config('settings.paginate');

        return PixelResource::collection(Pixel::where('user_id', $request->user()->id)
            ->when($search, function ($query) use ($search, $searchBy) {
                return $query->searchName($search);
            })->when($type, function ($query) use ($type) {
                return $query->ofType($type);
            })
            ->orderBy($sortBy, $sort)
            ->paginate($perPage)
            ->appends(['search' => $search, 'search_by' => $searchBy, 'type' => $type, 'sort_by' => $sortBy, 'sort' => $sort, 'per_page' => $perPage]))
            ->additional(['status' => 200]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StorePixelRequest $request
     * @return PixelResource|\Illuminate\Http\JsonResponse
     */
    public function store(StorePixelRequest $request)
    {
        $created = $this->pixelStore($request);

        if ($created) {
            return PixelResource::make($created);
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
     * @return PixelResource|\Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id)
    {
        $link = Pixel::where([['id', '=', $id], ['user_id', $request->user()->id]])->first();

        if ($link) {
            return PixelResource::make($link);
        }

        return response()->json([
            'message' => __('Resource not found.'),
            'status' => 404
        ], 404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdatePixelRequest $request
     * @param int $id
     * @return PixelResource
     */
    public function update(UpdatePixelRequest $request, $id)
    {
        $pixel = Pixel::where([['id', '=', $id], ['user_id', '=', $request->user()->id]])->firstOrFail();

        $updated = $this->pixelUpdate($request, $pixel);

        if ($updated) {
            return PixelResource::make($updated);
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
        $pixel = Pixel::where([['id', '=', $id], ['user_id', '=', $request->user()->id]])->first();

        if ($pixel) {
            $pixel->delete();

            return response()->json([
                'id' => $pixel->id,
                'object' => 'pixel',
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
