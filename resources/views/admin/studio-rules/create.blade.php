<x-admin.page
    :title="__('admin.studio_rules.title')"
    container-class="bg-transparent shadow-none p-0 space-y-6"
    :content-class="null"
>
    @php
        $createErrors = $errors->getBag('createStudioRule');
        $useOldValues = ! $createErrors->isEmpty();
        $createIsActive = $createErrors->isEmpty() ? true : (old('is_active', '0') === '1');
    @endphp

    <section class="bg-white shadow sm:rounded-lg overflow-hidden">
        <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4">
            <h2 class="text-lg font-medium text-gray-900">
                {{ __('admin.studio_rules.create_title') }}
            </h2>

            <a
                href="{{ route('admin.studio-rules.index') }}"
                class="inline-flex items-center gap-2 rounded-md border border-transparent bg-gray-100 px-3 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 transition ease-in-out duration-150 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2"
            >
                {{ __('admin.studio_rules.back_to_list') }}
            </a>
        </div>

        <form
            method="post"
            action="{{ route('admin.studio-rules.store') }}"
            class="border-t border-gray-200 p-6 space-y-6"
        >
            @csrf

            <div>
                <x-input-label for="studio_rule_value" :value="__('admin.studio_rules.fields.value')" />

                <textarea
                    id="studio_rule_value"
                    name="value"
                    rows="5"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    required
                    maxlength="2000"
                >{{ $useOldValues ? old('value') : '' }}</textarea>

                <x-input-error class="mt-2" :messages="$createErrors->get('value')" />
            </div>

            <div class="flex items-center gap-2 pb-3">
                <input type="hidden" name="is_active" value="0" />

                <input
                    id="studio_rule_is_active"
                    name="is_active"
                    type="checkbox"
                    value="1"
                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                    @checked($createIsActive)
                />

                <label for="studio_rule_is_active" class="text-sm text-gray-600">
                    {{ __('admin.studio_rules.fields.is_active') }}
                </label>
            </div>

            <div class="flex items-center gap-3">
                <x-primary-button>
                    {{ __('admin.studio_rules.submit_create') }}
                </x-primary-button>

                <a
                    href="{{ route('admin.studio-rules.index') }}"
                    class="inline-flex items-center gap-2 rounded-md border border-transparent bg-gray-100 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 transition ease-in-out duration-150 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2"
                >
                    {{ __('admin.studio_rules.cancel') }}
                </a>
            </div>
        </form>
    </section>
</x-admin.page>
