@props([
    'items' => [],
])

<aside
    x-cloak
    :class="[
        sidebarOpen ? 'translate-x-0 w-64' : '-translate-x-full lg:translate-x-0 lg:w-20',
        isMobile ? 'fixed z-50 bg-white' : 'fixed z-30'
    ]"
    class="inset-y-0 left-0
           transition-all duration-300 ease-in-out
           flex flex-col justify-between"
>
    {{-- LOGO --}}
    <div class="flex items-center gap-3 px-5 py-6">
        <div class="w-10 h-10 bg-linear-to-br from-cyan-500 to-cyan-600
                    rounded-xl flex items-center justify-center
                    text-white font-bold shadow">
            FI
        </div>

        <span
            x-show="sidebarOpen"
            x-transition
            class="font-semibold text-lg whitespace-nowrap text-cyan-700"
        >
            Sistema Financeiro
        </span>
    </div>

    {{-- MENU --}}
    <nav class="flex-1 mt-2 space-y-1 px-2 overflow-y-auto">
        @foreach($items as $item)
            <x-sidebar-item
                :href="$item['route'] ?? $item['url'] ?? '#'"
                :icon="$item['icon'] ?? null"
                :active="
                    $item['active']
                        ?? (isset($item['route'])
                            ? request()->routeIs($item['route'])
                            : request()->is(ltrim($item['url'] ?? '', '/').'*'))
                "
                @click="if (isMobile) sidebarOpen = false"
            >
                {{ $item['label'] }}
            </x-sidebar-item>
        @endforeach
    </nav>

    {{-- FOOTER --}}
    <div class="px-3 pb-4 space-y-3">
        @php
            $user = auth()->user();
            $initials = collect(explode(' ', $user->name))
                ->map(fn ($n) => strtoupper(substr($n, 0, 1)))
                ->take(2)
                ->implode('');
        @endphp

        {{-- PROFILE --}}
        <div
            class="bg-white/80 rounded-xl transition"
            x-data="{ openProfile: false }"
        >
            <button
                type="button"
                @click="openProfile = !openProfile"
                class="w-full flex items-center gap-3 px-3 py-3 rounded-xl
                       hover:bg-cyan-50 transition"
            >
                {{-- Avatar --}}
                <div class="relative shrink-0">
                    @if ($user->avatar)
                        <img
                            src="{{ asset('storage/' . $user->avatar) }}"
                            class="w-10 h-10 rounded-xl object-cover"
                        >
                    @else
                        <div
                            class="w-10 h-10 rounded-xl
                                   bg-linear-to-br from-cyan-500 to-cyan-600
                                   flex items-center justify-center
                                   text-white font-bold"
                        >
                            {{ $initials }}
                        </div>
                    @endif
                </div>

                {{-- Info --}}
                <div class="flex-1 min-w-0" x-show="sidebarOpen" x-transition>
                    <div class="font-semibold text-slate-800 text-left truncate">
                        {{ $user->name }}
                    </div>
                    <div class="text-xs text-slate-500 truncate">
                        {{ $user->email }}
                    </div>
                </div>

                <i
                    x-show="sidebarOpen"
                    class="bi text-slate-400"
                    :class="openProfile ? 'bi-chevron-up' : 'bi-chevron-down'"
                ></i>
            </button>

            {{-- DROPDOWN --}}
            <div
                x-show="openProfile && sidebarOpen"
                x-transition
                class="px-2 pb-2 space-y-1"
            >
                <a
                    href="{{ route('dashboard.profile') }}"
                    class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm
                           text-slate-600 hover:bg-cyan-50 hover:text-cyan-700"
                >
                    <i class="bi bi-person-circle"></i>
                    Meu perfil
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button
                        class="w-full flex items-center gap-3 px-3 py-2 rounded-lg
                               text-sm text-slate-600 hover:bg-red-50 hover:text-red-600"
                    >
                        <i class="bi bi-box-arrow-right"></i>
                        Sair
                    </button>
                </form>
            </div>
        </div>

        {{-- DESKTOP TOGGLE --}}
        <button
            @click="sidebarOpen = !sidebarOpen"
            class="hidden lg:flex justify-center"
        >
            <div
                class="w-11 h-11 bg-linear-to-br from-cyan-500 to-cyan-600
                       rounded-full flex items-center justify-center
                       text-white shadow hover:scale-105 transition"
            >
                <i :class="sidebarOpen ? 'bi-chevron-left' : 'bi-chevron-right'"></i>
            </div>
        </button>
    </div>
</aside>
