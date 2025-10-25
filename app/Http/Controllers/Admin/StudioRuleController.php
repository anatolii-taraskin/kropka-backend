<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StudioRule\ReorderStudioRuleRequest;
use App\Http\Requests\Admin\StudioRule\StoreStudioRuleRequest;
use App\Http\Requests\Admin\StudioRule\UpdateStudioRuleRequest;
use App\Http\Services\StudioRuleService;
use App\Models\StudioRule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class StudioRuleController extends Controller
{
    public function __construct(
        private readonly StudioRuleService $studioRuleService,
    )
    {
    }

    /**
     * Display the list of studio rules.
     */
    public function index(): View
    {
        $rules = StudioRule::query()
            ->orderBy('sort')
            ->orderBy('id')
            ->get();

        return view('admin.studio-rules.index', [
            'rules' => $rules,
        ]);
    }

    /**
     * Show the form for creating a new studio rule.
     */
    public function create(): View
    {
        return view('admin.studio-rules.create');
    }

    /**
     * Store a newly created studio rule.
     */
    public function store(StoreStudioRuleRequest $request): RedirectResponse
    {
        $this->studioRuleService->create($request);

        return redirect()
            ->route('admin.studio-rules.index')
            ->with('status', 'studio-rule-created');
    }

    /**
     * Show the form for editing the specified studio rule.
     */
    public function edit(StudioRule $studioRule): View
    {
        return view('admin.studio-rules.edit', [
            'studioRule' => $studioRule,
        ]);
    }

    /**
     * Update the specified studio rule.
     */
    public function update(UpdateStudioRuleRequest $request, StudioRule $studioRule): RedirectResponse
    {
        $this->studioRuleService->update($request, $studioRule);

        return redirect()
            ->route('admin.studio-rules.index')
            ->with('status', 'studio-rule-updated');
    }

    /**
     * Remove the specified studio rule.
     */
    public function destroy(StudioRule $studioRule): RedirectResponse
    {
        $this->studioRuleService->delete($studioRule);

        return redirect()
            ->route('admin.studio-rules.index')
            ->with('status', 'studio-rule-deleted');
    }

    /**
     * Update the sort order for studio rules.
     */
    public function reorder(ReorderStudioRuleRequest $request): JsonResponse
    {
        $this->studioRuleService->reorder($request->ids());

        return response()->json(['status' => 'ok']);
    }
}
