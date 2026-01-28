<?php

use Livewire\Component;
use App\Models\User;
use App\Models\Role;
use Livewire\Attributes\On;

new class extends Component
{
    public bool $show = false;
    public ?int $userId = null;
    public ?int $newRoleId = null;

    public function getRolesProperty()
    {
        return Role::orderBy('name')->get();
    }

    #[On('users.change-role.open')]
    public function open(int $id): void
    {
        $user = User::find($id);

        if (! $user || $user->hasRole('admin')) {
            $this->dispatch(
                'toast',
                type: 'warning',
                title: 'Ação não permitida',
                message: 'O papel do administrador não pode ser alterado.'
            );
            return;
        }

        $this->userId = $id;
        $this->newRoleId = null;
        $this->show = true;
    }

    public function change(): void
    {
        $this->validate([
            'newRoleId' => 'required|exists:roles,id',
        ]);

        $user = User::findOrFail($this->userId);

        $user->roles()->sync([$this->newRoleId]);

        // 🔔 avisa a página principal
        $this->dispatch('roles.users.updated');

        $this->dispatch('toast', type: 'success', title: 'Papel alterado');

        $this->reset();
    }
};
?>

<x-modal model="show" title="Alterar papel do usuário" size="md">
    <div class="space-y-4">
        <x-select
            label="Novo papel"
            wire:model.defer="newRoleId"
            :options="$this->roles->map(fn($r) => ['id' => $r->id, 'label' => $r->label])->toArray()"
        />

        <div class="flex justify-end gap-2">
            <x-button variant="secondary" size="sm" wire:click="$set('show', false)">
                Cancelar
            </x-button>

            <x-button size="sm" wire:click="change">
                Confirmar
            </x-button>
        </div>
    </div>
</x-modal>
