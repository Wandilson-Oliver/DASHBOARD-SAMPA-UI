<?php

use Livewire\Attributes\Layout;
use Livewire\Component;
 
new #[Layout('layouts::auth')] class extends Component { 
    // ...
};
?>

<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-teal-600 to-teal-700 p-4">
    <div class="w-full max-w-md">
        <x-card
            title="Redefinir Senha"
            description="Informe sua nova senha para sua conta."
        >
            <form wire:submit.prevent="login" class="space-y-4">
                <x-input
                    name="email"
                    label="Email Cadastrado"
                    type="email"
                    wire:model.defer="email"
                />
                <x-password
                    label="Senha"
                    wire:model="password"
                    strength
                />

                <x-password
                    label="Confirmar Senha"
                    wire:model="password_confirmation"
                />

                <div class="flex justify-between items-end pt-2">
                    <div class=" text-sm">
                        <a href="{{ route('login') }}" class="underline hover:text-teal-600">Clique aqui</a>, para ir ate login.
                    </div>
                    <x-button
                        type="submit"
                        size="lg"
                        wire:loading.attr="disabled"
                    >
                        <span wire:loading.remove>
                            Entrar
                        </span>

                        <span wire:loading>
                            Entrando...
                        </span>
                    </x-button>
                </div>
            </form>
        </x-card>
    </div>
</div>
