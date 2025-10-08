<x-admin.page :title="__('admin.studio_rules.title')">
    <section>
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
</x-admin.page>
