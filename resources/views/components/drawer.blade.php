@props([
    'model',
    'title' => null,
    'subtitle' => null,
    'size' => 'md',
    'variant' => 'default',
    'persistent' => false,
    'closeButton' => true,
])

@php
    $sizes = [
        'sm' => 'w-80',
        'md' => 'w-96',
        'lg' => 'w-[28rem]',
        '2xl' => 'w-[36rem]',
        '4xl' => 'w-[48rem]',
    ];

    $variants = [
        'default' => 'border-gray-200',
        'primary' => 'border-blue-500',
        'success' => 'border-teal-500',
        'warning' => 'border-amber-500',
        'error' => 'border-red-500',
    ];

    $width = $sizes[$size] ?? $sizes['md'];
    $border = $variants[$variant] ?? $variants['default'];
@endphp

<div
    x-data="{ open: @entangle($model).live }"
    x-show="open"
    x-cloak
    class="fixed inset-0 z-50"
    role="dialog"
    aria-modal="true"
    @keydown.escape.window="{{ $persistent ? '' : 'open = false' }}"
>
    {{-- BACKDROP --}}
    <div
        x-show="open"
        x-transition.opacity.duration.300ms
        class="absolute inset-0 bg-black/40"
        @click="{{ $persistent ? '' : 'open = false' }}"
    ></div>

    {{-- DRAWER --}}
    <div
        x-show="open"
        x-trap.inert.noscroll="open"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="translate-x-full"
        class="absolute right-0 top-0 h-full bg-white shadow-2xl
               border-l-4 {{ $border }} {{ $width }}
               flex flex-col"
    >
        {{-- HEADER --}}
        @if($title || $closeButton)
            <header class="flex items-start justify-between px-6 py-4 border-b border-slate-200">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">
                        {{ $title }}
                    </h2>
                    @if($subtitle)
                        <p class="text-sm text-slate-500">
                            {{ $subtitle }}
                        </p>
                    @endif
                </div>

                @if($closeButton)
                    <button
                        type="button"
                        @click="open = false"
                        class="rounded-full w-10 h-10 flex items-center justify-center
                               text-gray-400 hover:text-gray-600
                               focus:outline-none focus:ring"
                        aria-label="Fechar"
                    >
                        ✕
                    </button>
                @endif
            </header>
        @endif

        {{-- BODY --}}
        <main class="flex-1 overflow-y-auto px-6 py-4">
            {{ $slot }}
        </main>

        {{-- FOOTER --}}
        @isset($actions)
            <footer class="px-6 py-4 border-t bg-gray-50 flex justify-end gap-2">
                {{ $actions }}
            </footer>
        @endisset
    </div>
</div>
