@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-bone-dim mb-2']) }}>
    {{ $value ?? $slot }}
</label>
