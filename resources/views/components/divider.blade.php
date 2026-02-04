@props([
    'variant' => 'dashed', // dashed | solid
    'size' => '2',         // 2 | 4 | 8
    'color' => 'slate-200',
])

@php


    $borderStyle = $variant === 'solid'
        ? 'border-solid'
        : 'border-dashed';
@endphp

<div {{ $attributes->merge([
    'class' => "w-full border-{$size} {$borderStyle} border-{$color}"
]) }}></div>
