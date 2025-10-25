<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Admin\Price\PriceManager;
use App\Actions\Admin\ReorderRecords;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Price\ReorderPriceRequest;
use App\Http\Requests\Admin\Price\StorePriceRequest;
use App\Http\Requests\Admin\Price\UpdatePriceRequest;
use App\Models\Price;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PriceController extends Controller
{
    public function __construct(
        private readonly PriceManager $priceManager,
        private readonly ReorderRecords $reorderRecords,
    )
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
    public function store(StorePriceRequest $request): RedirectResponse
    {
        $this->priceManager->create($request);

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
    public function update(UpdatePriceRequest $request, Price $price): RedirectResponse
    {
        $this->priceManager->update($request, $price);

        return redirect()
            ->route('admin.prices.index')
            ->with('status', 'price-updated');
    }

    /**
     * Remove the specified price entry.
     */
    public function destroy(Price $price): RedirectResponse
    {
        $this->priceManager->delete($price);

        return redirect()
            ->route('admin.prices.index')
            ->with('status', 'price-deleted');
    }

    /**
     * Update the sort order for price tiles.
     */
    public function reorder(ReorderPriceRequest $request): JsonResponse
    {
        $this->reorderRecords->handle(Price::class, $request->ids());

        return response()->json(['status' => 'ok']);
    }
}
