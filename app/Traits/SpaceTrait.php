<?php


namespace App\Traits;

use App\Models\Space;
use Illuminate\Http\Request;

trait SpaceTrait
{
    /**
     * Store the Space.
     *
     * @param Request $request
     * @return Space
     */
    protected function spaceStore(Request $request)
    {
        $space = new Space;

        $space->name = $request->input('name');
        $space->user_id = $request->user()->id;
        $space->color = array_key_exists($request->input('color'), formatSpace()) ? $request->input('color') : 1;
        $space->save();

        return $space;
    }

    /**
     * Update the Space.
     *
     * @param Request $request
     * @param Space $space
     * @return Space
     */
    protected function spaceUpdate(Request $request, Space $space)
    {
        if ($request->has('name')) {
            $space->name = $request->input('name');
        }

        if ($request->has('color')) {
            $space->color = $request->input('color');
        }

        $space->save();

        return $space;
    }
}