<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Price;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class PriceController extends Controller
{
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

        $data['sort'] = min((Price::max('sort') ?? 0) + 1, 255);

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
            'name' => ['required', 'string', 'max:255'],
            'col1' => ['nullable', 'string', 'max:255'],
            'col2' => ['nullable', 'string', 'max:255'],
            'col3' => ['nullable', 'string', 'max:255'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    /**
     * Human readable attribute names.
     */
    private function attributes(): array
    {
        return [
            'name' => __('admin.prices.fields.name'),
            'col1' => __('admin.prices.fields.col1'),
            'col2' => __('admin.prices.fields.col2'),
            'col3' => __('admin.prices.fields.col3'),
            'is_active' => __('admin.prices.fields.is_active'),
        ];
    }

    /**
     * Prepare sanitized data for persistence.
     */
    private function prepareData(array $input): array
    {
        $data = [
            'name' => $this->sanitizeString($input['name'] ?? ''),
            'col1' => $this->sanitizeNullableString($input['col1'] ?? null),
            'col2' => $this->sanitizeNullableString($input['col2'] ?? null),
            'col3' => $this->sanitizeNullableString($input['col3'] ?? null),
            'is_active' => array_key_exists('is_active', $input) ? (bool) $input['is_active'] : false,
        ];

        return $data;
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

        DB::transaction(function () use ($ids) {
            foreach ($ids as $index => $id) {
                Price::whereKey($id)->update(['sort' => min($index + 1, 255)]);
            }
        });

        return response()->json(['status' => 'ok']);
    }

    private function sanitizeString(string $value): string
    {
        return trim($value);
    }

    private function sanitizeNullableString(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $trimmed = trim($value);

        return $trimmed === '' ? null : $trimmed;
    }
}
