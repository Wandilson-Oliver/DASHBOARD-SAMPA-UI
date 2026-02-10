<?php

use Livewire\Component;
use Livewire\Attributes\Layout;

new class extends Component
{
    public string $search = '';

    /* =================================================
     | WATCHERS
     ================================================= */

    public function updatedSearch(): void
    {
        $this->emitFilters();
    }

    /* =================================================
     | EMIT
     ================================================= */

    protected function emitFilters(): void
    {
        $this->dispatch('chatFaq.filters.updated', [
            'search' => $this->search,
        ]);
    }

    /* =================================================
     | ACTIONS
     ================================================= */

    public function clear(): void
    {
        $this->reset('search');
        $this->emitFilters();
    }
};
?>

<x-card class="mb-5">
    <div class="grid grid-cols-1 gap-4 lg:grid-cols-12">

        {{-- SEARCH --}}
        <div class="lg:col-span-9">
            <x-input
                label="Buscar"
                variant="success"
                size="lg"
                icon="bi bi-search"
                placeholder="Buscar pergunta ou resposta..."
                class="bg-slate-50"
                wire:model.live.debounce.500ms="search"
            />
        </div>

        {{-- ACTION --}}
        <div class="lg:col-span-3 flex items-end mb-2">
            <x-button
                variant="secondary"
                class="w-full py-3.5"
                wire:click="clear"
                size="lg"
                :disabled="!$search"
            >
                <i class="bi bi-x-circle"></i>
                Limpar
            </x-button>
        </div>

    </div>
</x-card>
