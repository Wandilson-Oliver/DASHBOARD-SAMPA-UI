<?php

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Layout;

new #[Layout('layouts::app')] class extends Component
{
    /**
     * Autorização da página
     */
    public function mount(): void
    {
        abort_unless(
            auth()->user()->hasPermission('users.view'),
            403
        );
    }

    /**
     * Computed property
     */
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


<x-content
    title="Listagem de Usuários"
    description="Total: {{ $this->stats['total'] }} |
                 ativos: {{ $this->stats['active'] }} |
                 inativos: {{ $this->stats['inactive'] }}"
>

    {{-- ACTIONS --}}
    <x-slot name="actions">

        {{-- GERENCIAR PAPÉIS / PERMISSÕES --}}
        @if(auth()->user()->hasPermission('roles.manage'))
            <x-button
                size="md"
                variant="primary"
                :outline="true"
                href="{{ route('dashboard.roles') }}"
            >
                <i class="bi bi-shield-lock"></i>
                Permissões e Papéis
            </x-button>
        @endif

        {{-- CRIAR USUÁRIO --}}
        @if(auth()->user()->hasPermission('users.create'))
            <x-button
                size="md"
                variant="primary"
                :outline="true"
                wire:click="$dispatch('users.create')"
            >
                <i class="bi bi-plus"></i>
                Novo Registro
            </x-button>
        @endif

    </x-slot>

    {{-- FILTROS --}}
    <livewire:pages::dashboard.user.filters />

    {{-- TABELA --}}
    <livewire:pages::dashboard.user.table />

    {{-- MODAL: CRIAR / EDITAR USUÁRIO --}}
    @if(auth()->user()->hasPermission('users.create') || auth()->user()->hasPermission('users.edit'))
        <livewire:pages::dashboard.user.modal-form />
    @endif

    {{-- MODAL: RESET DE SENHA --}}
    @if(auth()->user()->hasPermission('users.reset_password'))
        <livewire:pages::dashboard.user.modal-reset-password />
    @endif

    {{-- MODAL: HISTÓRICO DE ACESSOS --}}
    @if(auth()->user()->hasPermission('sessions.view'))
        <livewire:pages::dashboard.user.modal-access />
    @endif


    {{-- MODAL: HISTÓRICO DE ACESSOS --}}
    @if(auth()->user()->hasPermission('users.delete'))
        <livewire:pages::dashboard.user.modal-delete />
    @endif

    {{-- MODAL: HISTÓRICO DE ACESSOS --}}
    @if(auth()->user()->hasPermission('users.restore'))
        <livewire:pages::dashboard.user.modal-restore />
    @endif

</x-content>
