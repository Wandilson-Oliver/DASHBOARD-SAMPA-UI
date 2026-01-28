<nav
    class="sticky top-0 z-40"
    x-data="{ profileOpen: false }"
>
    <div class="flex h-14 items-center justify-end px-5 mt-5">

        {{-- ESQUERDA: MENU MOBILE --}}
        <div class="flex items-center lg:hidden">
            <button
                type="button"
                @click="sidebarOpen = true"
                class="flex items-center justify-center
                       w-10 h-10 rounded-lg
                       text-slate-600
                       hover:bg-slate-100 hover:text-teal-600
                       transition"
                aria-label="Abrir menu"
            >
                <i class="bi bi-list text-2xl"></i>
            </button>
        </div>

        {{-- DIREITA: AÇÕES --}}
        <div class="flex justify-end items-center gap-1">

            {{-- CONFIGURAÇÕES --}}
            <a
                href=""
                class="flex items-center justify-center
                       w-10 h-10 rounded-lg
                       text-slate-600
                       hover:bg-slate-100 hover:text-teal-600
                       transition"
                aria-label="Configurações"
            >
                <i class="bi bi-gear text-xl"></i>
            </a>

            {{-- NOTIFICAÇÕES --}}
            <button
                type="button"
                class="relative flex items-center justify-center
                       w-10 h-10 rounded-lg
                       text-slate-600
                       hover:bg-slate-100 hover:text-teal-600
                       transition"
                aria-label="Notificações"
            >
                <i class="bi bi-bell text-xl"></i>

                {{-- Badge --}}
                <span
                    class="absolute top-1.5 right-1.5
                           w-2.5 h-2.5 rounded-full
                           bg-red-500"
                ></span>
            </button>

            {{-- PERFIL --}}
            @php
                $user = auth()->user();
                $initials = collect(explode(' ', $user->name))
                    ->map(fn ($n) => strtoupper(substr($n, 0, 1)))
                    ->take(2)
                    ->implode('');
            @endphp

            <div class="relative ml-1" @click.outside="profileOpen = false">
                <button
                    type="button"
                    @click="profileOpen = !profileOpen"
                    class="flex items-center gap-3
                           px-2 py-1.5 rounded-lg
                           hover:bg-slate-100 transition cursor-pointer"
                    aria-label="Menu do usuário"
                    :aria-expanded="profileOpen.toString()"
                >
                    {{-- Avatar --}}
                    @if ($user->avatar)
                        <img
                            src="{{ asset('storage/' . $user->avatar) }}"
                            alt="Avatar de {{ $user->name }}"
                            class="w-9 h-9 rounded-full object-cover"
                        >
                    @else
                        <div
                            class="w-9 h-9 rounded-full
                                   bg-gradient-to-br from-teal-500 to-teal-600
                                   flex items-center justify-center
                                   text-xs font-bold text-white"
                        >
                            {{ $initials }}
                        </div>
                    @endif

                    {{-- Nome + Email (desktop) --}}
                    <div class="hidden sm:flex flex-col items-start leading-tight">
                        <span class="text-sm font-semibold text-slate-800 truncate max-w-[160px]">
                            {{ $user->name }}
                        </span>
                        <span class="text-xs text-slate-500 truncate max-w-[160px]">
                            {{ $user->email }}
                        </span>
                    </div>

                    <i class="bi bi-chevron-down text-slate-400 text-xs hidden sm:block"></i>
                </button>

                {{-- DROPDOWN PERFIL --}}
                <div
                    x-show="profileOpen"
                    x-transition
                    class="absolute right-0 mt-2 w-48
                           bg-white rounded-xl shadow-lg
                           border border-slate-100 overflow-hidden"
                >
                    <div class="px-4 py-3 border-b border-slate-100">
                        <div class="text-sm font-semibold text-slate-800 truncate">
                            {{ $user->name }}
                        </div>
                        <div class="text-xs text-slate-500 truncate">
                            {{ $user->email }}
                        </div>
                    </div>

                    <a
                        href="{{ route('dashboard.profile') }}"
                        wire:navigate
                        class="flex items-center gap-3 px-4 py-2
                               text-sm text-slate-600
                               hover:bg-teal-50 hover:text-teal-700 transition"
                    >
                        <i class="bi bi-person-circle"></i>
                        Perfil
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button
                            type="submit"
                            class="w-full flex items-center gap-3 px-4 py-2
                                   text-sm text-slate-600 cursor-pointer
                                   hover:bg-red-50 hover:text-red-600 transition"
                        >
                            <i class="bi bi-box-arrow-right"></i>
                            Sair
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</nav>
