<?php

use Livewire\Component;
use Livewire\Attributes\Layout;

new #[Layout('layouts::doc')] class extends Component
{
    
};
?>

<x-content>

    {{-- Header --}}
    <div class="mb-10">
        <div class="space-y-3">

            <h1 class="text-3xl font-bold tracking-tight">
                Documentação
            </h1>

            <p class="text-gray-600 text-base max-w-2xl">
                Aqui você encontrará exemplos de código, dicas de uso e melhores práticas 
                para aproveitar ao máximo os recursos oferecidos pelo template.
            </p>
        </div>
    </div>


    {{-- Instalação --}}
    <section class="space-y-6">

        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold">
                Instalação
            </h2>
        </div>

        <x-divider />

        <div class="not-prose">
<pre class="!m-0"><code class="language-bash rounded-2xl">
git clone git@github.com:Wandilson-Oliver/DASHBOARD-SAMPA-UI.git
cd DASHBOARD-SAMPA-UI
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
php artisan serve
</code></pre>
        </div>

    </section>


    {{-- Aviso --}}
    <section class="mt-10">
        <x-card variant="info">
            <div class="space-y-3">
                <h3 class="font-semibold">
                    Verifique as dependências e senha do seeder
                </h3>

                <p class="text-sm opacity-90">
                    Antes de iniciar, certifique-se de ter o PHP, Composer, Node.js 
                    e npm instalados.
                </p>

                <div class="flex flex-wrap gap-2 pt-2">
                    <x-badge variant="secondary">
                        Email: wandilson.oliver@gmail.com
                    </x-badge>

                    <x-badge variant="warning">
                        Senha: password
                    </x-badge>
                </div>
            </div>
        </x-card>
    </section>


    {{-- Stack --}}
    <section class="mt-12 space-y-6">

        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold">
                Stack Principal
            </h2>

            <x-badge variant="success">Core</x-badge>
        </div>

        <x-divider />

        <div class="grid md:grid-cols-2 gap-4">

            <x-card>
                <h4 class="font-semibold mb-2">Backend</h4>
                <ul class="space-y-1 text-sm text-gray-600">
                    <li>Laravel 12+</li>
                    <li>Livewire v4</li>
                </ul>
            </x-card>

            <x-card>
                <h4 class="font-semibold mb-2">Frontend</h4>
                <ul class="space-y-1 text-sm text-gray-600">
                    <li>Alpine.js</li>
                    <li>Tailwind CSS</li>
                </ul>
            </x-card>

        </div>

    </section>


    {{-- ========================================= --}}
    {{-- SISTEMA DE ESTILOS --}}
    {{-- ========================================= --}}

    <section class="mt-16 space-y-6">
        <h2 class="text-xl font-semibold">
            Sistema de Estilos
        </h2>

        <x-divider />

        <x-card>
            <p class="text-sm text-gray-600">
                O sistema de estilos é dividido em:
            </p>

            <ul class="mt-3 space-y-2 text-sm text-gray-600">
                <li><strong>app.css</strong> → Variáveis globais e tokens do tema</li>
                <li><strong>Arquivos CSS dos componentes</strong> → Estilos específicos (button.css, input.css, etc.)</li>
            </ul>
        </x-card>
    </section>


    {{-- app.css --}}
    <section class="mt-12 space-y-6">
        <h3 class="text-lg font-semibold">
            app.css
        </h3>

        <x-card>
            <p class="text-sm text-gray-600">
                O <strong>app.css</strong> centraliza as variáveis globais do sistema.
                Todas as cores semânticas vêm daqui.
            </p>
        </x-card>

        <div class="not-prose">
<pre><code class="language-css rounded-2xl">
:root {
    --color-primary: #2563eb;
    --color-secondary: #64748b;
    --color-accent: #7c3aed;
    --color-success: #16a34a;
    --color-warning: #f59e0b;
    --color-error: #dc2626;
}
</code></pre>
        </div>

        <x-card>
            <p class="text-sm text-gray-600">
                Para personalizar o tema, altere essas variáveis.
                Todos os componentes serão atualizados automaticamente.
            </p>
        </x-card>
    </section>


    {{-- CSS dos Componentes --}}
    <section class="mt-12 space-y-6">
        <h3 class="text-lg font-semibold">
            CSS dos Componentes
        </h3>

        <x-card>
            <p class="text-sm text-gray-600">
                Cada componente possui um arquivo próprio,
                como <code>button.css</code> ou <code>input.css</code>.
                Eles utilizam as variáveis do <strong>app.css</strong>.
            </p>
        </x-card>

        <div class="not-prose">
<pre><code class="language-css rounded-2xl">
.btn-primary {
    background: var(--color-primary);
}

.input-accent {
    border-color: var(--color-accent);
}
</code></pre>
        </div>
    </section>


    {{-- Criando nova variante --}}
    <section class="mt-12 space-y-6">
        <h3 class="text-lg font-semibold">
            Criando Nova Variante
        </h3>

        <x-card>
            <p class="text-sm text-gray-600">
                1. Adicione uma nova variável no <strong>app.css</strong>
            </p>
        </x-card>

        <div class="not-prose">
<pre><code class="language-css rounded-2xl">
:root {
    --color-brand: #14b8a6;
}
</code></pre>
        </div>

        <x-card>
            <p class="text-sm text-gray-600">
                2. Use essa variável no CSS do componente
            </p>
        </x-card>

        <div class="not-prose">
<pre><code class="language-css rounded-2xl">
.btn-brand {
    background: var(--color-brand);
}
</code></pre>
        </div>
    </section>


    {{-- Boas práticas --}}
    <section class="mt-12 space-y-6">
        <h3 class="text-lg font-semibold">
            Boas Práticas
        </h3>

        <x-card>
            <ul class="space-y-2 text-sm text-gray-600">
                <li>✔ Use sempre variáveis globais</li>
                <li>✔ Evite cores fixas dentro dos componentes</li>
                <li>✔ Centralize mudanças no app.css</li>
                <li>✔ Mantenha padrão semântico (primary, accent, success...)</li>
            </ul>
        </x-card>
    </section>

</x-content>
