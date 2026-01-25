<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::auth')] class extends Component
{
    public string $code = '';

    protected array $rules = [
        'code' => 'required|digits:6',
    ];

    protected array $messages = [
        'code.required' => 'Informe o código enviado por e-mail.',
        'code.digits'   => 'O código deve conter 6 números.',
    ];

    public function verify()
    {
        $this->validate();

        $userId = session('2fa:user:id');

        if (! $userId) {
            abort(403);
        }

        $user = User::find($userId);

        if (! $user) {
            abort(403);
        }

        
        // ⏱ Expirado
        if (
            ! $user->two_factor_code ||
            ! $user->two_factor_expires_at ||
            now()->greaterThan($user->two_factor_expires_at)
        ) {
            $this->addError('code', 'Código expirado. Solicite um novo.');
            return;
        }

        // 🔐 Confere código (STRING!)
        if (! Hash::check((string) $this->code, $user->two_factor_code)) {
            $this->addError('code', 'Código inválido.');
            return;
        }

        // ✅ Limpa dados 2FA
        $user->forceFill([
            'two_factor_code' => null,
            'two_factor_expires_at' => null,
        ])->save();

        session()->forget('2fa:user:id');

        $remember = session('2fa:remember', false);

        // 🔐 LOGIN FINAL
        Auth::login($user, $remember);
        session()->regenerate();

        session()->forget([
            '2fa:user:id',
            '2fa:remember',
        ]);

        return redirect()->route('dashboard.index');
    }

    public function resend()
    {
        $userId = session('2fa:user:id');

        if (! $userId) {
            abort(403);
        }

        $user = User::findOrFail($userId);

        // 🔐 Gera NOVO código (STRING)
        $code = (string) random_int(100000, 999999);

        $user->forceFill([
            'two_factor_code' => Hash::make($code),
            'two_factor_expires_at' => now()->addMinutes(10),
        ])->save();

        Mail::to($user->email)->send(
            new \App\Mail\TwoFactorCodeMail($code)
        );

        session()->flash('success', 'Novo código enviado para seu e-mail.');
    }
};

?>

<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-teal-600 to-teal-700 p-4">
    <div class="w-full max-w-md">

        <x-card
            title="Verificação de segurança"
            description="Informe o código de 6 dígitos enviado para seu e-mail."
        >
            <form wire:submit.prevent="verify" class="space-y-4">

                <x-input
                    label="Código de verificação"
                    wire:model.live="code"
                    inputmode="numeric"
                    maxlength="6"
                    placeholder="000000"
                    required
                />

                @if (session()->has('success'))
                    <p class="text-sm text-green-600">
                        {{ session('success') }}
                    </p>
                @endif

                <div class="flex justify-between items-center pt-2">
                    <button
                        type="button"
                        wire:click="resend"
                        class="text-sm underline text-slate-600 hover:text-teal-600"
                        wire:loading.attr="disabled"
                    >
                        Reenviar código
                    </button>

                    <x-button
                        type="submit"
                        size="lg"
                        wire:loading.attr="disabled"
                        wire:target="verify"
                    >
                        <span wire:loading.remove wire:target="verify">
                            Verificar
                        </span>

                        <span wire:loading wire:target="verify">
                            Verificando…
                        </span>
                    </x-button>
                </div>

            </form>
        </x-card>

    </div>
</div>
