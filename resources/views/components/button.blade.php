@props([
    'variant' => 'primary', // primary | secondary | success | warning | error | accent
    'size' => 'md',         // sm | md | lg | xl
    'type' => 'button',
    'outline' => false,
    'href' => null,         // 👈 NOVO
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

@if($href)
    {{-- LINK --}}
    <a
        href="{{ $href }}"
        wire:navigate
        {{ $attributes->merge(['class' => $classes]) }}
    >
        {{ $slot }}
    </a>
@else
    {{-- BUTTON --}}
    <button
        type="{{ $type }}"
        {{ $attributes->merge(['class' => $classes]) }}
    >
        {{ $slot }}
    </button>
@endif
