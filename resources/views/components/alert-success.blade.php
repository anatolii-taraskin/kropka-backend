@props(['title' => null])

@php
    $slotText = trim(preg_replace('/\s+/', ' ', strip_tags((string) $slot)));
    $titleText = $title ? trim(preg_replace('/\s+/', ' ', strip_tags((string) $title))) : null;
    $parts = array_filter([$titleText, $slotText], fn ($value) => filled($value));
    $message = implode(' ', $parts);
@endphp

@if ($message !== '')
    <div data-success-message="{{ e($message) }}" hidden></div>

    <noscript>
        <div {{ $attributes->merge(['class' => 'rounded-md border border-green-200 bg-green-50 p-4 text-sm text-green-800']) }}>
            <div class="flex gap-3">
                <svg class="mt-0.5 h-5 w-5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.06l2.5 2.5a.75.75 0 001.138-.09l4-5.5z" clip-rule="evenodd" />
                </svg>

                <div class="space-y-1 text-green-600">
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
