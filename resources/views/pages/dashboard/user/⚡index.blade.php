<?php

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;

new #[Layout('layouts::app')] class extends Component
{
    // ✅ computed property
    public function getStatsProperty(): array
    {
        return [
            'total'    => User::count(),
            'active'   => User::where('status', true)->count(),
            'inactive' => User::where('status', false)->count(),
        ];
    }
};
?>


<div class="w-full space-y-5">

    {{-- HEADER --}}
    <x-header
        title="Perfil"
        description="Gerencie suas informações pessoais e segurança">
        <x-slot name="buttons">
            <x-button
                size="md"
                variant="primary"
                :outline="true"
                wire:click="$dispatch('users.create')"
            >
                <i class="bi bi-plus"></i>
                Novo Registro
            </x-button>
        </x-slot>
    </x-header>

    {{-- FILTROS --}}
    <livewire:pages::dashboard.user.filters />

    {{-- TABELA --}}
    <livewire:pages::dashboard.user.table />

    {{-- MODAL: CRIAR / EDITAR USUÁRIO --}}
    <livewire:pages::dashboard.user.modal-form />

    {{-- MODAL: RESET DE SENHA --}}
    <livewire:pages::dashboard.user.modal-reset-password />

    {{-- MODAL: HISTÓRICO DE ACESSOS --}}
    <livewire:pages::dashboard.user.modal-access />
</div>

