<?php

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

new class extends Component
{
    public bool $open = false;

    public ?User $user = null;
    public string $password = '';

    /* =========================
        EVENTS
    ========================= */

    #[On('users.delete.confirm')]
    public function openModal(int $id): void
    {
        abort_unless(
            auth()->user()->hasPermission('users.delete'),
            403
        );

        $user = User::find($id);

        if (! $user || $user->trashed()) {
            return;
        }

        $this->user = $user;
        $this->password = '';
        $this->open = true;
    }

    /* =========================
        ACTIONS
    ========================= */

    public function delete(): void
    {
        if (! $this->user) {
            return;
        }

        // 🔐 valida senha do usuário logado
        if (! Hash::check($this->password, auth()->user()->password)) {
            $this->addError('password', 'Senha incorreta.');
            return;
        }

        $this->user->delete();

        $this->dispatch(
            'toast',
            type: 'success',
            title: 'Usuário removido',
            message: "O usuário {$this->user->name} foi excluído."
        );

        $this->dispatch('users.saved');

        $this->close();
    }

    public function close(): void
    {
        $this->reset(['open', 'user', 'password']);
        $this->resetErrorBag();
    }
};
?>

<x-modal
    model="open"
    title="Excluir usuário"
    subtitle="Confirme sua senha para continuar"
    size="md"
>
    <div class="space-y-4">

        {{-- DADOS DO USUÁRIO --}}
        @if($user)
            <div class="rounded-lg border bg-slate-50 p-4">
                <p class="text-sm text-slate-500">Usuário selecionado</p>

                <p class="font-semibold text-slate-900">
                    {{ $user->name }}
                </p>

                <p class="text-sm text-slate-600">
                    {{ $user->email }}
                </p>
            </div>
        @endif

        {{-- SENHA --}}
        <div>
            <x-password
                label="Sua senha"
                wire:model.defer="password"
                placeholder="Confirme sua senha"
            />
        </div>

        {{-- AÇÕES --}}
        <div class="flex justify-end gap-2 pt-4">
            <x-button
                variant="secondary"
                size="sm"
                wire:click="close"
            >
                Cancelar
            </x-button>

            <x-button
                variant="error"
                size="sm"
                wire:click="delete"
            >
                Confirmar exclusão
            </x-button>
        </div>

    </div>
</x-modal>
