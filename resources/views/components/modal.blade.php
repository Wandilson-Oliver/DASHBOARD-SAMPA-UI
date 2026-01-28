@props([
    'model',
    'title' => null,
    'size' => 'md',
    'variant' => 'default',
    'persistent' => false,
    'closeButton' => true,
    'subtitle' => null,
])

@php
    $sizes = [
        'sm' => 'max-w-sm',
        'md' => 'max-w-md',
        'lg' => 'max-w-lg',
        '2xl' => 'max-w-2xl',
        '4xl' => 'max-w-4xl',
    ];

    $variants = [
        'default' => 'border-gray-100',
        'primary' => 'border-blue-500',
        'success' => 'border-teal-500',
        'warning' => 'border-amber-500',
        'error' => 'border-red-500',
    ];

    $maxWidth = $sizes[$size] ?? $sizes['md'];
    $border = $variants[$variant] ?? $variants['default'];
@endphp

<div
    x-data="{ open: @entangle($model).live }"
    x-show="open"
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center p-5"
    role="dialog"
    aria-modal="true"
    @keydown.escape.window="{{ $persistent ? '' : 'open = false' }}"
>
    {{-- Backdrop --}}
    <div
        x-show="open"
        x-transition.opacity.duration.300ms
        class="absolute inset-0 bg-black/40"
        @click="{{ $persistent ? '' : 'open = false' }}"
    ></div>

    {{-- Modal --}}
    <div
        x-show="open"
        x-trap.inert.noscroll="open"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95 translate-y-4"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-4"
        class="relative bg-white rounded-2xl shadow-2xl border-t-4 {{ $border }} {{ $maxWidth }} w-full max-h-[90vh] flex flex-col"
    >
        {{-- Header --}}
        @if($title || $closeButton)
            <header class="flex items-center justify-between px-6 py-4 border-b border-slate-200">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">
                        {{ $title }}
                    </h2>
                    <p class="text-sm">{{ $subtitle }}</p>
                </div>

                @if($closeButton)
                    <button
                        type="button"
                        @click="open = false"
                        class="rounded-full w-10 h-10 cursor-pointer p-2 text-gray-400 hover:text-gray-600 focus:outline-none focus:ring"
                        aria-label="Fechar modal"
                    >
                        ✕
                    </button>
                @endif
            </header>
        @endif

        {{-- Body --}}
        <main class="flex-1 overflow-auto px-6 py-4">
            {{ $slot }}
        </main>

        {{-- Footer --}}
        @isset($actions)
            <footer class="px-6 py-4 border-t bg-gray-50 flex justify-end gap-2">
                {{ $actions }}
            </footer>
        @endisset
    </div>
</div>
