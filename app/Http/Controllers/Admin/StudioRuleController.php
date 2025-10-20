<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudioRule;
use App\Services\SortOrderService;
use App\Support\Concerns\SanitizesAttributes;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class StudioRuleController extends Controller
{
    use SanitizesAttributes;

    public function __construct(private readonly SortOrderService $sortOrderService)
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
    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make(
            $request->all(),
            $this->rules(),
            [],
            $this->attributes(),
        );

        $validated = $validator->validateWithBag('createStudioRule');

        $data = $this->prepareData($validated);
        $data['sort'] = $this->sortOrderService->nextSortValue(StudioRule::class);
        $data['property'] = $this->generateProperty();

        StudioRule::create($data);

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
    public function update(Request $request, StudioRule $studioRule): RedirectResponse
    {
        $validator = Validator::make(
            $request->all(),
            $this->rules(),
            [],
            $this->attributes(),
        );

        $validated = $validator->validateWithBag('updateStudioRule' . $studioRule->id);

        $data = $this->prepareData($validated);

        $studioRule->update($data);

        return redirect()
            ->route('admin.studio-rules.index')
            ->with('status', 'studio-rule-updated');
    }

    /**
     * Remove the specified studio rule.
     */
    public function destroy(StudioRule $studioRule): RedirectResponse
    {
        $studioRule->delete();

        return redirect()
            ->route('admin.studio-rules.index')
            ->with('status', 'studio-rule-deleted');
    }

    /**
     * Update the sort order for studio rules.
     */
    public function reorder(Request $request): JsonResponse
    {
        $data = $request->validate([
            'order' => ['required', 'array'],
            'order.*' => ['integer', 'distinct', 'exists:studio_rules,id'],
        ]);

        $this->sortOrderService->reorder(StudioRule::class, $data['order']);

        return response()->json(['status' => 'ok']);
    }

    /**
     * Validation rules for studio rule forms.
     */
    private function rules(): array
    {
        return [
            'value' => ['required', 'string', 'max:2000'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    /**
     * Human readable attribute names.
     */
    private function attributes(): array
    {
        return [
            'value' => __('admin.studio_rules.fields.value'),
            'is_active' => __('admin.studio_rules.fields.is_active'),
        ];
    }

    /**
     * Prepare sanitized data for persistence.
     */
    private function prepareData(array $input): array
    {
        return [
            'value' => $this->sanitizeString($input['value'] ?? ''),
            'is_active' => $this->sanitizeBoolean($input['is_active'] ?? null, true),
        ];
    }

    private function generateProperty(): string
    {
        $nextNumber = (StudioRule::max('id') ?? 0) + 1;

        return sprintf('rule_%02d', $nextNumber);
    }
}
