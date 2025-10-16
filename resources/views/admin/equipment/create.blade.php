<x-admin.page
    :title="__('admin.equipment.title')"
    container-class="bg-transparent shadow-none p-0 space-y-6"
    :content-class="null"
>
    @php
        $createErrors = $errors->getBag('createEquipment');
        $useOldValues = ! $createErrors->isEmpty();
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

        <form method="post" action="{{ route('admin.equipment.store') }}" enctype="multipart/form-data" class="border-t border-gray-200 p-6 space-y-6">
            @csrf

            <div>
                <x-input-label for="equipment_name" :value="__('admin.equipment.fields.name')" />

                <x-text-input
                    id="equipment_name"
                    name="name"
                    type="text"
                    class="mt-1 block w-full"
                    value="{{ $useOldValues ? old('name') : '' }}"
                    required
                    maxlength="255"
                />

                <x-input-error class="mt-2" :messages="$createErrors->get('name')" />
            </div>

            <div>
                <x-input-label for="equipment_description" :value="__('admin.equipment.fields.description')" />

                <textarea
                    id="equipment_description"
                    name="description"
                    rows="5"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                >{{ $useOldValues ? old('description') : '' }}</textarea>

                <x-input-error class="mt-2" :messages="$createErrors->get('description')" />
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
