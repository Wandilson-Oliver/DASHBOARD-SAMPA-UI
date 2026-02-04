@props([
    'label' => null,
    'variant' => 'primary',
    'name' => null,
    'placeholder' => 'R$ 0,00',
    'prefix' => 'R$',
])

@php
    /*
    |--------------------------------------------------------------------------
    | Field resolution (Livewire-first)
    |--------------------------------------------------------------------------
    */
    $field = $name
        ?? collect($attributes->getAttributes())
            ->keys()
            ->first(fn ($key) => str_starts_with($key, 'wire:model'));

    $field = $field
        ? $attributes->get($field)
        : null;

    $hasError = $field && $errors->has($field);
@endphp

<x-input
    :label="$label"
    :name="$field"
    :placeholder="$placeholder"
    :variant="$hasError ? 'danger' : $variant"
    x-data="{
        format(value) {
            value = value.replace(/\\D/g, '');
            value = (parseInt(value || 0) / 100).toFixed(2);
            value = value.replace('.', ',');
            value = value.replace(/\\B(?=(\\d{3})+(?!\\d))/g, '.');
            return value;
        }
    }"
    x-on:input="$el.value = format($el.value)"
    {{ $attributes }}
>
    <x-slot:prefix>
        <span class="mr-2 {{ $hasError ? 'text-red-500' : 'text-gray-400' }}">
            {{ $prefix }}
        </span>
    </x-slot:prefix>
</x-input>

@if($hasError)
    <p class="mt-1 text-sm text-red-500">
        {{ $errors->first($field) }}
    </p>
@endif
