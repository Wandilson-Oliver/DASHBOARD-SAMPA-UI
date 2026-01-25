<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $title ?? config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body
    class="min-h-screen bg-slate-200"
    x-data="{
        sidebarOpen: false,
        isMobile: window.matchMedia('(max-width: 1024px)').matches
    }"
    x-init="
        sidebarOpen = !isMobile;
        window.matchMedia('(max-width: 1024px)')
            .addEventListener('change', e => {
                isMobile = e.matches;
                sidebarOpen = !e.matches;
            });
    "
>
    {{-- MOBILE OVERLAY --}}
    <div
        x-show="sidebarOpen && isMobile"
        x-transition.opacity
        @click="sidebarOpen = false"
        class="fixed inset-0 bg-black/40 z-40 lg:hidden"
    ></div>

    {{-- SIDEBAR --}}
    <x-sidebar :items="[
        ['label' => 'Dashboard', 'icon' => 'bi-speedometer2', 'route' => 'dashboard.index'],
        ['label' => 'Financeiro', 'icon' => 'bi-cash-coin', 'url' => '/financeiro'],
        ['label' => 'Relatórios', 'icon' => 'bi-bar-chart-line', 'url' => '/relatorios'],
        ['label' => 'Configurações', 'icon' => 'bi-sliders2-vertical', 'route' => 'dashboard.settings'],
    ]"/>

    {{-- TOP BAR (MOBILE) --}}
    <header class="lg:hidden sticky top-0 z-30 bg-white shadow px-4 py-3 flex items-center">
        <button @click="sidebarOpen = true" class="text-slate-600">
            <i class="bi bi-list text-2xl"></i>
        </button>
        <span class="ml-3 font-semibold text-slate-700">
            {{ config('app.name') }}
        </span>
    </header>

    {{-- MAIN --}}
    <main
        :class="{
            'lg:ml-72 lg:mr-8': sidebarOpen && !isMobile,
            'lg:ml-20': !sidebarOpen && !isMobile
        }"
        class="transition-all duration-300 p-4"
    >
        <x-toast/>
        {{ $slot }}
    </main>

    @livewireScripts
</body>
</html>
