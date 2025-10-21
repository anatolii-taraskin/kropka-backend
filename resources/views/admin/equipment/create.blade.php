<x-admin.page
    :title="__('admin.equipment.title')"
    container-class="bg-transparent shadow-none p-0 space-y-6"
    :content-class="null"
>
    @php
        $createErrors = $errors->getBag('createEquipment');
        $useOldValues = ! $createErrors->isEmpty();
        $createIsActive = $createErrors->isEmpty() ? true : (old('is_active', '0') === '1');
    @endphp

    <section class="bg-white shadow sm:rounded-lg overflow-hidden">
        <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4">
            <h2 class="text-lg font-medium text-gray-900">
                {{ __('admin.equipment.create_title') }}
            </h2>

            <a
                href="{{ route('admin.equipment.index') }}"
                class="inline-flex items-center gap-2 rounded-md border border-transparent bg-gray-100 px-3 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 transition ease-in-out duration-150 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2"
            >
                {{ __('admin.equipment.back_to_list') }}
            </a>
        </div>

        <form
            method="post"
            action="{{ route('admin.equipment.store') }}"
            enctype="multipart/form-data"
            class="border-t border-gray-200 p-6 space-y-6"
        >
            @csrf

            <div class="space-y-6">
                <section class="first-element rounded-xl border border-gray-200 bg-white/40 p-6 shadow-sm space-y-6">
                    <header class="mb-2">
                        <h3 class="text-base font-semibold text-gray-900">
                            {{ __('admin.equipment.sections.ru') }}
                        </h3>
                    </header>

                    <div>
                        <x-input-label for="equipment_name_ru" :value="__('admin.equipment.fields.name_ru')" />

                        <x-text-input
                            id="equipment_name_ru"
                            name="name_ru"
                            type="text"
                            class="mt-1 block w-full"
                            value="{{ $useOldValues ? old('name_ru') : '' }}"
                            required
                            maxlength="255"
                        />

                        <x-input-error class="mt-2" :messages="$createErrors->get('name_ru')" />
                    </div>

                    <div>
                        <x-input-label for="equipment_description_ru" :value="__('admin.equipment.fields.description_ru')" />

                        <textarea
                            id="equipment_description_ru"
                            name="description_ru"
                            rows="5"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >{{ $useOldValues ? old('description_ru') : '' }}</textarea>

                        <x-input-error class="mt-2" :messages="$createErrors->get('description_ru')" />
                    </div>
                </section>

                <section class="rounded-xl border border-gray-200 bg-white/40 p-6 shadow-sm space-y-6">
                    <header class="mb-2">
                        <h3 class="text-base font-semibold text-gray-900">
                            {{ __('admin.equipment.sections.en') }}
                        </h3>
                    </header>

                    <div>
                        <x-input-label for="equipment_name_en" :value="__('admin.equipment.fields.name_en')" />

                        <x-text-input
                            id="equipment_name_en"
                            name="name_en"
                            type="text"
                            class="mt-1 block w-full"
                            value="{{ $useOldValues ? old('name_en') : '' }}"
                            required
                            maxlength="255"
                        />

                        <x-input-error class="mt-2" :messages="$createErrors->get('name_en')" />
                    </div>

                    <div>
                        <x-input-label for="equipment_description_en" :value="__('admin.equipment.fields.description_en')" />

                        <textarea
                            id="equipment_description_en"
                            name="description_en"
                            rows="5"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >{{ $useOldValues ? old('description_en') : '' }}</textarea>

                        <x-input-error class="mt-2" :messages="$createErrors->get('description_en')" />
                    </div>
                </section>
            </div>

            <div>
                <x-input-label for="equipment_photo" :value="__('admin.equipment.fields.photo')" />

                <input
                    id="equipment_photo"
                    name="photo"
                    type="file"
                    accept="image/*"
                    class="mt-1 block w-full text-sm text-gray-900 file:mr-4 file:rounded-md file:border-0 file:bg-gray-100 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-gray-700 hover:file:bg-gray-200"
                />

                <x-input-error class="mt-2" :messages="$createErrors->get('photo')" />
            </div>

            <div class="flex items-center gap-2 pb-3">
                <input type="hidden" name="is_active" value="0" />

                <input
                    id="equipment_is_active"
                    name="is_active"
                    type="checkbox"
                    value="1"
                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                    @checked($createIsActive)
                />

                <label for="equipment_is_active" class="text-sm text-gray-600">
                    {{ __('admin.equipment.fields.is_active') }}
                </label>
            </div>

            <div class="flex items-center gap-3">
                <x-primary-button>
                    {{ __('admin.equipment.submit_create') }}
                </x-primary-button>

                <a
                    href="{{ route('admin.equipment.index') }}"
                    class="inline-flex items-center gap-2 rounded-md border border-transparent bg-gray-100 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 transition ease-in-out duration-150 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2"
                >
                    {{ __('admin.equipment.cancel') }}
                </a>
            </div>
        </form>
    </section>
</x-admin.page>
