@props([
    'label' => null,
    'name' => null,
    'placeholder' => '(99) 9 9999-9999',
    'wire' => true,
])

<x-input
    :label="$label"
    :name="$name"
    :placeholder="$placeholder"
    :wire="$wire"
    x-data
    x-mask="(99) 9 9999-9999"
    {{ $attributes }}
>
    <x-slot:prefix>
        @isset($prefix)
                <span class="mr-2">{{ $prefix }}</span>
        @else
                <span class="mr-2"><i class="bi bi-telephone text-gray-400"></i></span>
        @endisset
    </x-slot:prefix>
</x-input>
