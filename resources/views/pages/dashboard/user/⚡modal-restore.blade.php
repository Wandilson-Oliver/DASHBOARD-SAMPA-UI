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

    #[On('users.restore.confirm')]
    public function openModal(int $id): void
    {
        abort_unless(
            auth()->user()->hasPermission('users.restore'),
            403
        );

        $user = User::withTrashed()->find($id);

        if (! $user || ! $user->trashed()) {
            return;
        }

        $this->user = $user;
        $this->password = '';
        $this->open = true;
    }

    /* =========================
        ACTIONS
    ========================= */

    public function restore(): void
    {
        if (! $this->user) {
            return;
        }

        // 🔐 valida senha do usuário logado
        if (! Hash::check($this->password, auth()->user()->password)) {
            $this->addError('password', 'Senha incorreta.');
            return;
        }

        $this->user->restore();

        $this->dispatch(
            'toast',
            type: 'success',
            title: 'Usuário restaurado',
            message: "O usuário {$this->user->name} foi restaurado com sucesso."
        );

        $this->dispatch('users.saved', ['id' => $this->user->id]);

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
    title="Restaurar usuário"
    subtitle="Confirme sua senha para continuar"
    size="md"
>
    <div class="space-y-4">

        {{-- DADOS DO USUÁRIO --}}
        @if($user)
            <div class="rounded-lg border bg-slate-50 p-4">
                <p class="text-sm text-slate-500">Usuário a ser restaurado</p>

                <p class="font-semibold text-slate-900">
                    {{ $user->name }}
                </p>

                <p class="text-sm text-slate-600">
                    {{ $user->email }}
                </p>
            </div>
        @endif

        {{-- ALERTA --}}
        <div class="rounded-lg bg-amber-50 border border-amber-200 p-3 text-sm text-amber-800">
            Este usuário voltará a ter acesso ao sistema.
        </div>

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
                variant="success"
                size="sm"
                wire:click="restore"
            >
                Confirmar restauração
            </x-button>
        </div>

    </div>
</x-modal>
