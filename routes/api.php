<?php

use App\Http\Controllers\Api\Public\EquipmentController;
use App\Http\Controllers\Api\Public\PriceController;
use App\Http\Controllers\Api\Public\StudioController;
use App\Http\Controllers\Api\Public\StudioRuleController;
use App\Http\Controllers\Api\Public\TeacherController;
use Illuminate\Support\Facades\Route;

Route::middleware('resolve.locale')->group(function () {
    Route::get('/status', function () {
        return response()->json([
            'status' => 'ok',
            'locale' => app()->getLocale(),
        ]);
    });

    Route::prefix('v1')->group(function () {
        Route::get('/studio', StudioController::class);
        Route::get('/prices', PriceController::class);
        Route::get('/equipment', EquipmentController::class);
        Route::get('/teachers', TeacherController::class);
        Route::get('/rules', StudioRuleController::class);
    });
});
