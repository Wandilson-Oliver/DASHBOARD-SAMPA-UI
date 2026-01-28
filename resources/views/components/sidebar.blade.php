@props([
    'items' => [],
])

<aside
    x-cloak
    :class="[
        sidebarOpen ? 'translate-x-0 w-64 px-3 bg-[#f6f7fc]' : '-translate-x-full lg:translate-x-0 px-3 lg:w-24',
        isMobile ? 'fixed z-50' : 'fixed z-30'
    ]"
    class="inset-y-0 left-0
           transition-all duration-300 ease-in-out
           flex flex-col justify-between"
>
    {{-- LOGO --}}
    <div class="flex items-center gap-3 px-5 py-6 mt-2">
        <div class="w-10 h-10 bg-teal-600
                    rounded-xl flex items-center justify-center
                    text-white font-bold">
            OL
        </div>

        <span
            x-show="sidebarOpen"
            x-transition
            class="font-semibold text-lg whitespace-nowrap text-teal-700"
        >
            Financeiro
        </span>
    </div>

    {{-- MENU --}}
<nav class="flex-1 mt-2 space-y-1 px-2 overflow-y-auto">
    @foreach ($items as $item)

        @php
            $href = isset($item['route'])
                ? route($item['route'])
                : ($item['url'] ?? '#');

            $active = $item['active']
                ?? (isset($item['route'])
                    ? request()->routeIs($item['route'] . '*')
                    : request()->is(ltrim($item['url'] ?? '', '/').'*'));

            $canSee = ! isset($item['permission'])
                || auth()->user()?->hasPermission($item['permission']);
        @endphp

        @if ($canSee)
            <x-sidebar-item
                :href="$href"
                :icon="$item['icon'] ?? null"
                :active="$active"
                @click="if (isMobile) sidebarOpen = false"
            >
                {{ $item['label'] }}
            </x-sidebar-item>
        @endif

    @endforeach
</nav>



{{-- FOOTER --}}
<div class="px-3 pb-4 space-y-3">
    {{-- DESKTOP SIDEBAR TOGGLE --}}
    <button
        type="button"
        @click="sidebarOpen = !sidebarOpen"
        class="hidden lg:flex justify-center cursor-pointer"
        aria-label="Alternar menu lateral"
    >
        <div
            class="w-11 h-11 bg-teal-600
                   rounded-full flex items-center justify-center
                   text-white shadow
                   hover:scale-105 active:scale-95 transition"
        >
            <i :class="sidebarOpen ? 'bi-chevron-left' : 'bi-chevron-right'"></i>
        </div>
    </button>
</div>

</aside>
