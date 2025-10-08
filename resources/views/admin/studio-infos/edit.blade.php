<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('admin.studio_infos.title') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-2 pb-3 lg:px-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl pb-3">
                    <section>
                        @if (session('status') === 'studio-infos-updated')
                            <x-alert-success class="mt-4">
                                {{ __('admin.studio_infos.success') }}
                            </x-alert-success>
                        @endif

                        <form method="post" action="{{ route('admin.studio-infos.update') }}" class="mt-6 space-y-6">
                            @csrf
                            @method('put')

                            @foreach ($fields as $field)
                                @php
                                    $inputValue = old('studio_infos.' . $field['property'], $field['value']);
                                @endphp

                                <div>
                                    <x-input-label
                                        :for="'studio_infos_' . $field['property']"
                                        :value="$field['label']"
                                    />

                                    @if ($field['type'] === 'textarea')
                                        <textarea
                                            id="studio_infos_{{ $field['property'] }}"
                                            name="studio_infos[{{ $field['property'] }}]"
                                            rows="4"
                                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full"
                                            @if ($field['required']) required @endif
                                        >{{ $inputValue }}</textarea>
                                    @else
                                        <input
                                            id="studio_infos_{{ $field['property'] }}"
                                            name="studio_infos[{{ $field['property'] }}]"
                                            type="{{ $field['type'] }}"
                                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full"
                                            value="{{ $inputValue }}"
                                            @if ($field['required']) required @endif
                                        />
                                    @endif

                                    <x-input-error class="mt-2" :messages="$errors->get('studio_infos.' . $field['property'])" />
                                </div>
                            @endforeach

                            <div class="flex items-center gap-4">
                                <x-primary-button>
                                    {{ __('admin.studio_infos.submit') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
