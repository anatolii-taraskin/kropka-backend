<x-admin.page
    :title="__('admin.prices.title')"
    container-class="bg-transparent shadow-none p-0 space-y-6"
    :content-class="null"
>
    @php
        $updateErrors = $errors->getBag('updatePrice' . $price->id);
        $useOldValues = ! $updateErrors->isEmpty();
        $updateIsActive = $useOldValues ? (old('is_active', '0') === '1') : (bool) $price->is_active;
        $updatedAt = $price->updated_at ? $price->updated_at->format(__('admin.prices.date_format')) : null;
    @endphp

    <section class="bg-white shadow sm:rounded-lg">
        <div class="flex flex-col gap-4 p-6 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <h2 class="text-lg font-medium text-gray-900">
                    {{ __('admin.prices.edit_title') }}
                </h2>
                <p class="mt-1 text-sm text-gray-600">
                    {{ __('admin.prices.edit_description') }}
                </p>

                @if ($updatedAt)
                    <p class="mt-3 text-xs text-gray-500">
                        {{ __('admin.prices.updated_at', ['date' => $updatedAt]) }}
                    </p>
                @endif
            </div>

            <a
                href="{{ route('admin.prices.index') }}"
                class="inline-flex items-center rounded-md bg-white px-3 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 transition duration-150 ease-in-out hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
            >
                {{ __('admin.prices.back_to_list') }}
            </a>
        </div>

        <form method="post" action="{{ route('admin.prices.update', $price) }}" class="border-t border-gray-200 p-6 space-y-6">
            @csrf
            @method('put')

            <div>
                <x-input-label for="price_{{ $price->id }}_name" :value="__('admin.prices.fields.name')" />
                <x-text-input
                    id="price_{{ $price->id }}_name"
                    name="name"
                    type="text"
                    class="mt-1 block w-full"
                    value="{{ $useOldValues ? old('name') : $price->name }}"
                    required
                    maxlength="255"
                />
                <x-input-error class="mt-2" :messages="$updateErrors->get('name')" />
            </div>

            <div class="grid gap-6 md:grid-cols-3">
                <div>
                    <x-input-label for="price_{{ $price->id }}_col1" :value="__('admin.prices.fields.col1')" />
                    <x-text-input
                        id="price_{{ $price->id }}_col1"
                        name="col1"
                        type="text"
                        class="mt-1 block w-full"
                        value="{{ $useOldValues ? old('col1') : $price->col1 }}"
                        maxlength="255"
                    />
                    <x-input-error class="mt-2" :messages="$updateErrors->get('col1')" />
                </div>

                <div>
                    <x-input-label for="price_{{ $price->id }}_col2" :value="__('admin.prices.fields.col2')" />
                    <x-text-input
                        id="price_{{ $price->id }}_col2"
                        name="col2"
                        type="text"
                        class="mt-1 block w-full"
                        value="{{ $useOldValues ? old('col2') : $price->col2 }}"
                        maxlength="255"
                    />
                    <x-input-error class="mt-2" :messages="$updateErrors->get('col2')" />
                </div>

                <div>
                    <x-input-label for="price_{{ $price->id }}_col3" :value="__('admin.prices.fields.col3')" />
                    <x-text-input
                        id="price_{{ $price->id }}_col3"
                        name="col3"
                        type="text"
                        class="mt-1 block w-full"
                        value="{{ $useOldValues ? old('col3') : $price->col3 }}"
                        maxlength="255"
                    />
                    <x-input-error class="mt-2" :messages="$updateErrors->get('col3')" />
                </div>
            </div>

            <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                <div>
                    <x-input-label for="price_{{ $price->id }}_sort" :value="__('admin.prices.fields.sort')" />
                    <x-text-input
                        id="price_{{ $price->id }}_sort"
                        name="sort"
                        type="number"
                        min="0"
                        max="255"
                        class="mt-1 block w-32"
                        value="{{ $useOldValues ? old('sort') : $price->sort }}"
                        required
                    />
                    <x-input-error class="mt-2" :messages="$updateErrors->get('sort')" />
                </div>

                <div class="flex items-center">
                    <input type="hidden" name="is_active" value="0" />
                    <input
                        id="price_{{ $price->id }}_is_active"
                        name="is_active"
                        type="checkbox"
                        value="1"
                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                        @checked($updateIsActive)
                    />
                    <label for="price_{{ $price->id }}_is_active" class="ms-2 text-sm text-gray-600">
                        {{ __('admin.prices.fields.is_active') }}
                    </label>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <x-primary-button>
                    {{ __('admin.prices.submit_update') }}
                </x-primary-button>

                <a
                    href="{{ route('admin.prices.index') }}"
                    class="text-sm text-gray-500 transition duration-150 ease-in-out hover:text-gray-700"
                >
                    {{ __('admin.prices.cancel') }}
                </a>
            </div>
        </form>
    </section>
</x-admin.page>
