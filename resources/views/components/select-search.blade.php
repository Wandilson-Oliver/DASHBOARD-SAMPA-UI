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
        $hasError ? 'input-error' : null,
        $disabled ? 'opacity-60 cursor-not-allowed' : 'cursor-pointer',
        $attributes->get('class'),
    ])));
@endphp

<div class="space-y-1 mb-2" x-data="{ open: false, search: '' }">

    @if($label)
        <label class="block text-sm font-medium text-slate-700">
            {{ $label }}
            @if($required)
                <span class="ml-1 text-red-500">*</span>
            @endif
        </label>
    @endif

    <div class="relative">

        <div
            class="{{ $inputClasses }} flex justify-between items-center"
            @click="!{{ $disabled ? 'true' : 'false' }} && (open = !open)"
        >
            <span class="truncate">
                {{
                    collect($options)
                        ->firstWhere('value', data_get($this, $field))['label']
                        ?? $placeholder
                }}
            </span>
            <i class="bi bi-chevron-down text-xs opacity-70"></i>
        </div>

        <div
            x-show="open"
            x-transition
            @click.outside="open = false"
            class="absolute left-0 top-full z-50 w-full mt-1
                   bg-white border border-slate-200
                   rounded-lg shadow-lg"
        >
            <input
                x-model="search"
                placeholder="Buscar..."
                class="w-full px-3 py-2 border-b border-slate-200 outline-none"
            >

            <div class="max-h-60 overflow-auto">
                @foreach($options as $item)
                    <div
                        class="px-3 py-2 text-sm cursor-pointer hover:bg-slate-100"
                        x-show="!search || '{{ strtolower($item['label']) }}'.includes(search.toLowerCase())"
                        @click="
                            $wire.set('{{ $field }}', @js($item['value']));
                            open = false;
                        "
                    >
                        {{ $item['label'] }}
                    </div>
                @endforeach

                @if(empty($options))
                    <div class="px-3 py-2 text-sm text-slate-500">
                        Nenhum resultado
                    </div>
                @endif
            </div>
        </div>

    </div>

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
