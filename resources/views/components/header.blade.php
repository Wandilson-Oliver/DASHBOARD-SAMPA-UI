@props([
    'title' => null,
    'description' => null,
])

@php
    $base = '
        w-full
        py-6
        transition-all duration-200
    ';
@endphp

<header {{ $attributes->merge(['class' => $base]) }}>
    <div class="px-4 flex items-center justify-between gap-6">

        {{-- LEFT / CONTENT --}}
        @if($title || $description)
            <div class="flex-1 min-w-0">
                @if($title)
                    <h1 class="text-2xl font-bold text-slate-900 truncate">
                        {{ $title }}
                    </h1>
                @endif

                @if($description)
                    <p class="text-sm text-slate-600 line-clamp-2">
                        {{ $description }}
                    </p>
                @endif
            </div>
        @endif

        {{-- RIGHT / BUTTONS --}}
        @isset($buttons)
            <div class="flex items-center gap-3 shrink-0">
                {{ $buttons }}
            </div>
        @endisset

    </div>
</header>
