@props([
    'label' => null,
    'name' => null,
    'id' => null,
    'placeholder' => null,
    'description' => null,

    // design system (input.css)
    'size' => 'md',          // sm | md | lg | xl
    'variant' => 'primary',  // primary | secondary | success | warning | error

    // states
    'disabled' => false,
    'readonly' => false,
    'required' => false,

    // features
    'strength' => false,
    'prefix' => null,
    'suffix' => null,
])

@php
    /*
    |--------------------------------------------------------------------------
    | Field / Error (Livewire-first)
    |--------------------------------------------------------------------------
    */
    $field = $name ?? $attributes->wire('model')->value();
    $hasError = $field && $errors->has($field);

    /*
    |--------------------------------------------------------------------------
    | Classes (CSS-driven)
    |--------------------------------------------------------------------------
    */
    $inputClasses = trim(implode(' ', array_filter([
        'input',
        "input-{$size}",
        "input-{$variant}",
        $hasError ? 'input-error' : null,
        'pr-12',
        $attributes->get('class'),
    ])));

    $barHeight = match ($size) {
        'sm' => 'h-1.5',
        'lg', 'xl' => 'h-2.5',
        default => 'h-2',
    };
@endphp

<div
    class="space-y-2"
    x-data="{
        show: false,
        level: 0,
        strengthLabel: '',

        checkStrength(value) {
            @if(!$strength) return; @endif

            let score = 0
            if (value.length >= 8) score++
            if (/[A-Z]/.test(value)) score++
            if (/[0-9]/.test(value)) score++
            if (/[^A-Za-z0-9]/.test(value)) score++

            this.level = score
            this.strengthLabel =
                score <= 1 ? 'Fraca'
                : score === 2 ? 'Média'
                : 'Forte'
        }
    }"
>
    {{-- LABEL --}}
    @if($label)
        <label
            for="{{ $id ?? $field }}"
            class="block text-sm font-medium text-slate-700"
        >
            {{ $label }}
            @if($required)
                <span class="ml-1 text-red-500">*</span>
            @endif
        </label>
    @endif

    {{-- INPUT WRAPPER --}}
    <div class="relative flex items-center gap-2">

        {{-- PREFIX --}}
        @if($prefix)
            <span class="text-slate-500 text-sm shrink-0">
                {{ $prefix }}
            </span>
        @endif

        {{-- INPUT --}}
        <input
            id="{{ $id ?? $field }}"
            x-bind:type="show ? 'text' : 'password'"
            name="{{ $name }}"
            placeholder="{{ $placeholder }}"
            @input="checkStrength($event.target.value)"
            @disabled($disabled)
            @readonly($readonly)
            @required($required)
            {{ $attributes->except('class') }}
            class="{{ $inputClasses }}"
        />

        {{-- SUFFIX --}}
        @if($suffix)
            <span class="text-slate-500 text-sm shrink-0">
                {{ $suffix }}
            </span>
        @endif

        {{-- TOGGLE --}}
        <button
            type="button"
            @click="show = !show"
            aria-label="Exibir ou ocultar senha"
            class="absolute right-3 top-1/2 -translate-y-1/2
                   z-10
                   text-slate-400 hover:text-slate-600
                   focus:outline-none"
        >
            <i class="bi bi-eye transition-all duration-200" x-show="!show"></i>
            <i class="bi bi-eye-slash transition-all duration-200" x-show="show"></i>
        </button>
    </div>

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

    {{-- PASSWORD STRENGTH --}}
    @if($strength)
        <div class="mt-2 space-y-1">
            <div class="w-full bg-slate-200 rounded-full overflow-hidden {{ $barHeight }}">
                <div
                    class="h-full rounded-full transition-all duration-300"
                    :class="{
                        'bg-red-500 w-1/3': level === 1,
                        'bg-yellow-500 w-2/3': level === 2,
                        'bg-green-500 w-full': level >= 3
                    }"
                ></div>
            </div>

            <p
                class="text-xs font-semibold"
                :class="{
                    'text-red-500': level <= 1,
                    'text-yellow-600': level === 2,
                    'text-green-600': level >= 3
                }"
                x-text="strengthLabel"
            ></p>
        </div>
    @endif
</div>
