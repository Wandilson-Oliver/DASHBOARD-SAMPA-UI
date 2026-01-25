@props([
    'href',
    'icon' => null,
    'active' => false,
])

<a
    href="{{ $href }}"
    {{ $attributes->merge([
        'class' => '
            group flex items-center gap-4
            px-4 py-3 rounded-xl
            transition-all duration-200
            ' . (
                $active
                    ? 'bg-linear-to-r from-cyan-500 to-cyan-600
                       text-white shadow scale-[1.02]'
                    : 'text-slate-500 hover:bg-slate-100 hover:text-cyan-700'
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
