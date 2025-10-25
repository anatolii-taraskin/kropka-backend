<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StudioInfoRequest;
use App\Services\StudioInfoFieldFactory;
use App\Services\StudioInfoRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class StudioInfoController extends Controller
{
    public function __construct(
        private readonly StudioInfoFieldFactory $fieldFactory,
        private readonly StudioInfoRepository $repository,
    )
    {
    }

    /**
     * Display the form for editing studio information entries.
     */
    public function edit(): View
    {
        return view('admin.studio-infos.edit', [
            'fieldGroups' => $this->fieldFactory->fieldGroups(
                $this->repository->all()
            ),
        ]);
    }

    /**
     * Persist studio information updates.
     */
    public function update(StudioInfoRequest $request): RedirectResponse
    {
        $this->repository->save(
            $request->studioInfos(),
            $this->fieldFactory->properties()
        );

        return redirect()
            ->route('admin.studio-infos.edit')
            ->with('status', 'studio-infos-updated');
    }
}
