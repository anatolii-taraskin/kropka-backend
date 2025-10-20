<x-admin.page
    :title="__('admin.prices.title')"
    container-class="bg-transparent shadow-none p-0 space-y-6"
    :content-class="null"
>
    @php
        $createErrors = $errors->getBag('createPrice');
        $createIsActive = $createErrors->isEmpty() ? true : (old('is_active', '0') === '1');
    @endphp

    <section class="bg-white shadow sm:rounded-lg">
        <div class="flex items-start justify-between gap-4 p-6">
            <div>
                <h2 class="text-lg font-medium text-gray-900">
                    {{ __('admin.prices.create_title') }}
                </h2>
            </div>

            <a
                href="{{ route('admin.prices.index') }}"
                class="inline-flex items-center rounded-md bg-white px-3 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 transition duration-150 ease-in-out hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
            >
                {{ __('admin.prices.back_to_list') }}
            </a>
        </div>

        <form method="post" action="{{ route('admin.prices.store') }}" class="border-t border-gray-200 px-6 space-y-6">
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

            <div class="flex items-center gap-3">
                <x-primary-button>
                    {{ __('admin.prices.submit_create') }}
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
