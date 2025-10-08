@props([
    'title',
    'containerClass' => 'p-2 pb-3 lg:px-8 bg-white shadow sm:rounded-lg max-w-xl pb-3',
    'contentClass' => 'max-w-xl pb-3 sm:rounded-lg',
])

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $title }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div {{ $attributes->class($containerClass) }}>
                @if ($contentClass)
                    <div class="{{ $contentClass }}">
                        {{ $slot }}
                    </div>
                @else
                    {{ $slot }}
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
