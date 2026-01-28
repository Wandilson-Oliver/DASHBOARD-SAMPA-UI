<x-mail::message>
{{-- =================================================
 | Greeting
 ================================================= --}}
@if (! empty($greeting))
# {{ $greeting }}
@else
# Olá!
@endif

{{-- =================================================
 | Intro
 ================================================= --}}
@foreach ($introLines as $line)
{{ $line }}

@endforeach

{{-- =================================================
 | Action Button
 ================================================= --}}
@isset($actionText)
@php
    $color = match ($level) {
        'success' => 'success',
        'error'   => 'error',
        default   => 'primary',
    };
@endphp

<x-mail::button :url="$actionUrl" :color="$color">
{{ $actionText }}
</x-mail::button>
@endisset

{{-- =================================================
 | Outro
 ================================================= --}}
@foreach ($outroLines as $line)
{{ $line }}

@endforeach

{{-- =================================================
 | Salutation
 ================================================= --}}
@if (! empty($salutation))
{{ $salutation }}
@else
Atenciosamente,<br>
<strong>{{ config('app.name') }}</strong>
@endif

{{-- =================================================
 | Subcopy
 ================================================= --}}
@isset($actionText)
<x-slot:subcopy>
Se você estiver com dificuldade para clicar no botão
<strong>"{{ $actionText }}"</strong>, copie e cole o link abaixo
no seu navegador:

<span class="break-all">
{{ $displayableActionUrl ?? $actionUrl }}
</span>
</x-slot:subcopy>
@endisset
</x-mail::message>
