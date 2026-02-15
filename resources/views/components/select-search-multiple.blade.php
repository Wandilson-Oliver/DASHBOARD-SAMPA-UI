@props([
    'label' => null,
    'description' => null,
    'options' => [],
    'placeholder' => 'Selecione...',
    'variant' => 'primary',
    'size' => 'md',
    'disabled' => false,
    'required' => false,
])

@php
    $field = $attributes->get('name')
        ?? $attributes->wire('model')->value();

    $hasError = $field && $errors->has($field);

    $inputClasses = trim(implode(' ', array_filter([
        'input',
        "input-{$variant}",
        "input-{$size}",
        'min-h-[42px]',
        'flex flex-wrap gap-1 items-center',
        $hasError ? 'input-error' : null,
        $attributes->get('class'),
    ])));
@endphp

<div
    class="space-y-1 mb-2"
    x-data="{
        open: false,
        search: '',
        options: @js($options),
        selected: @entangle($field),

        toggle(value) {
            this.selected.includes(value)
                ? this.selected = this.selected.filter(v => v !== value)
                : this.selected.push(value)
        },

        labelOf(value) {
            return this.options.find(o => o.value === value)?.label ?? value
        }
    }"
>
    {{-- LABEL --}}
    @if($label)
        <label class="block text-sm font-medium text-slate-700">
            {{ $label }}
            @if($required)
                <span class="ml-1 text-red-500">*</span>
            @endif
        </label>
    @endif

    <div class="relative">

        {{-- INPUT (MESMO VISUAL DO SINGLE) --}}
        <div
            class="{{ $inputClasses }}"
            :class="{ 'opacity-60 cursor-not-allowed pointer-events-none': {{ $disabled ? 'true' : 'false' }} }"
            @click="open = !open"
        >
            {{-- BADGES --}}
            <template x-for="value in selected" :key="value">
                <span
                    class="flex items-center gap-1 px-2 py-1 text-xs
                           bg-slate-100 text-slate-700 rounded-md"
                >
                    <span x-text="labelOf(value)"></span>
                    <button
                        type="button"
                        class="text-slate-400 hover:text-red-500"
                        @click.stop="toggle(value)"
                    >
                        ✕
                    </button>
                </span>
            </template>

            {{-- PLACEHOLDER --}}
            <span
                x-show="selected.length === 0"
                class="truncate text-slate-400 text-sm"
            >
                {{ $placeholder }}
            </span>

            {{-- CHEVRON --}}
            <i class="bi bi-chevron-down ml-auto text-xs opacity-70"></i>
        </div>

        {{-- DROPDOWN (IGUAL AO SINGLE) --}}
        <div
            x-show="open"
            x-transition
            @click.outside="open = false"
            class="absolute left-0 top-full z-50 w-full mt-1
                   bg-white border border-slate-200
                   rounded-lg shadow-lg"
        >
            {{-- SEARCH --}}
            <input
                x-model="search"
                placeholder="Buscar..."
                class="w-full px-3 py-2 border-b border-slate-200 outline-none text-sm"
            >

            {{-- OPTIONS --}}
            <div class="max-h-60 overflow-auto">
                <template
                    x-for="item in options.filter(o =>
                        !search || o.label.toLowerCase().includes(search.toLowerCase())
                    )"
                    :key="item.value"
                >
                    <div
                        class="px-3 py-2 text-sm cursor-pointer
                               hover:bg-slate-100 flex items-center gap-2"
                        @click="toggle(item.value)"
                    >
                        <input
                            type="checkbox"
                            class="rounded border-slate-300"
                            :checked="selected.includes(item.value)"
                            readonly
                        >
                        <span x-text="item.label"></span>
                    </div>
                </template>

                <div
                    x-show="options.length === 0"
                    class="px-3 py-2 text-sm text-slate-500"
                >
                    Nenhum resultado
                </div>
            </div>
        </div>

    </div>

    {{-- FEEDBACK --}}
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
