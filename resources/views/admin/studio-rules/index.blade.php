<x-admin.page
    :title="__('admin.studio_rules.title')"
    container-class="bg-transparent shadow-none p-0 space-y-6"
    :content-class="null"
>
    @php
        $status = session('status');
    @endphp

    @if ($status === 'studio-rule-created')
        <x-alert-success type="info">
            {{ __('admin.studio_rules.create_success') }}
        </x-alert-success>

        <br/>
    @elseif ($status === 'studio-rule-updated')
        <x-alert-success>
            {{ __('admin.studio_rules.update_success') }}
        </x-alert-success>

        <br/>
    @elseif ($status === 'studio-rule-deleted')
        <x-alert-success type="danger">
            {{ __('admin.studio_rules.delete_success') }}
        </x-alert-success>

        <br/>
    @endif

    <section class="bg-white shadow sm:rounded-lg overflow-hidden">
        @if ($rules->isEmpty())
            <p class="px-6 pb-6 text-sm text-gray-500">
                {{ __('admin.studio_rules.empty') }}
            </p>
        @else
            <div class="border-t border-gray-200">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <tbody
                            class="divide-y divide-gray-200 bg-white"
                            data-sortable-list
                            data-sortable-endpoint="{{ route('admin.studio-rules.reorder') }}"
                        >
                        @php
                            $locale = app()->getLocale();
                        @endphp
                        @foreach ($rules as $rule)
                            <tr class="border-b" data-sortable-id="{{ $rule->id }}">
                                <td class="px-2 py-4 align-top text-gray-400" style="width: 28px">
                                    <button
                                        type="button"
                                        class="cursor-grab rounded p-1 transition hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        data-sortable-handle
                                        aria-label="{{ __('admin.studio_rules.actions.reorder') }}"
                                    >
                                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path d="M7 4a1 1 0 1 1-2 0 1 1 0 0 1 2 0Zm8 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0ZM7 10a1 1 0 1 1-2 0 1 1 0 0 1 2 0Zm8 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0ZM7 16a1 1 0 1 1-2 0 1 1 0 0 1 2 0Zm8 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0Z" />
                                        </svg>
                                    </button>
                                </td>
                                <td class="px-6 py-4 align-top">
                                    <div class="space-y-2">
                                        <div class="flex items-start justify-between gap-3">
                                            <p class="text-sm text-gray-600 whitespace-pre-line flex-1">
                                                {{ $rule->localizedValue($locale) }}
                                            </p>

                                            @unless ($rule->is_active)
                                                <span class="inline-flex items-center rounded-full bg-gray-200 px-2 py-0.5 text-xs font-medium text-gray-600">
                                                    {{ __('admin.studio_rules.status.inactive') }}
                                                </span>
                                            @endunless
                                        </div>

                                        @if ($rule->updated_at)
                                            <p class="text-xs text-gray-400">
                                                {{ __('admin.studio_rules.updated_at', ['date' => $rule->updated_at->format(__('admin.studio_rules.date_format'))]) }}
                                            </p>
                                        @endif
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <div x-data="{}" class="flex items-center justify-end gap-3">
                                        <a
                                            href="{{ route('admin.studio-rules.edit', $rule) }}"
                                            class="text-gray-500 transition duration-150 ease-in-out hover:text-gray-700"
                                            title="{{ __('admin.studio_rules.actions.edit') }}"
                                        >
                                            <span class="sr-only">{{ __('admin.studio_rules.actions.edit') }}</span>
                                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path d="M13.586 3.586a2 2 0 0 1 2.828 2.828l-8.25 8.25a2 2 0 0 1-.878.515l-3.193.8a.75.75 0 0 1-.91-.91l.8-3.193a2 2 0 0 1 .515-.878l8.25-8.25Z"/>
                                                <path d="M5.75 15.25h8.5a.75.75 0 0 1 0 1.5h-8.5a.75.75 0 0 1 0-1.5Z"/>
                                            </svg>
                                        </a>

                                        <button
                                            type="button"
                                            class="text-red-600 transition duration-150 ease-in-out hover:text-red-700"
                                            title="{{ __('admin.studio_rules.actions.delete') }}"
                                            x-on:click.prevent="$dispatch('open-modal', 'confirm-studio-rule-{{ $rule->id }}')"
                                        >
                                            <span class="sr-only">{{ __('admin.studio_rules.actions.delete') }}</span>
                                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M9 2a1 1 0 0 0-.894.553L7.382 4H4a1 1 0 0 0 0 2h.243l.82 9.02A2 2 0 0 0 7.057 17h5.886a2 2 0 0 0 1.994-1.98L15.757 6H16a1 1 0 1 0 0-2h-3.382l-.724-1.447A1 1 0 0 0 11 2H9Zm-1 5a1 1 0 1 1 2 0v6a1 1 0 1 1-2 0V7Zm5-1a1 1 0 0 0-1 1v6a1 1 0 1 0 2 0V7a1 1 0 0 0-1-1Z" clip-rule="evenodd"/>
                                            </svg>
                                        </button>
                                    </div>

                                    <x-modal name="confirm-studio-rule-{{ $rule->id }}" focusable>
                                        <form method="post" action="{{ route('admin.studio-rules.destroy', $rule) }}" class="p-6">
                                            @csrf
                                            @method('delete')

                                            <h2 class="text-lg font-medium text-gray-900">
                                                {{ __('admin.studio_rules.delete_title') }}
                                            </h2>

                                            <p class="mt-2 text-sm text-gray-600">
                                                {{ __('admin.studio_rules.delete_confirm_simple') }}
                                            </p>

                                            <div class="mt-6 flex justify-end gap-3">
                                                <x-secondary-button x-on:click.prevent="$dispatch('close-modal', 'confirm-studio-rule-{{ $rule->id }}')">
                                                    {{ __('admin.studio_rules.modal.cancel') }}
                                                </x-secondary-button>

                                                <x-danger-button>
                                                    {{ __('admin.studio_rules.modal.confirm') }}
                                                </x-danger-button>
                                            </div>
                                        </form>
                                    </x-modal>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <div class="flex justify-start p-6">
            <a
                href="{{ route('admin.studio-rules.create') }}"
                class="inline-flex items-center gap-2 rounded-md border border-transparent bg-blue-600 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition ease-in-out duration-150 hover:bg-blue-500 focus:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
            >
                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 0 1 1 1v5h5a1 1 0 1 1 0 2h-5v5a1 1 0 1 1-2 0v-5H4a1 1 0 1 1 0-2h5V4a1 1 0 0 1 1-1Z" clip-rule="evenodd"/>
                </svg>
                <span>{{ __('admin.studio_rules.add_button') }}</span>
            </a>
        </div>
    </section>
</x-admin.page>
