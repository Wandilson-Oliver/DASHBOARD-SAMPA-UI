@props([
    'title' => null,
    'description' => null,
    'variant' => 'default' // default | muted | primary | success | warning | error
])

@php
    $classes = trim(implode(' ', array_filter([
        'card',
        "p-5",
        "card-{$variant}",
        $attributes->get('class'),
    ])));
@endphp

<div {{ $attributes->except('class')->merge(['class' => $classes]) }}>
    @if($title || $description)
        <div class="card-header">
            @if($title)
                <h3 class="card-title">{{ $title }}</h3>
            @endif

            @if($description)
                <p class="card-description">{{ $description }}</p>
            @endif
        </div>
    @endif

    <div class="card-body">
        {{ $slot }}
    </div>
</div>
