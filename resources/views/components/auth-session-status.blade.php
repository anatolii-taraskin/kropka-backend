@props(['status'])

@if ($status)
    <x-alert-success {{ $attributes }}>
        {{ $status }}
    </x-alert-success>
@endif
