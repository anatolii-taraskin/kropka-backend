<x-admin.page :title="__('admin.studio_infos.title')">
    <section>
        @if (session('status') === 'studio-infos-updated')
            <x-alert-success class="mt-4">
                {{ __('admin.studio_infos.success') }}
            </x-alert-success>
        @endif

        <form method="post" action="{{ route('admin.studio-infos.update') }}" class="form-first-child-tight mt-6 space-y-6">
            @csrf
            @method('put')

            @php
                $isFirstField = true;
            @endphp

            @foreach ($fieldGroups as $section => $fields)
                <section class="rounded-xl border border-gray-200 bg-white/40 p-6 shadow-sm">
                    <header class="mb-4">
                        <h2 class="text-lg font-semibold text-gray-900">
                            {{ __('admin.studio_infos.sections.' . $section) }}
                        </h2>
                    </header>

                    <div class="space-y-6">
                        @foreach ($fields as $field)
                            @php
                                $inputValue = old('studio_infos.' . $field->property(), $field->value());
                            @endphp

                            <div @class(['first-element' => $isFirstField])>
                                <x-input-label
                                    :for="'studio_infos_' . $field->property()"
                                    :value="$field->label()"
                                />

                                @if ($field->type() === 'textarea')
                                    <textarea
                                        id="studio_infos_{{ $field->property() }}"
                                        name="studio_infos[{{ $field->property() }}]"
                                        rows="4"
                                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full"
                                        @if ($field->required()) required @endif
                                    >{{ $inputValue }}</textarea>
                                @else
                                    <input
                                        id="studio_infos_{{ $field->property() }}"
                                        name="studio_infos[{{ $field->property() }}]"
                                        type="{{ $field->type() }}"
                                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full"
                                        value="{{ $inputValue }}"
                                        @if ($field->required()) required @endif
                                    />
                                @endif

                                <x-input-error class="mt-2" :messages="$errors->get('studio_infos.' . $field->property())" />
                            </div>

                            @php
                                $isFirstField = false;
                            @endphp
                        @endforeach
                    </div>
                </section>
            @endforeach

            <div class="flex items-center gap-4">
                <x-primary-button>
                    {{ __('admin.studio_infos.submit') }}
                </x-primary-button>
            </div>
        </form>
    </section>
</x-admin.page>
