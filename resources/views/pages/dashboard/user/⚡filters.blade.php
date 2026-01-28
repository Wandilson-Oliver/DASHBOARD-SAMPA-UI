<?php

use Livewire\Component;
use Livewire\Attributes\Layout;

new #[Layout('layouts::app')] class extends Component
{
    public string $search = '';
    public string $status = '';

    /* =================================================
     | Watchers explícitos (melhor prática)
     ================================================= */
    public function updatedSearch(): void
    {
        $this->emitFilters();
    }

    public function updatedStatus(): void
    {
        $this->emitFilters();
    }

    protected function emitFilters(): void
    {
        $this->dispatch('users.filters.updated', [
            'search' => $this->search,
            'status' => $this->status,
        ]);
    }

    public function clear(): void
    {
        $this->reset(['search', 'status']);
        $this->emitFilters();
    }
};

?>


<x-card class="mb-5">
    <div
        class="
            grid grid-cols-1 gap-4
            lg:grid-cols-4
        "
    >
        {{-- SEARCH --}}
        <div class="lg:col-span-2">
            <x-input
                label="Buscar"
                variant="success"
                size="lg"
                icon="bi bi-search"
                placeholder="Buscar usuário..."
                class="bg-slate-50"
                wire:model.live.debounce.500ms="search"
            />
        </div>

        {{-- STATUS --}}
        <div>
            <x-select
                label="Status"
                variant="success"
                size="lg"

                class="bg-slate-50"
                placeholder="Todos"
                :options="[
                    ['id' => 'active', 'label' => 'Ativo'],
                    ['id' => 'inactive', 'label' => 'Inativo'],
                ]"
                wire:model.live="status"
            />
        </div>

        {{-- ACTION --}}
        <div class="flex items-end mb-2">
            <x-button
                variant="secondary"
                class="w-full py-3.5"
                wire:click="clear"
                size="lg"
                :disabled="!$search && !$status"
            >
                <i class="bi bi-x-circle"></i>
                Limpar
            </x-button>
        </div>
    </div>
</x-card>

