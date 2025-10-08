<x-admin.page
    :title="__('admin.prices.title')"
    container-class="bg-transparent shadow-none p-0 space-y-6"
    :content-class="null"
>
    @php
        $createErrors = $errors->getBag('createPrice');
        $status = session('status');
    @endphp

    @if ($status === 'price-created')
        <x-alert-success>
            {{ __('admin.prices.create_success') }}
        </x-alert-success>
    @elseif ($status === 'price-updated')
        <x-alert-success>
            {{ __('admin.prices.update_success') }}
        </x-alert-success>
    @elseif ($status === 'price-deleted')
        <x-alert-success>
            {{ __('admin.prices.delete_success') }}
        </x-alert-success>
    @endif

    <section class="bg-white shadow sm:rounded-lg p-6">
        <header>
            <h2 class="text-lg font-medium text-gray-900">
                {{ __('admin.prices.create_title') }}
            </h2>
            <p class="mt-1 text-sm text-gray-600">
                {{ __('admin.prices.create_description') }}
            </p>
        </header>

        <form method="post" action="{{ route('admin.prices.store') }}" class="mt-6 space-y-6">
            @csrf

            <div>
                <x-input-label for="price_name" :value="__('admin.prices.fields.name')" />
                <x-text-input
                    id="price_name"
                    name="name"
                    type="text"
                    class="mt-1 block w-full"
                    value="{{ $createErrors->isEmpty() ? '' : old('name') }}"
                    required
                    maxlength="255"
                />
                <x-input-error class="mt-2" :messages="$createErrors->get('name')" />
            </div>

            <div class="grid gap-6 md:grid-cols-3">
                <div>
                    <x-input-label for="price_col1" :value="__('admin.prices.fields.col1')" />
                    <x-text-input
                        id="price_col1"
                        name="col1"
                        type="text"
                        class="mt-1 block w-full"
                        value="{{ $createErrors->isEmpty() ? '' : old('col1') }}"
                        maxlength="255"
                    />
                    <x-input-error class="mt-2" :messages="$createErrors->get('col1')" />
                </div>

                <div>
                    <x-input-label for="price_col2" :value="__('admin.prices.fields.col2')" />
                    <x-text-input
                        id="price_col2"
                        name="col2"
                        type="text"
                        class="mt-1 block w-full"
                        value="{{ $createErrors->isEmpty() ? '' : old('col2') }}"
                        maxlength="255"
                    />
                    <x-input-error class="mt-2" :messages="$createErrors->get('col2')" />
                </div>

                <div>
                    <x-input-label for="price_col3" :value="__('admin.prices.fields.col3')" />
                    <x-text-input
                        id="price_col3"
                        name="col3"
                        type="text"
                        class="mt-1 block w-full"
                        value="{{ $createErrors->isEmpty() ? '' : old('col3') }}"
                        maxlength="255"
                    />
                    <x-input-error class="mt-2" :messages="$createErrors->get('col3')" />
                </div>
            </div>

            <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                <div>
                    <x-input-label for="price_sort" :value="__('admin.prices.fields.sort')" />
                    <x-text-input
                        id="price_sort"
                        name="sort"
                        type="number"
                        min="0"
                        max="255"
                        class="mt-1 block w-32"
                        value="{{ $createErrors->isEmpty() ? $nextSort : old('sort', $nextSort) }}"
                        required
                    />
                    <x-input-error class="mt-2" :messages="$createErrors->get('sort')" />
                </div>

                @php
                    $createIsActive = $createErrors->isEmpty() ? true : (old('is_active', '0') === '1');
                @endphp

                <div class="flex items-center">
                    <input type="hidden" name="is_active" value="0" />
                    <input
                        id="price_is_active"
                        name="is_active"
                        type="checkbox"
                        value="1"
                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                        @checked($createIsActive)
                    />
                    <label for="price_is_active" class="ms-2 text-sm text-gray-600">
                        {{ __('admin.prices.fields.is_active') }}
                    </label>
                </div>
            </div>

            <div class="flex items-center">
                <x-primary-button>
                    {{ __('admin.prices.submit_create') }}
                </x-primary-button>
            </div>
        </form>
    </section>

    <section class="bg-white shadow sm:rounded-lg p-6">
        <header class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-lg font-medium text-gray-900">
                    {{ __('admin.prices.list_title') }}
                </h2>
                <p class="mt-1 text-sm text-gray-600">
                    {{ __('admin.prices.list_description') }}
                </p>
            </div>
        </header>

        @if ($prices->isEmpty())
            <p class="mt-6 text-sm text-gray-500">
                {{ __('admin.prices.empty') }}
            </p>
        @else
            <div class="mt-6 space-y-6">
                @foreach ($prices as $price)
                    @php
                        $updateErrors = $errors->getBag('updatePrice' . $price->id);
                        $useOldValues = ! $updateErrors->isEmpty();
                        $updatedAt = $price->updated_at ? $price->updated_at->format(__('admin.prices.date_format')) : null;
                    @endphp

                    <div class="border border-gray-200 rounded-lg bg-gray-50 p-4">
                        <form method="post" action="{{ route('admin.prices.update', $price) }}" class="space-y-4">
                            @csrf
                            @method('put')

                            <div class="grid gap-4 md:grid-cols-2">
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

                                <div class="grid gap-4 sm:grid-cols-3">
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

                                @php
                                    $updateIsActive = $useOldValues ? (old('is_active', '0') === '1') : (bool) $price->is_active;
                                @endphp

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

                            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                <div class="flex items-center gap-3">
                                    <x-primary-button>
                                        {{ __('admin.prices.submit_update') }}
                                    </x-primary-button>

                                    @if ($updatedAt)
                                        <span class="text-xs text-gray-500">
                                            {{ __('admin.prices.updated_at', ['date' => $updatedAt]) }}
                                        </span>
                                    @endif
                                </div>

                                <x-danger-button form="delete-price-{{ $price->id }}">
                                    {{ __('admin.prices.delete') }}
                                </x-danger-button>
                            </div>
                        </form>

                        <form
                            id="delete-price-{{ $price->id }}"
                            method="post"
                            action="{{ route('admin.prices.destroy', $price) }}"
                            class="hidden"
                            onsubmit="return confirm('{{ __('admin.prices.delete_confirm', ['name' => $price->name]) }}');"
                        >
                            @csrf
                            @method('delete')
                        </form>
                    </div>
                @endforeach
            </div>
        @endif
    </section>
</x-admin.page>
