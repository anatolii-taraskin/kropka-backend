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

    <section class="bg-white shadow sm:rounded-lg overflow-hidden">
        <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4">
            <div class="space-y-1">
                <h2 class="text-lg font-medium text-gray-900">
                    {{ __('admin.prices.edit_title') }}
                </h2>

                @if ($updatedAt)
                    <p class="text-xs text-gray-500">
                        {{ __('admin.prices.updated_at', ['date' => $updatedAt]) }}
                    </p>
                @endif
            </div>

            <a
                href="{{ route('admin.prices.index') }}"
                class="inline-flex items-center gap-2 rounded-md border border-transparent bg-gray-100 px-3 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 transition ease-in-out duration-150 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2"
            >
                {{ __('admin.prices.back_to_list') }}
            </a>
        </div>

        <form
            method="post"
            action="{{ route('admin.prices.update', $price) }}"
            class="form-first-child-tight border-t border-gray-200 p-6 space-y-6"
        >
            @csrf
            @method('put')

            <div class="first-element">
                <div class="grid gap-6 md:grid-cols-2">
                    <div>
                        <x-input-label for="price_{{ $price->id }}_name_ru" :value="__('admin.prices.fields.name_ru')" />
                        <x-text-input
                            id="price_{{ $price->id }}_name_ru"
                            name="name_ru"
                            type="text"
                            class="mt-1 block w-full"
                            value="{{ $useOldValues ? old('name_ru') : $price->name_ru }}"
                            required
                            maxlength="255"
                        />
                        <x-input-error class="mt-2" :messages="$updateErrors->get('name_ru')" />
                    </div>

                    <div>
                        <x-input-label for="price_{{ $price->id }}_name_en" :value="__('admin.prices.fields.name_en')" />
                        <x-text-input
                            id="price_{{ $price->id }}_name_en"
                            name="name_en"
                            type="text"
                            class="mt-1 block w-full"
                            value="{{ $useOldValues ? old('name_en') : $price->name_en }}"
                            required
                            maxlength="255"
                        />
                        <x-input-error class="mt-2" :messages="$updateErrors->get('name_en')" />
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="grid gap-6 md:grid-cols-2">
                    <div>
                        <x-input-label for="price_{{ $price->id }}_col1_ru" :value="__('admin.prices.fields.col1_ru')" />
                        <x-text-input
                            id="price_{{ $price->id }}_col1_ru"
                            name="col1_ru"
                            type="text"
                            class="mt-1 block w-full"
                            value="{{ $useOldValues ? old('col1_ru') : $price->col1_ru }}"
                            maxlength="255"
                        />
                        <x-input-error class="mt-2" :messages="$updateErrors->get('col1_ru')" />
                    </div>

                    <div>
                        <x-input-label for="price_{{ $price->id }}_col1_en" :value="__('admin.prices.fields.col1_en')" />
                        <x-text-input
                            id="price_{{ $price->id }}_col1_en"
                            name="col1_en"
                            type="text"
                            class="mt-1 block w-full"
                            value="{{ $useOldValues ? old('col1_en') : $price->col1_en }}"
                            maxlength="255"
                        />
                        <x-input-error class="mt-2" :messages="$updateErrors->get('col1_en')" />
                    </div>
                </div>

                <div class="grid gap-6 md:grid-cols-2">
                    <div>
                        <x-input-label for="price_{{ $price->id }}_col2_ru" :value="__('admin.prices.fields.col2_ru')" />
                        <x-text-input
                            id="price_{{ $price->id }}_col2_ru"
                            name="col2_ru"
                            type="text"
                            class="mt-1 block w-full"
                            value="{{ $useOldValues ? old('col2_ru') : $price->col2_ru }}"
                            maxlength="255"
                        />
                        <x-input-error class="mt-2" :messages="$updateErrors->get('col2_ru')" />
                    </div>

                    <div>
                        <x-input-label for="price_{{ $price->id }}_col2_en" :value="__('admin.prices.fields.col2_en')" />
                        <x-text-input
                            id="price_{{ $price->id }}_col2_en"
                            name="col2_en"
                            type="text"
                            class="mt-1 block w-full"
                            value="{{ $useOldValues ? old('col2_en') : $price->col2_en }}"
                            maxlength="255"
                        />
                        <x-input-error class="mt-2" :messages="$updateErrors->get('col2_en')" />
                    </div>
                </div>

                <div class="grid gap-6 md:grid-cols-2">
                    <div>
                        <x-input-label for="price_{{ $price->id }}_col3_ru" :value="__('admin.prices.fields.col3_ru')" />
                        <x-text-input
                            id="price_{{ $price->id }}_col3_ru"
                            name="col3_ru"
                            type="text"
                            class="mt-1 block w-full"
                            value="{{ $useOldValues ? old('col3_ru') : $price->col3_ru }}"
                            maxlength="255"
                        />
                        <x-input-error class="mt-2" :messages="$updateErrors->get('col3_ru')" />
                    </div>

                    <div>
                        <x-input-label for="price_{{ $price->id }}_col3_en" :value="__('admin.prices.fields.col3_en')" />
                        <x-text-input
                            id="price_{{ $price->id }}_col3_en"
                            name="col3_en"
                            type="text"
                            class="mt-1 block w-full"
                            value="{{ $useOldValues ? old('col3_en') : $price->col3_en }}"
                            maxlength="255"
                        />
                        <x-input-error class="mt-2" :messages="$updateErrors->get('col3_en')" />
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-2 pb-3">
                <input type="hidden" name="is_active" value="0" />

                <input
                    id="price_{{ $price->id }}_is_active"
                    name="is_active"
                    type="checkbox"
                    value="1"
                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                    @checked($updateIsActive)
                />

                <label for="price_{{ $price->id }}_is_active" class="text-sm text-gray-600">
                    {{ __('admin.prices.fields.is_active') }}
                </label>
            </div>

            <div class="flex items-center gap-3">
                <x-primary-button>
                    {{ __('admin.prices.submit_update') }}
                </x-primary-button>

                <a
                    href="{{ route('admin.prices.index') }}"
                    class="inline-flex items-center gap-2 rounded-md border border-transparent bg-gray-100 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 transition ease-in-out duration-150 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2"
                >
                    {{ __('admin.prices.cancel') }}
                </a>
            </div>
        </form>
    </section>
</x-admin.page>
