<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $title ?? config('app.name') }}</title>

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.11.1/styles/atom-one-dark.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.11.1/highlight.min.js"></script>

    <!-- and it's easy to individually load additional languages -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.11.1/languages/go.min.js"></script>


</head>

<body
    class="min-h-screen dash"
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
    <x-sidebar 
    :logo="[
        'label' => 'Sampa UI',
        'icon' => 'S',
        'route' => 'dashboard.index'
    ]"

    :items="[
        ['label' => 'Voltar ao Dashboard', 'icon' => 'bi-arrow-left', 'route' => 'dashboard.index'],
        ['label' => 'Introdução', 'route' => 'documentation.index'],
        ['label' => 'Botões', 'route' => 'documentation.buttons'],
        ['label' => 'Inputs', 'route' => 'documentation.input'],
    ]"
/>



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
        <x-navbar class="bg-[#f6f7fc]"/>
        
        <x-toast/>
        {{ $slot }}

        <livewire:chat.index />
    </main>

    @livewireScripts

        <script>hljs.highlightAll();</script>

    
</body>
</html>
