<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')->middleware('auth:api', 'throttle:120')->group(function () {
    Route::apiResource('links', 'API\LinkController', ['parameters' => [
        'links' => 'id'
    ], 'as' => 'api'])->middleware('api.guard');

    Route::apiResource('domains', 'API\DomainController', ['parameters' => [
        'domains' => 'id'
    ], 'as' => 'api'])->middleware('api.guard');

    Route::apiResource('spaces', 'API\SpaceController', ['parameters' => [
        'spaces' => 'id'
    ], 'as' => 'api'])->middleware('api.guard');

    Route::apiResource('pixels', 'API\PixelController', ['parameters' => [
        'pixels' => 'id'
    ], 'as' => 'api'])->middleware('api.guard');

    Route::apiResource('stats', 'API\StatController', ['parameters' => [
        'stats' => 'id'
    ], 'only' => ['show'], 'as' => 'api']);

    Route::apiResource('account', 'API\AccountController', ['only' => [
        'index'
    ], 'as' => 'api'])->middleware('api.guard');

    Route::fallback(function () {
        return response()->json(['message' => __('Resource not found.'), 'status' => 404], 404);
    });
});