<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('admin.studio_rules.title') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-3xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">
                                {{ __('admin.studio_rules.title') }}
                            </h2>

                            <p class="mt-1 text-sm text-gray-600">
                                {{ __('admin.studio_rules.description') }}
                            </p>
                        </header>

                        @if (session('status') === 'studio-rules-updated')
                            <x-alert-success class="mt-4">
                                {{ __('admin.studio_rules.success') }}
                            </x-alert-success>
                        @endif

                        <form method="post" action="{{ route('admin.studio-rules.update') }}" class="mt-6 space-y-6">
                            @csrf
                            @method('put')

                            @foreach ($fields as $field)
                                @php
                                    $inputValue = old('studio_rules.' . $field['property'], $field['value']);
                                @endphp

                                <div>
                                    <x-input-label
                                        :for="'studio_rules_' . $field['property']"
                                        :value="$field['label']"
                                    />

                                    <textarea
                                        id="studio_rules_{{ $field['property'] }}"
                                        name="studio_rules[{{ $field['property'] }}]"
                                        rows="3"
                                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full"
                                        @if ($field['required']) required @endif
                                    >{{ $inputValue }}</textarea>

                                    <x-input-error class="mt-2" :messages="$errors->get('studio_rules.' . $field['property'])" />
                                </div>
                            @endforeach

                            <div class="flex items-center gap-4">
                                <x-primary-button>
                                    {{ __('admin.studio_rules.submit') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
