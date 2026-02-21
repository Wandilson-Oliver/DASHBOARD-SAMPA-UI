@props([
    'type' => 'text',
    'name' => null,

    // UI
    'label' => null,
    'description' => null,

    // Variations
    'variant' => 'primary', // primary | secondary | success | warning | error | info | accent
    'size' => 'md',         // sm | md | lg | xl

    // States
    'disabled' => false,
    'readonly' => false,
    'required' => false,
])

@php
    /*
    |--------------------------------------------------------------------------
    | Field resolution (Livewire-first)
    |--------------------------------------------------------------------------
    | Prioridade:
    | 1. name
    | 2. wire:model
    */
    $field = $name
        ?? $attributes->wire('model')->value();

    $hasError = $field && $errors->has($field);

    /*
    |--------------------------------------------------------------------------
    | Classes (CSS-driven)
    |--------------------------------------------------------------------------
    */
    $inputClasses = trim(implode(' ', array_filter([
        'input',
        "input-{$variant}",
        "input-{$size}",
        $hasError ? 'input-error' : null,
        $attributes->get('class'),
    ])));
@endphp

<div class="space-y-1.5 mb-3">

    {{-- LABEL --}}
    @if($label)
        <label
            for="{{ $attributes->get('id') ?? $field }}"
            class="block text-sm font-medium text-slate-700 dark:text-slate-200"
        >
            {{ $label }}

            @if($required)
                <span class="ml-1 text-red-500">*</span>
            @endif
        </label>
    @endif

    {{-- INPUT --}}
    <input
        id="{{ $attributes->get('id') ?? $field }}"
        type="{{ $type }}"
        name="{{ $name }}"
        placeholder="{{ $attributes->get('placeholder') }}"
        @disabled($disabled)
        @readonly($readonly)
        @required($required)
        {{ $attributes->except('class')->merge([
            'class' => $inputClasses
        ]) }}
    />

    {{-- ERROR / DESCRIPTION --}}
    @if($hasError)
        <p class="text-sm text-red-600">
            {{ $errors->first($field) }}
        </p>
    @elseif($description)
        <p class="text-sm text-slate-500">
            {{ $description }}
        </p>
    @endif

</div>
