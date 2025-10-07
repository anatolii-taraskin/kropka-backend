@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-emerald-700']) }}>
    {{ $value ?? $slot }}
</label>
