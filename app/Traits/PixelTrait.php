<?php


namespace App\Traits;

use App\Models\Pixel;
use Illuminate\Http\Request;

trait PixelTrait
{
    /**
     * Store the Pixel.
     *
     * @param Request $request
     * @return Pixel
     */
    protected function pixelStore(Request $request)
    {
        $pixel = new Pixel;

        $pixel->name = $request->input('name');
        $pixel->user_id = $request->user()->id;
        $pixel->type = $request->input('type');
        $pixel->value = $request->input('value');
        $pixel->save();

        return $pixel;
    }

    /**
     * Update the Pixel.
     *
     * @param Request $request
     * @param Pixel $pixel
     * @return Pixel
     */
    protected function pixelUpdate(Request $request, Pixel $pixel)
    {
        if ($request->has('name')) {
            $pixel->name = $request->input('name');
        }

        if ($request->has('type')) {
            $pixel->type = $request->input('type');
        }

        if ($request->has('value')) {
            $pixel->value = $request->input('value');
        }

        $pixel->save();

        return $pixel;
    }
}