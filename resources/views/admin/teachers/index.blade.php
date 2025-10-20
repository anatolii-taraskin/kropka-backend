<x-admin.page
    :title="__('admin.teachers.title')"
    container-class="bg-transparent shadow-none p-0 space-y-6"
    :content-class="null"
>
    @php
        $status = session('status');
    @endphp

    @if ($status === 'teacher-created')
        <x-alert-success type="info">
            {{ __('admin.teachers.create_success') }}
        </x-alert-success>

        <br/>
    @elseif ($status === 'teacher-updated')
        <x-alert-success>
            {{ __('admin.teachers.update_success') }}
        </x-alert-success>

        <br/>
    @elseif ($status === 'teacher-deleted')
        <x-alert-success type="danger">
            {{ __('admin.teachers.delete_success') }}
        </x-alert-success>

        <br/>
    @endif

    <section class="bg-white shadow sm:rounded-lg overflow-hidden">
        @if ($teachers->isEmpty())
            <p class="px-6 p-6 text-sm text-gray-500">
                {{ __('admin.teachers.empty') }}
            </p>
        @else
            <div class="border-t border-gray-200">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <tbody
                            class="divide-y divide-gray-200 bg-white"
                            data-sortable-list
                            data-sortable-endpoint="{{ route('admin.teachers.reorder') }}"
                        >
                        @foreach ($teachers as $teacher)
                            <tr class="border-b" data-sortable-id="{{ $teacher->id }}">
                                <td class="px-2 py-4 align-top text-gray-400" style="width: 28px">
                                    <button
                                        type="button"
                                        class="cursor-grab rounded p-1 transition hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        data-sortable-handle
                                        aria-label="{{ __('admin.teachers.actions.reorder') }}"
                                    >
                                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path d="M7 4a1 1 0 1 1-2 0 1 1 0 0 1 2 0Zm8 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0ZM7 10a1 1 0 1 1-2 0 1 1 0 0 1 2 0Zm8 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0ZM7 16a1 1 0 1 1-2 0 1 1 0 0 1 2 0Zm8 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0Z" />
                                        </svg>
                                    </button>
                                </td>
                                <td class="px-6 py-4 align-top">
                                    <div class="flex items-start gap-4">
                                        @if ($teacher->photo_path)
                                            <div class="h-16 w-16 flex-shrink-0 overflow-hidden rounded-md border border-gray-200 bg-gray-50">
                                                <img
                                                    src="{{ $teacher->photoUrl() }}"
                                                    alt="{{ $teacher->name }}"
                                                    class="h-full w-full object-cover"
                                                />
                                            </div>
                                        @endif

                                        <div class="space-y-2">
                                            <div class="flex items-center gap-2">
                                                <span class="text-sm font-medium text-gray-900">
                                                    {{ $teacher->name }}
                                                </span>

                                                @unless ($teacher->is_active)
                                                    <span class="inline-flex items-center rounded-full bg-gray-200 px-2 py-0.5 text-xs font-medium text-gray-600">
                                                        {{ __('admin.teachers.status.inactive') }}
                                                    </span>
                                                @endunless
                                            </div>

                                            @if ($teacher->description)
                                                <p class="text-sm text-gray-600 whitespace-pre-line">
                                                    {{ $teacher->description }}
                                                </p>
                                            @endif

                                            @if ($teacher->telegram_url)
                                                <div>
                                                    <a
                                                        href="{{ $teacher->telegram_url }}"
                                                        class="inline-flex items-center gap-1 text-sm font-medium text-blue-600 transition hover:text-blue-500"
                                                        target="_blank"
                                                        rel="noopener noreferrer"
                                                    >
                                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                                            <path d="M9.197 15.59 8.99 19.38a.75.75 0 0 0 1.255.585l2.027-1.845 3.67 2.742c.677.506 1.64.126 1.808-.726l3.25-16.25a1 1 0 0 0-1.374-1.1L2.322 10.084c-.897.36-.832 1.652.093 1.91l4.978 1.36 11.54-7.26-9.736 9.496Z" />
                                                        </svg>
                                                        <span>{{ __('admin.teachers.telegram_link_label') }}</span>
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <div x-data="{}" class="flex items-center justify-end gap-3">
                                        <a
                                            href="{{ route('admin.teachers.edit', $teacher) }}"
                                            class="text-gray-500 transition duration-150 ease-in-out hover:text-gray-700"
                                            title="{{ __('admin.teachers.actions.edit') }}"
                                        >
                                            <span class="sr-only">{{ __('admin.teachers.actions.edit') }}</span>
                                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path
                                                    d="M13.586 3.586a2 2 0 0 1 2.828 2.828l-8.25 8.25a2 2 0 0 1-.878.515l-3.193.8a.75.75 0 0 1-.91-.91l.8-3.193a2 2 0 0 1 .515-.878l8.25-8.25Z"/>
                                                <path d="M5.75 15.25h8.5a.75.75 0 0 1 0 1.5h-8.5a.75.75 0 0 1 0-1.5Z"/>
                                            </svg>
                                        </a>

                                        <button
                                            type="button"
                                            class="text-red-600 transition duration-150 ease-in-out hover:text-red-700"
                                            title="{{ __('admin.teachers.actions.delete') }}"
                                            x-on:click.prevent="$dispatch('open-modal', 'confirm-teacher-{{ $teacher->id }}')"
                                        >
                                            <span class="sr-only">{{ __('admin.teachers.actions.delete') }}</span>
                                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path fill-rule="evenodd"
                                                      d="M9 2a1 1 0 0 0-.894.553L7.382 4H4a1 1 0 0 0 0 2h.243l.82 9.02A2 2 0 0 0 7.057 17h5.886a2 2 0 0 0 1.994-1.98L15.757 6H16a1 1 0 1 0 0-2h-3.382l-.724-1.447A1 1 0 0 0 11 2H9Zm-1 5a1 1 0 1 1 2 0v6a1 1 0 1 1-2 0V7Zm5-1a1 1 0 0 0-1 1v6a1 1 0 1 0 2 0V7a1 1 0 0 0-1-1Z"
                                                      clip-rule="evenodd"/>
                                            </svg>
                                        </button>
                                    </div>

                                    <x-modal name="confirm-teacher-{{ $teacher->id }}" focusable>
                                        <form method="post" action="{{ route('admin.teachers.destroy', $teacher) }}" class="p-6">
                                            @csrf
                                            @method('delete')

                                            <h2 class="text-lg font-medium text-gray-900">
                                                {{ __('admin.teachers.delete_title') }}
                                            </h2>

                                            <p class="mt-2 text-sm text-gray-600">
                                                {{ __('admin.teachers.delete_confirm_simple') }}
                                            </p>

                                            <div class="mt-6 flex justify-end gap-3">
                                                <x-secondary-button x-on:click.prevent="$dispatch('close-modal', 'confirm-teacher-{{ $teacher->id }}')">
                                                    {{ __('admin.teachers.modal.cancel') }}
                                                </x-secondary-button>

                                                <x-danger-button>
                                                    {{ __('admin.teachers.modal.confirm') }}
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
                href="{{ route('admin.teachers.create') }}"
                class="inline-flex items-center gap-2 rounded-md border border-transparent bg-blue-600 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition ease-in-out duration-150 hover:bg-blue-500 focus:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
            >
                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd"
                          d="M10 3a1 1 0 0 1 1 1v5h5a1 1 0 1 1 0 2h-5v5a1 1 0 1 1-2 0v-5H4a1 1 0 1 1 0-2h5V4a1 1 0 0 1 1-1Z"
                          clip-rule="evenodd"/>
                </svg>
                <span>{{ __('admin.teachers.add_button') }}</span>
            </a>
        </div>
    </section>
</x-admin.page>
