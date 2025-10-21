<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Price;
use App\Services\SortOrderService;
use App\Support\Concerns\SanitizesAttributes;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class PriceController extends Controller
{
    use SanitizesAttributes;

    public function __construct(private readonly SortOrderService $sortOrderService)
    {
    }

    /**
     * Display the list of prices.
     */
    public function index(): View
    {
        $prices = Price::query()
            ->orderBy('sort')
            ->orderBy('id')
            ->get();

        return view('admin.prices.index', [
            'prices' => $prices,
        ]);
    }

    /**
     * Show the form for creating a new price entry.
     */
    public function create(): View
    {
        return view('admin.prices.create');
    }

    /**
     * Store a newly created price entry.
     */
    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make(
            $request->all(),
            $this->rules(),
            [],
            $this->attributes(),
        );

        $validated = $validator->validateWithBag('createPrice');

        $data = $this->prepareData($validated);

        $data['sort'] = $this->sortOrderService->nextSortValue(Price::class);

        Price::create($data);

        return redirect()
            ->route('admin.prices.index')
            ->with('status', 'price-created');
    }

    /**
     * Show the form for editing the specified price entry.
     */
    public function edit(Price $price): View
    {
        return view('admin.prices.edit', [
            'price' => $price,
        ]);
    }

    /**
     * Update the specified price entry.
     */
    public function update(Request $request, Price $price): RedirectResponse
    {
        $validator = Validator::make(
            $request->all(),
            $this->rules(),
            [],
            $this->attributes(),
        );

        $validated = $validator->validateWithBag('updatePrice' . $price->id);

        $data = $this->prepareData($validated);

        $price->update($data);

        return redirect()
            ->route('admin.prices.index')
            ->with('status', 'price-updated');
    }

    /**
     * Remove the specified price entry.
     */
    public function destroy(Price $price): RedirectResponse
    {
        $price->delete();

        return redirect()
            ->route('admin.prices.index')
            ->with('status', 'price-deleted');
    }

    /**
     * Validation rules for price forms.
     */
    private function rules(): array
    {
        return [
            'name_ru' => ['required', 'string', 'max:255'],
            'name_en' => ['required', 'string', 'max:255'],
            'col1_ru' => ['nullable', 'string', 'max:255'],
            'col1_en' => ['nullable', 'string', 'max:255'],
            'col2_ru' => ['nullable', 'string', 'max:255'],
            'col2_en' => ['nullable', 'string', 'max:255'],
            'col3_ru' => ['nullable', 'string', 'max:255'],
            'col3_en' => ['nullable', 'string', 'max:255'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    /**
     * Human readable attribute names.
     */
    private function attributes(): array
    {
        return [
            'name_ru' => __('admin.prices.fields.name_ru'),
            'name_en' => __('admin.prices.fields.name_en'),
            'col1_ru' => __('admin.prices.fields.col1_ru'),
            'col1_en' => __('admin.prices.fields.col1_en'),
            'col2_ru' => __('admin.prices.fields.col2_ru'),
            'col2_en' => __('admin.prices.fields.col2_en'),
            'col3_ru' => __('admin.prices.fields.col3_ru'),
            'col3_en' => __('admin.prices.fields.col3_en'),
            'is_active' => __('admin.prices.fields.is_active'),
        ];
    }

    /**
     * Prepare sanitized data for persistence.
     */
    private function prepareData(array $input): array
    {
        return [
            'name_ru' => $this->sanitizeString($input['name_ru'] ?? ''),
            'name_en' => $this->sanitizeString($input['name_en'] ?? ''),
            'col1_ru' => $this->sanitizeNullableString($input['col1_ru'] ?? null),
            'col1_en' => $this->sanitizeNullableString($input['col1_en'] ?? null),
            'col2_ru' => $this->sanitizeNullableString($input['col2_ru'] ?? null),
            'col2_en' => $this->sanitizeNullableString($input['col2_en'] ?? null),
            'col3_ru' => $this->sanitizeNullableString($input['col3_ru'] ?? null),
            'col3_en' => $this->sanitizeNullableString($input['col3_en'] ?? null),
            'is_active' => $this->sanitizeBoolean($input['is_active'] ?? null),
        ];
    }

    /**
     * Update the sort order for price tiles.
     */
    public function reorder(Request $request): JsonResponse
    {
        $data = $request->validate([
            'order' => ['required', 'array'],
            'order.*' => ['integer', 'distinct', 'exists:prices,id'],
        ]);

        $ids = $data['order'];

        $this->sortOrderService->reorder(Price::class, $ids);

        return response()->json(['status' => 'ok']);
    }
}
