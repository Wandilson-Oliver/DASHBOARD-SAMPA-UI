<?php

use Livewire\Component;
use Livewire\Attributes\Layout;

new #[Layout('layouts::doc')] class extends Component
{
};
?>


<div class="px-10">

<x-content>

    {{-- Header --}}
    <div class="mb-12 space-y-3">
        <h1 class="text-3xl font-bold tracking-tight">
            Input
        </h1>

        <p class="text-gray-600 max-w-2xl">
            O componente <strong>x-input</strong> fornece campos de formulário
            consistentes com suporte a variantes, tamanhos e estados.
        </p>
    </div>

    {{-- Basic --}}
    <section class="space-y-6">
        <h2 class="text-xl font-semibold">Basic</h2>
        <x-divider />

        <x-browser>
            <div class="max-w-md mx-auto space-y-4">
                <x-input placeholder="Digite seu nome..." />
            </div>
        </x-browser>

        <div class="not-prose">
<pre><code class="language-html rounded-2xl">
&lt;x-input placeholder="Digite seu nome..." /&gt;
</code></pre>
        </div>
    </section>


    {{-- Variants --}}
    <section class="mt-12 space-y-6">
        <h2 class="text-xl font-semibold">Variants</h2>

            <x-browser>
                <div class="max-w-md mx-auto">
                    <x-input variant="primary" placeholder="Primary" />
                    <x-input variant="secondary" placeholder="Secondary" />
                    <x-input variant="accent" placeholder="Accent" />
                    <x-input variant="success" placeholder="Success" />
                    <x-input variant="warning" placeholder="Warning" />
                    <x-input variant="error" placeholder="Error" />
                </div>
            </x-browser>

        <div class="not-prose">
<pre><code class="language-html rounded-2xl">
&lt;x-input variant="accent" placeholder="Accent" /&gt;
</code></pre>
        </div>
    </section>


    {{-- Sizes --}}
    <section class="mt-12 space-y-6">
        <h2 class="text-xl font-semibold">Sizes</h2>
        <x-divider />

        <x-browser>
            <div class="max-w-md mx-auto">
                <x-input size="sm" placeholder="Small" />
                <x-input size="md" placeholder="Medium" />
                <x-input size="lg" placeholder="Large" />
            </div>
        </x-browser>

        <div class="not-prose">
<pre><code class="language-html rounded-2xl">
&lt;x-input size="sm" placeholder="Small" /&gt;
&lt;x-input size="md" placeholder="Medium" /&gt;
&lt;x-input size="lg" placeholder="Large" /&gt;
</code></pre>
        </div>
    </section>


    {{-- With Label --}}
    <section class="mt-12 space-y-6">
        <h2 class="text-xl font-semibold">With Label</h2>
        <x-divider />

        <x-browser>
            <div class="max-w-md mx-auto space-y-4">
                <x-input label="Email" placeholder="Digite seu email" />
            </div>
        </x-browser>

        <div class="not-prose">
<pre><code class="language-html rounded-2xl">
&lt;x-input 
    label="Email"
    placeholder="Digite seu email" 
/&gt;
</code></pre>
        </div>
    </section>


    {{-- Helper Text --}}
    <section class="mt-12 space-y-6">
        <h2 class="text-xl font-semibold">Helper Text</h2>
        <x-divider />

        <x-browser>
            <div class="max-w-md mx-auto space-y-4">
                <x-input 
                    label="Nome"
                    description="Mínimo de 8 caracteres"
                />
            </div>
        </x-browser>

        <div class="not-prose">
<pre><code class="language-html rounded-2xl">
&lt;x-input 
    label="Nome"
    description="Mínimo de 8 caracteres"
/&gt;
</code></pre>
        </div>
    </section>


    



    {{-- Disabled --}}
    <section class="mt-12 space-y-6">
        <h2 class="text-xl font-semibold">Disabled</h2>
        <x-divider />

        <x-browser>
            <div class="max-w-md mx-auto space-y-4">
                <x-input 
                    label="Nome"
                    placeholder="Campo desativado"
                    disabled
                />
            </div>
        </x-browser>

        <div class="not-prose">
<pre><code class="language-html rounded-2xl">
&lt;x-input 
    label="Nome"
    disabled
/&gt;
</code></pre>
        </div>
    </section>





    {{-- Props --}}
    <section class="mt-16 space-y-6">
        <h2 class="text-xl font-semibold">Props</h2>
        <x-divider />

        <x-card>
            <ul class="space-y-2 text-sm text-gray-600">
                <li><strong>variant</strong> → primary | accent | success | warning | error</li>
                <li><strong>size</strong> → sm | md | lg</li>
                <li><strong>label</strong> → texto acima do input</li>
                <li><strong>description</strong> → texto auxiliar abaixo</li>
                <li><strong>disabled</strong> → desativa o campo</li>
                <li><strong>type</strong> → text | email | number</li>
            </ul>
        </x-card>
    </section>

</x-content>

</div>