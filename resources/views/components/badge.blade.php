@props([
    'variant' => 'default', // default, success, danger, warning, info, dark
    'size' => 'md',         // sm, md, lg
    'pill' => false,
])

@php
    $variants = [
        'default' => 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100',
        'success' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
        'danger'  => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
        'warning' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
        'info'    => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
        'dark'    => 'bg-gray-900 text-white',
    ];

    $sizes = [
        'sm' => 'text-xs px-2 py-0.5',
        'md' => 'text-sm px-2.5 py-0.5',
        'lg' => 'text-sm px-3 py-1',
    ];

    $classes = implode(' ', [
        'inline-flex items-center cursor-pointer',
        $pill ? 'rounded-full' : 'rounded-lg',
        $variants[$variant] ?? $variants['default'],
        $sizes[$size] ?? $sizes['md'],
    ]);
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</span>
