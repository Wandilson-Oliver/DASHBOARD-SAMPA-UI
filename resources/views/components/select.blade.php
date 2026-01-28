@props([
    'label' => null,
    'name' => null,
    'options' => [],
    'valueKey' => 'id',
    'labelKey' => 'label',
    'placeholder' => 'Selecione uma opção',
    'size' => 'md',      // sm | md | lg | xl
    'variant' => 'primary', // primary | secondary | success | warning | error
    'disabled' => false,
])

@php
    $error = $name && $errors->has($name);

    $sizeClass = match ($size) {
        'sm' => 'input-sm',
        'lg' => 'input-lg',
        'xl' => 'input-xl',
        default => 'input-md',
    };

    $variantClass = $error
        ? 'input-error'
        : 'input-' . $variant;
@endphp

<div class="w-full space-y-1">
    {{-- LABEL --}}
    @if($label)
        <label
            for="{{ $name }}"
            class="text-sm font-semibold text-slate-700"
        >
            {{ $label }}
        </label>
    @endif

    {{-- SELECT --}}
    <div class="relative">
        <select
            id="{{ $name }}"
            name="{{ $name }}"
            {{ $attributes->merge([
                'class' => trim("
                    input
                    {$sizeClass}
                    {$variantClass}
                    appearance-none
                    pr-10
                "),
            ]) }}
            @if($disabled) disabled @endif
        >
            {{-- PLACEHOLDER --}}
            @if($placeholder)
                <option value="">
                    {{ $placeholder }}
                </option>
            @endif

            {{-- OPTIONS --}}
            @foreach($options as $option)
                <option value="{{ $option[$valueKey] }}">
                    {{ $option[$labelKey] }}
                </option>
            @endforeach
        </select>

        {{-- ICON --}}
        <div
            class="pointer-events-none absolute inset-y-0 right-3
                   flex items-center text-slate-400"
        >
            <i class="bi bi-chevron-down"></i>
        </div>
    </div>

    {{-- ERROR --}}
    @if($error)
        <p class="text-xs text-red-600 font-medium">
            {{ $errors->first($name) }}
        </p>
    @endif
</div>
