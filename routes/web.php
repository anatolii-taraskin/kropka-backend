<?php

use App\Http\Controllers\Admin\StudioInfoController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/'.config('app.locale'));

Route::prefix('{locale}')
    ->whereIn('locale', config('app.supported_locales', []))
    ->middleware('set.locale')
    ->name('localized.')
    ->group(function () {
        Route::get('/', function () {
            return view('welcome');
        })->name('welcome');
    });

Route::middleware(['auth', 'admin', 'resolve.locale:admin'])->group(function () {
    Route::get('/admin', function () {
        return view('admin.panel');
    })->name('admin.panel');

    Route::get('/admin/studio-infos', [StudioInfoController::class, 'edit'])
        ->name('admin.studio-infos.edit');

    Route::put('/admin/studio-infos', [StudioInfoController::class, 'update'])
        ->name('admin.studio-infos.update');

    Route::get('/admin/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');
    Route::patch('/admin/profile', [ProfileController::class, 'update'])
        ->name('profile.update');
    Route::delete('/admin/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

Route::middleware('resolve.locale')->group(function () {
    require __DIR__.'/auth.php';
});
