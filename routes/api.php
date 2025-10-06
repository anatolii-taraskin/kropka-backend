<?php

use Illuminate\Support\Facades\Route;

Route::middleware('resolve.locale')->group(function () {
    Route::get('/status', function () {
        return response()->json([
            'status' => 'ok',
            'locale' => app()->getLocale(),
        ]);
    });
});
