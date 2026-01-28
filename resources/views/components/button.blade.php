@props([
    'variant' => 'primary', // primary | secondary | success | warning | error | ghost
    'size' => 'md',         // sm | md | lg | xl
    'type' => 'button',
    'outline' => false,
])

@php
    $base = 'btn btn-' . $size;

    if ($variant === 'ghost') {
        $classes = "$base btn-ghost";
    } elseif ($outline) {
        $classes = "$base btn-outline btn-outline-{$variant}";
    } else {
        $classes = "$base btn-{$variant}";
    }
@endphp

<button
    type="{{ $type }}"
    {{ $attributes->merge(['class' => $classes]) }}
>
    {{ $slot }}
</button>
