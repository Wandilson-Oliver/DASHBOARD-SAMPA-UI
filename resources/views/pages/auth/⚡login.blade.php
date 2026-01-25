<?php

use App\Models\User;
use App\Mail\TwoFactorCodeMail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::auth')] class extends Component
{
    /* =========================================================
     | State
     ========================================================= */
    public string $email = '';
    public string $password = '';
    public bool $remember = false;

    // Flags para o Blade (Livewire 4 safe)
    public bool $blocked = false;
    public ?string $blockedMessage = null;

    /* =========================================================
     | Validation
     ========================================================= */
    protected array $rules = [
        'email' => 'required|email',
        'password' => 'required|min:6',
    ];

    protected array $messages = [
        'email.required'    => 'O campo e-mail é obrigatório.',
        'email.email'       => 'O e-mail informado não é válido.',
        'password.required' => 'O campo senha é obrigatório.',
        'password.min'      => 'A senha deve ter no mínimo 6 caracteres.',
    ];

    /* =========================================================
     | Reactivity
     ========================================================= */
    public function updatedEmail(): void
    {
        $this->syncBlockState();
    }

    /* =========================================================
     | Login
     ========================================================= */
    public function login()
    {
        $this->validate();
        $this->syncBlockState();

        if ($this->blocked) {
            return;
        }

        $emailKey = strtolower(trim($this->email));
        $user = User::where('email', $this->email)->first();

        // ❌ Credenciais inválidas
        if (! $user || ! Hash::check($this->password, $user->password)) {
            $this->remember = false;
            $this->registerFailedAttempt($emailKey);
            $this->addError('email', 'Credenciais inválidas.');
            $this->syncBlockState();
            return;
        }

        // 🚫 Usuário inativo
        if (! $user->status) {
            $this->remember = false;
            $this->addError('email', 'Seu acesso está desativado.');
            return;
        }

        // ✅ Login válido → limpa tentativas
        $this->clearAttempts($emailKey);

        // 🔐 Gera código 2FA
        $code = (string) random_int(100000, 999999);

        $user->forceFill([
            'two_factor_code'        => Hash::make($code),
            'two_factor_expires_at'  => now()->addMinutes(10),
        ])->save();

        // ✉️ Envia e-mail
        Mail::to($user->email)->send(
            new TwoFactorCodeMail($code)
        );

        // Guarda estado temporário para verificação
        session([
            '2fa:user:id'  => $user->id,
            '2fa:remember' => $this->remember,
        ]);

        return redirect()->route('verify-2fa');
    }

    /* =========================================================
     | Rate limit helpers
     ========================================================= */
    protected function registerFailedAttempt(string $email): void
    {
        $attemptKey = "login:attempts:$email";
        $blockedKey = "login:blocked_until:$email";

        $attempts = Cache::get($attemptKey, 0) + 1;
        Cache::put($attemptKey, $attempts, now()->addMinutes(15));

        if ($attempts >= 3) {
            $waitSeconds = random_int(60, 600); // 1 a 10 min
            Cache::put(
                $blockedKey,
                now()->timestamp + $waitSeconds,
                $waitSeconds
            );
            Cache::forget($attemptKey);
        }
    }

    protected function clearAttempts(string $email): void
    {
        Cache::forget("login:attempts:$email");
        Cache::forget("login:blocked_until:$email");
    }

    protected function syncBlockState(): void
    {
        if (! $this->email) {
            $this->blocked = false;
            $this->blockedMessage = null;
            return;
        }

        $emailKey = strtolower(trim($this->email));
        $blockedUntil = Cache::get("login:blocked_until:$emailKey");

        if (! $blockedUntil || now()->timestamp >= $blockedUntil) {
            $this->blocked = false;
            $this->blockedMessage = null;
            return;
        }

        $seconds = $blockedUntil - now()->timestamp;

        $this->blocked = true;
        $this->blockedMessage =
            $seconds < 60
                ? "Muitas tentativas. Aguarde {$seconds}s para tentar novamente."
                : 'Muitas tentativas. Aguarde ' . ceil($seconds / 60) . ' min para tentar novamente.';
    }
};
?>

<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-teal-600 to-teal-700 p-4">
    <div class="w-full max-w-md">

        <x-card
            title="Login"
            description="Informe suas credenciais para acessar sua conta."
        >
            <form wire:submit.prevent="login" class="space-y-4">

                <x-input
                    name="email"
                    label="Email"
                    type="email"
                    wire:model.live="email"
                    required
                    :disabled="$blocked"
                />

                <x-password
                    name="password"
                    label="Senha"
                    wire:model.live="password"
                    required
                    :disabled="$blocked"
                />

                {{-- BLOQUEIO --}}
                @if($blocked)
                    <div class="text-sm text-red-600 bg-red-50 border border-red-200 rounded-md p-3">
                        {{ $blockedMessage }}
                    </div>
                @endif

                {{-- Remember + Forgot --}}
                <div class="flex justify-between items-center pt-2 text-sm">
                    <label class="flex items-center gap-2 text-slate-600">
                        <input
                            type="checkbox"
                            wire:model="remember"
                            class="rounded border-slate-300 text-teal-600 focus:ring-teal-500"
                            :disabled="$blocked"
                        >
                        Manter conectado
                    </label>

                    <a
                        href="{{ route('forgot-password') }}"
                        class="underline hover:text-teal-600"
                    >
                        Esqueceu a senha?
                    </a>
                </div>

                <div class="flex justify-end pt-2">
                    <x-button
                        type="submit"
                        size="lg"
                        :disabled="$blocked"
                    >
                        Entrar
                    </x-button>
                </div>

            </form>
        </x-card>

    </div>
</div>
