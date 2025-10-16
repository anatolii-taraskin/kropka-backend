@props(['title' => null, 'type' => 'success'])

@php
    $slotText = trim(preg_replace('/\s+/', ' ', strip_tags((string) $slot)));
    $titleText = $title ? trim(preg_replace('/\s+/', ' ', strip_tags((string) $title))) : null;
    $parts = array_filter([$titleText, $slotText], fn ($value) => filled($value));
    $message = implode(' ', $parts);

    $variants = [
        'success' => [
            'container' => 'border-green-200 bg-green-50 text-green-800',
            'content' => 'text-green-600',
        ],
        'info' => [
            'container' => 'border-blue-200 bg-blue-50 text-blue-800',
            'content' => 'text-blue-600',
        ],
        'danger' => [
            'container' => 'border-red-200 bg-red-50 text-red-800',
            'content' => 'text-red-600',
        ],
    ];

    $type = array_key_exists($type, $variants) ? $type : 'success';
    $variant = $variants[$type];
@endphp

@if ($message !== '')
    <div
        data-toast-message="{{ e($message) }}"
        data-toast-type="{{ $type }}"
        hidden
    ></div>

    <noscript>
        <div {{ $attributes->merge(['class' => "rounded-md border p-4 text-sm {$variant['container']}"]) }}>
            <div class="flex gap-3">
                <svg class="mt-0.5 h-5 w-5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.799-1.88-1.88a.75.75 0 10-1.06 1.06l2.5 2.5a.75.75 0 001.138-.09l4-5.5z" clip-rule="evenodd" />
                </svg>

                <div class="space-y-1 {{ $variant['content'] }}">
                    @if ($title)
                        <p class="font-medium">{{ $title }}</p>
                    @endif

                    <div>
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </noscript>
@endif
