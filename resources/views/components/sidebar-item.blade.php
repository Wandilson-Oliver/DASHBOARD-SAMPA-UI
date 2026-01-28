@props([
    'href',
    'icon' => null,
    'active' => false,
])

<a
    href="{{ $href }}"
    wire:navigate
    {{ $attributes->merge([
        'class' => '
            group flex items-center gap-4
            px-4 py-3 rounded-xl
            transition-all duration-200
            ' . (
                $active
                    ? 'bg-teal-500
                       text-white shadow-xl'
                    : 'text-slate-500 hover:bg-white hover:text-teal-700'
            )
    ]) }}
>
    @if($icon)
        <i class="bi {{ $icon }} text-lg shrink-0"></i>
    @endif

    <span
        x-show="sidebarOpen"
        x-transition
        class="text-sm font-medium whitespace-nowrap"
    >
        {{ $slot }}
    </span>
</a>
