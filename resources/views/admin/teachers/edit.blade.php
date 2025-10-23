<x-admin.page
    :title="__('admin.teachers.title')"
    container-class="bg-transparent shadow-none p-0 space-y-6"
    :content-class="null"
>
    @php
        $updateErrors = $errors->getBag('updateTeacher' . $teacher->id);
        $useOldValues = ! $updateErrors->isEmpty();
        $updatedAt = $teacher->updated_at ? $teacher->updated_at->format(__('admin.teachers.date_format')) : null;
        $updateIsActive = $useOldValues ? (old('is_active', '0') === '1') : (bool) $teacher->is_active;
    @endphp

    <section class="bg-white shadow sm:rounded-lg overflow-hidden">
        <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4">
            <div class="space-y-1">
                <h2 class="text-lg font-medium text-gray-900">
                    {{ __('admin.teachers.edit_title') }}
                </h2>

                @if ($updatedAt)
                    <p class="text-xs text-gray-500">
                        {{ __('admin.teachers.updated_at', ['date' => $updatedAt]) }}
                    </p>
                @endif
            </div>

            <a
                href="{{ route('admin.teachers.index') }}"
                class="inline-flex items-center gap-2 rounded-md border border-transparent bg-gray-100 px-3 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 transition ease-in-out duration-150 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2"
            >
                {{ __('admin.teachers.back_to_list') }}
            </a>
        </div>

        <form
            method="post"
            action="{{ route('admin.teachers.update', $teacher) }}"
            enctype="multipart/form-data"
            class="form-first-child-tight border-t border-gray-200 p-6 space-y-6"
        >
            @csrf
            @method('put')

            <div class="space-y-6">
                <section class="first-element rounded-xl border border-gray-200 bg-white/40 p-6 shadow-sm space-y-6">
                    <header class="mb-2">
                        <h3 class="text-base font-semibold text-gray-900">
                            {{ __('admin.teachers.sections.ru') }}
                        </h3>
                    </header>

                    <div>
                        <x-input-label for="teacher_{{ $teacher->id }}_name_ru" :value="__('admin.teachers.fields.name_ru')" />

                        <x-text-input
                            id="teacher_{{ $teacher->id }}_name_ru"
                            name="name_ru"
                            type="text"
                            class="mt-1 block w-full"
                            value="{{ $useOldValues ? old('name_ru') : $teacher->name_ru }}"
                            required
                            maxlength="255"
                        />

                        <x-input-error class="mt-2" :messages="$updateErrors->get('name_ru')" />
                    </div>

                    <div>
                        <x-input-label for="teacher_{{ $teacher->id }}_description_ru" :value="__('admin.teachers.fields.description_ru')" />

                        <textarea
                            id="teacher_{{ $teacher->id }}_description_ru"
                            name="description_ru"
                            rows="5"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >{{ $useOldValues ? old('description_ru') : $teacher->description_ru }}</textarea>

                        <x-input-error class="mt-2" :messages="$updateErrors->get('description_ru')" />
                    </div>
                </section>

                <section class="rounded-xl border border-gray-200 bg-white/40 p-6 shadow-sm space-y-6">
                    <header class="mb-2">
                        <h3 class="text-base font-semibold text-gray-900">
                            {{ __('admin.teachers.sections.en') }}
                        </h3>
                    </header>

                    <div>
                        <x-input-label for="teacher_{{ $teacher->id }}_name_en" :value="__('admin.teachers.fields.name_en')" />

                        <x-text-input
                            id="teacher_{{ $teacher->id }}_name_en"
                            name="name_en"
                            type="text"
                            class="mt-1 block w-full"
                            value="{{ $useOldValues ? old('name_en') : $teacher->name_en }}"
                            required
                            maxlength="255"
                        />

                        <x-input-error class="mt-2" :messages="$updateErrors->get('name_en')" />
                    </div>

                    <div>
                        <x-input-label for="teacher_{{ $teacher->id }}_description_en" :value="__('admin.teachers.fields.description_en')" />

                        <textarea
                            id="teacher_{{ $teacher->id }}_description_en"
                            name="description_en"
                            rows="5"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >{{ $useOldValues ? old('description_en') : $teacher->description_en }}</textarea>

                        <x-input-error class="mt-2" :messages="$updateErrors->get('description_en')" />
                    </div>
                </section>
            </div>

            <div>
                <x-input-label for="teacher_{{ $teacher->id }}_telegram_url" :value="__('admin.teachers.fields.telegram_url')" />

                <x-text-input
                    id="teacher_{{ $teacher->id }}_telegram_url"
                    name="telegram_url"
                    type="url"
                    class="mt-1 block w-full"
                    value="{{ $useOldValues ? old('telegram_url') : $teacher->telegram_url }}"
                    maxlength="255"
                />

                <x-input-error class="mt-2" :messages="$updateErrors->get('telegram_url')" />
            </div>

            <div class="space-y-3">
                <x-input-label for="teacher_{{ $teacher->id }}_photo" :value="__('admin.teachers.fields.photo')" />

                @if ($teacher->photo_path)
                    <div class="flex items-start gap-4">
                        <div class="h-40 w-40 overflow-hidden rounded-md border border-gray-200 bg-gray-50">
                            <img
                                src="{{ $teacher->photoUrl() }}"
                                alt="{{ $teacher->localizedName() }}"
                                class="h-full w-full object-cover"
                            />
                        </div>

                        <p class="text-xs text-gray-500">
                            {{ __('admin.teachers.photo_help') }}
                        </p>
                    </div>
                @endif

                <input
                    id="teacher_{{ $teacher->id }}_photo"
                    name="photo"
                    type="file"
                    accept="image/*"
                    class="mt-1 block w-full text-sm text-gray-900 file:mr-4 file:rounded-md file:border-0 file:bg-gray-100 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-gray-700 hover:file:bg-gray-200"
                />

                <x-input-error class="mt-2" :messages="$updateErrors->get('photo')" />
            </div>

            <div class="flex items-center gap-2 pb-3">
                <input type="hidden" name="is_active" value="0" />

                <input
                    id="teacher_{{ $teacher->id }}_is_active"
                    name="is_active"
                    type="checkbox"
                    value="1"
                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                    @checked($updateIsActive)
                />

                <label for="teacher_{{ $teacher->id }}_is_active" class="text-sm text-gray-600">
                    {{ __('admin.teachers.fields.is_active') }}
                </label>
            </div>

            <div class="flex items-center gap-3">
                <x-primary-button>
                    {{ __('admin.teachers.submit_update') }}
                </x-primary-button>

                <a
                    href="{{ route('admin.teachers.index') }}"
                    class="inline-flex items-center gap-2 rounded-md border border-transparent bg-gray-100 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 transition ease-in-out duration-150 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2"
                >
                    {{ __('admin.teachers.cancel') }}
                </a>
            </div>
        </form>
    </section>
</x-admin.page>
