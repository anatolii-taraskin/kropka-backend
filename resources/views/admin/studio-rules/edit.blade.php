<x-admin.page
    :title="__('admin.studio_rules.title')"
    container-class="bg-transparent shadow-none p-0 space-y-6"
    :content-class="null"
>
    @php
        $updateErrors = $errors->getBag('updateStudioRule' . $studioRule->id);
        $useOldValues = ! $updateErrors->isEmpty();
        $updatedAt = $studioRule->updated_at ? $studioRule->updated_at->format(__('admin.studio_rules.date_format')) : null;
        $updateIsActive = $useOldValues ? (old('is_active', '0') === '1') : (bool) $studioRule->is_active;
    @endphp

    <section class="bg-white shadow sm:rounded-lg overflow-hidden">
        <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4">
            <div class="space-y-1">
                <h2 class="text-lg font-medium text-gray-900">
                    {{ __('admin.studio_rules.edit_title') }}
                </h2>

                @if ($updatedAt)
                    <p class="text-xs text-gray-500">
                        {{ __('admin.studio_rules.updated_at', ['date' => $updatedAt]) }}
                    </p>
                @endif
            </div>

            <a
                href="{{ route('admin.studio-rules.index') }}"
                class="inline-flex items-center gap-2 rounded-md border border-transparent bg-gray-100 px-3 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 transition ease-in-out duration-150 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2"
            >
                {{ __('admin.studio_rules.back_to_list') }}
            </a>
        </div>

        <form
            method="post"
            action="{{ route('admin.studio-rules.update', $studioRule) }}"
            class="form-first-child-tight border-t border-gray-200 p-6 space-y-6"
        >
            @csrf
            @method('put')

            <div class="space-y-6">
                <section class="first-element rounded-xl border border-gray-200 bg-white/40 p-6 shadow-sm space-y-6">
                    <header class="mb-2">
                        <h3 class="text-base font-semibold text-gray-900">
                            {{ __('admin.studio_rules.sections.ru') }}
                        </h3>
                    </header>

                    <div>
                        <x-input-label for="studio_rule_{{ $studioRule->id }}_value_ru" :value="__('admin.studio_rules.fields.value_ru')" />

                        <textarea
                            id="studio_rule_{{ $studioRule->id }}_value_ru"
                            name="value_ru"
                            rows="5"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            required
                            maxlength="2000"
                        >{{ $useOldValues ? old('value_ru') : $studioRule->value_ru }}</textarea>

                        <x-input-error class="mt-2" :messages="$updateErrors->get('value_ru')" />
                    </div>
                </section>

                <section class="rounded-xl border border-gray-200 bg-white/40 p-6 shadow-sm space-y-6">
                    <header class="mb-2">
                        <h3 class="text-base font-semibold text-gray-900">
                            {{ __('admin.studio_rules.sections.en') }}
                        </h3>
                    </header>

                    <div>
                        <x-input-label for="studio_rule_{{ $studioRule->id }}_value_en" :value="__('admin.studio_rules.fields.value_en')" />

                        <textarea
                            id="studio_rule_{{ $studioRule->id }}_value_en"
                            name="value_en"
                            rows="5"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            required
                            maxlength="2000"
                        >{{ $useOldValues ? old('value_en') : $studioRule->value_en }}</textarea>

                        <x-input-error class="mt-2" :messages="$updateErrors->get('value_en')" />
                    </div>
                </section>
            </div>

            <div class="flex items-center gap-2 pb-3">
                <input type="hidden" name="is_active" value="0" />

                <input
                    id="studio_rule_{{ $studioRule->id }}_is_active"
                    name="is_active"
                    type="checkbox"
                    value="1"
                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                    @checked($updateIsActive)
                />

                <label for="studio_rule_{{ $studioRule->id }}_is_active" class="text-sm text-gray-600">
                    {{ __('admin.studio_rules.fields.is_active') }}
                </label>
            </div>

            <div class="flex items-center gap-3">
                <x-primary-button>
                    {{ __('admin.studio_rules.submit_update') }}
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
