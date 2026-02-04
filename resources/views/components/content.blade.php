@props([
    'title' => null,
    'description' => null,
    'actions' => null,
    'fluid' => false,
])

<div
    {{ $attributes->merge([
        'class' => '
            w-full
            ' . ($fluid ? '' : 'max-w-7xl mx-auto') . '
            px-4 sm:px-6 lg:px-8
            py-4 sm:py-6
        '
    ]) }}
>
    {{-- HEADER DA PÁGINA --}}
    @if ($title || $actions)
        <div class="mt-5 mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                @if ($title)
                    <h1 class="text-2xl font-semibold text-slate-800">
                        {{ $title }}
                    </h1>
                @endif

                @if ($description)
                    <p class="text-sm text-slate-500">
                        {{ $description }}
                    </p>
                @endif
            </div>

            @if ($actions)
                <div class="flex items-center gap-2">
                    {{ $actions }}
                </div>
            @endif
        </div>
    @endif

    {{-- CONTEÚDO --}}
    <div>
        {{ $slot }}
    </div>
</div>
