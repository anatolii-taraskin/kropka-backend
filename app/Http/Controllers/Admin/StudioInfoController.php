<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StudioInfoRequest;
use App\Services\StudioInfoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class StudioInfoController extends Controller
{
    public function __construct(private readonly StudioInfoService $studioInfoService)
    {
    }

    /**
     * Display the form for editing studio information entries.
     */
    public function edit(): View
    {
        return view('admin.studio-infos.edit', [
            'fieldGroups' => $this->studioInfoService->fieldGroups(),
        ]);
    }

    /**
     * Persist studio information updates.
     */
    public function update(StudioInfoRequest $request): RedirectResponse
    {
        $this->studioInfoService->save($request->studioInfos());

        return redirect()
            ->route('admin.studio-infos.edit')
            ->with('status', 'studio-infos-updated');
    }
}
