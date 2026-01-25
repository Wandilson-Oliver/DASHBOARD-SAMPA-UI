@props([
    'variant' => 'primary', // primary | secondary | success | warning | error | ghost
    'size' => 'md',         // sm | md | lg | xl
    'type' => 'button',
])

<button
    type="{{ $type }}"
    {{ $attributes->merge([
        'class' => "btn btn-{$variant} btn-{$size}"
    ]) }}
>
    {{ $slot }}
</button>
