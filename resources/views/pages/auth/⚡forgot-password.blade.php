<?php

use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Support\Facades\Password;

new #[Layout('layouts::auth')] class extends Component
{
    public string $email = '';

    protected array $rules = [
        'email' => 'required|email',
    ];

    protected array $messages = [
        'email.required' => 'O campo e-mail é obrigatório.',
        'email.email'    => 'O e-mail informado não é válido.',
    ];

    public function sendResetLink()
    {
        $this->validate();

        $status = Password::sendResetLink([
            'email' => $this->email,
        ]);

        if ($status === Password::RESET_LINK_SENT) {
            session()->flash(
                'success',
                'Link de redefinição enviado para seu e-mail!'
            );

            return;
        }

        $this->addError(
            'email',
            'Não foi possível enviar o link. E-mail não encontrado.'
        );
    }

    // Validação em tempo real (Livewire)
    public function updatedEmail()
    {
        $this->validateOnly('email');
        session()->forget('success');
    }
};
?>

<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-teal-600 to-teal-700 p-4">
    <div class="w-full max-w-md">

        <x-card
            title="Enviar link de redefinição"
            description="Informe seu e-mail para receber o link de redefinição de senha."
        >
            <form wire:submit.prevent="sendResetLink" class="space-y-4">

                <x-input
                    name="email"
                    label="Email"
                    type="email"
                    wire:model.live="email"
                    required
                />

                {{-- SUCCESS MESSAGE --}}
                @if (session()->has('success'))
                    <p class="text-sm text-green-600">
                        {{ session('success') }}
                    </p>
                @endif

                <div class="flex justify-between items-center pt-2">
                    <p class="text-sm text-slate-600">
                        <a
                            href="{{ route('login') }}"
                            class="underline hover:text-teal-600"
                        >
                            Voltar para o login
                        </a>
                    </p>

                    <x-button
                        type="submit"
                        size="lg"
                        wire:loading.attr="disabled"
                        wire:target="sendResetLink"
                    >
                        <span wire:loading.remove wire:target="sendResetLink">
                            Enviar link
                        </span>

                        <span wire:loading wire:target="sendResetLink">
                            Enviando…
                        </span>
                    </x-button>
                </div>
            </form>
        </x-card>

    </div>
</div>

