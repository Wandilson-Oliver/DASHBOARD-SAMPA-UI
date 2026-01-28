<?php

use App\Models\User;
use Illuminate\Support\Facades\Password;
use Livewire\Component;
use Livewire\Attributes\On;

new class extends Component
{
    public bool $show = false;
    public ?User $user = null;

    /* =========================
        EVENTS
    ========================= */

    #[On('users.reset.confirm')]
    public function open(int $id): void
    {
        $this->user = User::findOrFail($id);
        $this->show = true;
    }

    /* =========================
        ACTIONS
    ========================= */

    public function send(): void
    {
        if (! $this->user) {
            return;
        }

        // 🔐 BLOQUEIO DEFINITIVO (backend)
        if (! $this->user->status) {
            $this->dispatch(
                'toast',
                type: 'warning',
                title: 'Usuário inativo',
                message: 'Não é possível redefinir a senha de um usuário inativo.'
            );

            $this->close();
            return;
        }

        Password::sendResetLink([
            'email' => $this->user->email,
        ]);

        $this->dispatch(
            'toast',
            type: 'success',
            title: 'E-mail enviado',
            message: "Link de redefinição de senha enviado para {$this->user->email}."
        );

        $this->close();
    }

    public function close(): void
    {
        $this->reset(['show', 'user']);
    }
};
?>

<x-modal
    model="show"
    title="Redefinir senha"
    subtitle="O usuário receberá um e-mail para criar uma nova senha"
    size="md"
>
    <div class="space-y-4">

        @if($user && ! $user->status)
            {{-- ALERTA USUÁRIO INATIVO --}}
            <div class="rounded-lg bg-amber-50 border border-amber-200 p-3 text-sm text-amber-800">
                Este usuário está <strong>inativo</strong>.
                Não é possível enviar link de redefinição de senha.
            </div>
        @else
            <p class="text-sm text-slate-600">
                Tem certeza que deseja enviar um link de redefinição de senha para:
            </p>
        @endif

        <div class="font-semibold text-slate-900">
            {{ $user?->email }}
        </div>

        <div class="flex justify-end gap-2 pt-4">
            <x-button
                class="btn-sm btn-secondary"
                wire:click="close"
                wire:loading.remove
                wire:target="send"
            >
                Cancelar
            </x-button>

            <x-button
                class="btn-sm btn-primary"
                wire:click="send"
                wire:loading.attr="disabled"
                wire:target="send"
                :disabled="$user && ! $user->status"
            >
                <span wire:loading.remove wire:target="send">
                    Confirmar envio
                </span>

                <span wire:loading wire:target="send">
                    Enviando...
                </span>
            </x-button>
        </div>

    </div>
</x-modal>
