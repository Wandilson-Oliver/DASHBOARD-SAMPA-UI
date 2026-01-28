<?php

use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Livewire\Attributes\Layout;

new #[Layout('layouts::auth')] class extends Component
{
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $token = '';

    protected array $rules = [
        'email' => 'required|email',
        'password' => 'required|min:6|confirmed',
    ];

    public function mount(string $token): void
    {
        $this->token = $token;
    }

    public function updatePassword(): void
    {
        $this->validate();

        $status = Password::reset(
            [
                'email' => $this->email,
                'password' => $this->password,
                'password_confirmation' => $this->password_confirmation,
                'token' => $this->token,
            ],
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            session()->flash(
                'success',
                'Senha redefinida com sucesso. Faça login para continuar.'
            );

            $this->redirectRoute('login', navigate: true);
            return;
        }

        $this->addError('email', __($status));
    }
};
?>

<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-teal-600 to-teal-700 p-4">
    <div class="w-full max-w-md">
        <x-card
            title="Redefinir Senha"
            description="Informe sua nova senha para sua conta."
        >
            <form
                wire:submit.prevent="updatePassword"
                class="space-y-4"
            >
                <x-input
                    name="email"
                    label="Email cadastrado"
                    type="email"
                    wire:model.defer="email"
                />

                <x-password
                    label="Nova senha"
                    wire:model.defer="password"
                    strength
                />

                <x-password
                    label="Confirmar senha"
                    wire:model.defer="password_confirmation"
                />

                <div class="flex justify-between items-end pt-2">
                    <div class="text-sm">
                        <a
                            href="{{ route('login') }}"
                            class="underline hover:text-teal-600"
                        >
                            Voltar para o login
                        </a>
                    </div>

                    <x-button
                        type="submit"
                        size="lg"
                        wire:loading.attr="disabled"
                    >
                        <span wire:loading.remove>
                            Redefinir senha
                        </span>

                        <span wire:loading>
                            Salvando...
                        </span>
                    </x-button>
                </div>
            </form>
        </x-card>
    </div>
</div>
