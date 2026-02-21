<?php

use Livewire\Component;
use Livewire\Attributes\Layout;

new #[Layout('layouts::doc')] class extends Component
{
};
?>

<x-content>

    {{-- Header --}}
    <div class="mb-12 space-y-3">
        <h1 class="text-3xl font-bold tracking-tight">
            Button
        </h1>

        <p class="text-gray-600 max-w-2xl">
            O componente <strong>x-button</strong> aplica automaticamente
            as classes base definidas no sistema e suporta variantes semânticas.
        </p>
    </div>

    {{-- Variants --}}
    <section class="space-y-6">
        <h2 class="text-xl font-semibold">Variants</h2>
        <x-divider />

        <x-browser>
                <div class="max-w-lg mx-auto">
                <x-button variant="primary">Primary</x-button>
                <x-button variant="secondary">Secondary</x-button>
                <x-button variant="accent">Accent</x-button>
                <x-button variant="success">Success</x-button>
                <x-button variant="warning">Warning</x-button>
                <x-button variant="error">Error</x-button>
            </div>
        </x-browser>

        <div class="not-prose">
<pre><code class="language-html rounded-2xl">
&lt;x-button variant="primary"&gt;Primary&lt;/x-button&gt;
&lt;x-button variant="secondary"&gt;Secondary&lt;/x-button&gt;
&lt;x-button variant="accent"&gt;Accent&lt;/x-button&gt;
&lt;x-button variant="success"&gt;Success&lt;/x-button&gt;
&lt;x-button variant="warning"&gt;Warning&lt;/x-button&gt;
&lt;x-button variant="error"&gt;Error&lt;/x-button&gt;
</code></pre>
        </div>
    </section>


    {{-- Sizes --}}
    <section class="mt-12 space-y-6">
        <h2 class="text-xl font-semibold">Sizes</h2>
        <x-divider />

        <x-browser>
            <div class="max-w-md mx-auto">
                <x-button size="sm">Small</x-button>
                <x-button size="md">Medium</x-button>
                <x-button size="lg">Large</x-button>
                <x-button size="xl">Extra Large</x-button>
            </div>
        </x-browser>

        <div class="not-prose">
<pre><code class="language-html rounded-2xl">
&lt;x-button size="sm"&gt;Small&lt;/x-button&gt;
&lt;x-button size="md"&gt;Medium&lt;/x-button&gt;
&lt;x-button size="lg"&gt;Large&lt;/x-button&gt;
&lt;x-button size="xl"&gt;Extra Large&lt;/x-button&gt;
</code></pre>
        </div>
    </section>


    {{-- Outline --}}
    <section class="mt-12 space-y-6">
        <h2 class="text-xl font-semibold">Outline</h2>
        <x-divider />

        <x-browser>
            <div class="max-w-xl mx-auto">
                <x-button variant="primary" outline>Primary</x-button>
                <x-button variant="secondary" outline>Secondary</x-button>
                <x-button variant="accent" outline>Accent</x-button>
                <x-button variant="success" outline>Success</x-button>
                <x-button variant="warning" outline>Warning</x-button>
                <x-button variant="error" outline>Error</x-button>
            </div>
        </x-browser>

        <div class="not-prose">
<pre><code class="language-html rounded-2xl">
&lt;x-button variant="accent" outline&gt;Accent&lt;/x-button&gt;
</code></pre>
        </div>
    </section>


    {{-- Disabled --}}
    <section class="mt-12 space-y-6">
        <h2 class="text-xl font-semibold">Disabled</h2>
        <x-divider />

        <x-browser>
            <div class="max-w-md mx-auto">
                <x-button disabled>
                    Disabled
                </x-button>

                <x-button variant="accent" disabled>
                    Accent Disabled
                </x-button>
            </div>
        </x-browser>

        <div class="not-prose">
<pre><code class="language-html rounded-2xl">
&lt;x-button disabled&gt;Disabled&lt;/x-button&gt;

&lt;x-button variant="accent" disabled&gt;
    Accent Disabled
&lt;/x-button&gt;
</code></pre>
        </div>
    </section>


    {{-- With Icon --}}
    <section class="mt-12 space-y-6">
        <h2 class="text-xl font-semibold">With Icon</h2>
        <x-divider />

        <x-browser>
            <div class="max-w-md mx-auto">
                <x-button variant="accent">
                    <i class="bi bi-lightning-charge"></i>
                    Action
                </x-button>
                <x-button variant="primary">
                    <i class="bi bi-cloud-upload"></i>
                    Upload
                </x-button>
                <x-button variant="success">
                    <i class="bi bi-check-lg"></i>
                    Confirm
                </x-button>
            </div>
        </x-browser>

        <div class="not-prose">
<pre><code class="language-html rounded-2xl">
&lt;x-button variant="accent"&gt;
    &lt;i class="bi bi-lightning-charge"&gt;&lt;/i&gt;
    Action
&lt;/x-button&gt;
</code></pre>
        </div>
    </section>



    {{-- With Icon --}}
<section class="mt-12 space-y-6">
    <h2 class="text-xl font-semibold">With Icon</h2>
    <x-divider />

    <x-browser>
        <div class="max-w-md mx-auto flex flex-wrap gap-4">

            <x-button variant="accent">
                <i class="bi bi-lightning-charge"></i>
                Action
            </x-button>

            <x-button variant="primary">
                <i class="bi bi-cloud-upload"></i>
                Upload
            </x-button>

            <x-button variant="success">
                <i class="bi bi-check-lg"></i>
                Confirm
            </x-button>

        </div>
    </x-browser>

    <div class="not-prose">
<pre><code class="language-html rounded-2xl">
&lt;x-button variant="accent"&gt;
    &lt;i class="bi bi-lightning-charge"&gt;&lt;/i&gt;
    Action
&lt;/x-button&gt;
</code></pre>
    </div>
</section>


{{-- Custom Class --}}
<section class="mt-12 space-y-6">
    <h2 class="text-xl font-semibold">Custom Class</h2>
    <x-divider />

    <x-browser>
        <div class="max-w-md mx-auto flex flex-wrap gap-4">

            {{-- Rounded full --}}
            <x-button 
                variant="primary" 
                class="rounded-full px-8"
            >
                Rounded
            </x-button>

            {{-- Shadow extra --}}
            <x-button 
                variant="accent" 
                class="shadow-lg hover:scale-105 transition"
            >
                Elevated
            </x-button>

            {{-- Custom background override --}}
            <x-button 
                class="bg-black text-white hover:bg-gray-800"
            >
                Custom
            </x-button>

        </div>
    </x-browser>

    <div class="not-prose">
<pre><code class="language-html rounded-2xl">
&lt;x-button 
    variant="primary" 
    class="rounded-full px-8"
&gt;
    Rounded
&lt;/x-button&gt;

&lt;x-button 
    variant="accent" 
    class="shadow-lg hover:scale-105 transition"
&gt;
    Elevated
&lt;/x-button&gt;

&lt;x-button 
    class="bg-black text-white hover:bg-gray-800"
&gt;
    Custom
&lt;/x-button&gt;
</code></pre>
    </div>
</section>



    {{-- Como funciona --}}
    <section class="mt-16 space-y-6">
        <h2 class="text-xl font-semibold">Como funciona</h2>
        <x-divider />

        <x-card>
            <ul class="space-y-2 text-sm text-gray-600">
                <li><strong>variant</strong> → primary | secondary | accent | success | warning | error</li>
                <li><strong>size</strong> → sm | md | lg | xl</li>
                <li><strong>outline</strong> → ativa modo outline</li>
                <li><strong>disabled</strong> → desativa interação</li>
            </ul>
        </x-card>
    </section>

</x-content>
