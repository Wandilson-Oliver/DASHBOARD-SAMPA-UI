@php
    $base = 'bg-white dark:bg-slate-900 p-6 rounded-b-2xl';
@endphp

<div class="border border-slate-200 dark:border-slate-700 rounded-2xl overflow-hidden">

    {{-- Header estilo janela --}}
    <div class="bg-slate-100 dark:bg-slate-800 px-4 py-4  border-slate-200 dark:border-slate-700">
        <div class="flex items-center gap-2">
            <span class="w-3 h-3 bg-red-500 rounded-full"></span>
            <span class="w-3 h-3 bg-yellow-500 rounded-full"></span>
            <span class="w-3 h-3 bg-green-500 rounded-full"></span>
        </div>
    </div>

    {{-- Conteúdo --}}
    <div {{ $attributes->merge(['class' => $base]) }}>
        {{ $slot }}
    </div>

</div>
