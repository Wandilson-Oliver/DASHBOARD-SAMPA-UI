<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $title ?? config('app.name') }}</title>

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body
    class="min-h-screen bg-[#f6f7fc]"
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
        ['label' => 'Usuários', 'icon' => 'bi-people', 'route' => 'dashboard.users', 'permission' => 'users.view'],

        // Recursos do site
        ['label' => 'Pessoas', 'icon' => 'bi-person-lines-fill', 'route' => 'dashboard.persons', 'permission' => 'persons.view'],
        ['label' => 'Produtos', 'icon' => 'bi-box-seam', 'route' => 'dashboard.products', 'permission' => 'products.view'],
    ]"/>


    {{-- MAIN --}}
    <main
        class=""
        :class="{
            'lg:ml-64': sidebarOpen && !isMobile,
            'lg:ml-20': !sidebarOpen && !isMobile
        }"
        class="p-4"
    >  
        {{-- NAVBAR --}}
        <x-navbar/>
        
        <x-toast/>
        {{ $slot }}
    </main>

    @livewireScripts
</body>
</html>
