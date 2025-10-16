<?php

use App\Http\Controllers\Admin\EquipmentController;
use App\Http\Controllers\Admin\PriceController;
use App\Http\Controllers\Admin\StudioInfoController;
use App\Http\Controllers\Admin\StudioRuleController;
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

    Route::get('/admin/prices', [PriceController::class, 'index'])
        ->name('admin.prices.index');

    Route::get('/admin/prices/create', [PriceController::class, 'create'])
        ->name('admin.prices.create');

    Route::post('/admin/prices', [PriceController::class, 'store'])
        ->name('admin.prices.store');

    Route::post('/admin/prices/reorder', [PriceController::class, 'reorder'])
        ->name('admin.prices.reorder');

    Route::get('/admin/prices/{price}/edit', [PriceController::class, 'edit'])
        ->name('admin.prices.edit');

    Route::put('/admin/prices/{price}', [PriceController::class, 'update'])
        ->name('admin.prices.update');

    Route::delete('/admin/prices/{price}', [PriceController::class, 'destroy'])
        ->name('admin.prices.destroy');

    Route::get('/admin/equipment', [EquipmentController::class, 'index'])
        ->name('admin.equipment.index');

    Route::get('/admin/equipment/create', [EquipmentController::class, 'create'])
        ->name('admin.equipment.create');

    Route::post('/admin/equipment', [EquipmentController::class, 'store'])
        ->name('admin.equipment.store');

    Route::post('/admin/equipment/reorder', [EquipmentController::class, 'reorder'])
        ->name('admin.equipment.reorder');

    Route::get('/admin/equipment/{equipment}/edit', [EquipmentController::class, 'edit'])
        ->name('admin.equipment.edit');

    Route::put('/admin/equipment/{equipment}', [EquipmentController::class, 'update'])
        ->name('admin.equipment.update');

    Route::delete('/admin/equipment/{equipment}', [EquipmentController::class, 'destroy'])
        ->name('admin.equipment.destroy');

    Route::get('/admin/studio-rules', [StudioRuleController::class, 'edit'])
        ->name('admin.studio-rules.edit');

    Route::put('/admin/studio-rules', [StudioRuleController::class, 'update'])
        ->name('admin.studio-rules.update');

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
