<?php

use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

new #[Layout('layouts::app')] class extends Component
{
    use WithFileUploads;

    public User $user;

    public string $name = '';
    public string $email = '';
    public ?string $phone = null;

    public $photo;

    public ?string $password = null;
    public ?string $password_confirmation = null;

    public bool $showPasswordBox = false;

    // 🔐 força da senha
    public string $passwordStrength = '';
    public int $passwordScore = 0;

    public function mount()
    {
        $this->user = auth()->user();
        $this->name = $this->user->name;
        $this->phone = $this->user->phone;
        $this->email = $this->user->email;
    }

    /* =================================================
     | Password strength
     ================================================= */
    protected function evaluatePasswordStrength(string $password): void
    {
        $score = 0;

        if (strlen($password) >= 8)  $score++;
        if (strlen($password) >= 12) $score++;
        if (preg_match('/[a-z]/', $password)) $score++;
        if (preg_match('/[A-Z]/', $password)) $score++;
        if (preg_match('/\d/', $password))    $score++;
        if (preg_match('/[^a-zA-Z\d]/', $password)) $score++;

        $this->passwordScore = $score;

        $this->passwordStrength = match (true) {
            $score <= 2 => 'fraca',
            $score <= 4 => 'media',
            $score <= 5 => 'forte',
            default     => 'muito-forte',
        };
    }

    /**
     * 🚨 ESTE MÉTODO SÓ DISPARA COM wire:model (SEM defer)
     */
    public function updatedPassword($value)
    {
        if (! $value) {
            $this->passwordStrength = '';
            $this->passwordScore = 0;
            return;
        }

        $this->evaluatePasswordStrength($value);
    }

    /* =================================================
     | Actions
     ================================================= */
    public function generatePassword()
    {
        $password =
            Str::random(4) .
            strtoupper(Str::random(2)) .
            rand(10, 99) .
            '!@#';

        $password = str_shuffle($password);

        // 🔥 setar password dispara updatedPassword automaticamente
        $this->password = $password;
        $this->password_confirmation = $password;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|min:3',
            'photo' => 'nullable|image|max:2048',
            'phone' => 'nullable|min:10',
            'email' => 'required|email',
            'password' => 'nullable|min:8|confirmed',
        ]);

        $this->user->update([
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
        ]);

        if ($this->photo) {
            $path = $this->photo->store('avatars', 'public');
            $this->user->update(['avatar' => $path]);
        }

        if ($this->password) {
            $this->user->update([
                'password' => Hash::make($this->password),
            ]);
        }

        $this->reset([
            'photo',
            'password',
            'password_confirmation',
            'showPasswordBox',
            'passwordStrength',
            'passwordScore',
        ]);

        $this->dispatch(
            'toast',
            type: 'success',
            title: 'Perfil atualizado!',
            message: 'Suas informações foram salvas com sucesso.'
        );
    }
};
?>

<div class="w-full space-y-6">

    {{-- HEADER --}}
    <x-header
        title="Perfil"
        description="Gerencie suas informações pessoais e segurança">
    </x-header>

    <x-card>
        <form wire:submit.prevent="save" class="flex gap-10">

            {{-- FOTO --}}
            <div class="w-4/12 space-y-4">
                <div class="relative group">
                    @if ($photo)
                        <img src="{{ $photo->temporaryUrl() }}"
                             class="rounded-xl w-full aspect-square object-cover">
                    @elseif ($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}"
                             class="rounded-xl w-full aspect-square object-cover">
                    @else
                        <div
                            class="w-full aspect-square rounded-xl bg-slate-200
                                   flex items-center justify-center
                                   text-4xl font-bold text-slate-600">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                    @endif

                    <label
                        class="absolute inset-0 bg-black/40 text-white opacity-0
                               group-hover:opacity-100 transition
                               flex items-center justify-center
                               rounded-xl cursor-pointer">
                        Alterar foto
                        <input type="file" wire:model="photo" class="hidden" accept="image/*">
                    </label>
                </div>
            </div>

            {{-- DADOS --}}
            <div class="w-8/12 space-y-6">  

                <div class="border-b-4 pb-5 border-b-slate-200">
                    <div>
                        {{-- NOME --}}
                        <x-input
                            wire:model.defer="name"
                            label="Nome"
                        />

                        <x-input
                            wire:model.defer="email"
                            label="Email"
                        />
                    </div>

                    <div class="w-4/12">
                        <x-phone
                            wire:model.defer="phone"
                            label="Whatsapp"
                        />
                    </div>
                </div>

                {{-- SENHA --}}
                <div class="border border-dashed border-slate-300 rounded-xl p-4">

                    <button
                        type="button"
                        wire:click="$toggle('showPasswordBox')"
                        class="flex items-center gap-2 text-sm font-semibold text-slate-700 cursor-pointer">
                        <i class="bi bi-shield-lock"></i>
                        Alterar senha
                    </button>

                    @if ($showPasswordBox)
                        <div class="mt-4 space-y-4">

                            <div class="flex gap-5 w-10/12">
                                {{-- 🚨 SEM defer --}}
                                <x-password
                                    wire:model="password"
                                    label="Senha"
                                    size="lg"
                                    strength
                                />

                                <x-password
                                    wire:model.defer="password_confirmation"
                                    label="Confirmar senha"
                                    size="lg"
                                />
                            </div>

                            {{-- FORÇA DA SENHA --}}
                            @if ($passwordStrength)
                                <div class="w-full max-w-md space-y-1">
                                    <div class="h-1.5 rounded-full bg-slate-200 overflow-hidden">
                                        <div
                                            class="h-full transition-all"
                                            style="
                                                width: {{ min($passwordScore * 15, 100) }}%;
                                                background-color:
                                                    {{ match($passwordStrength) {
                                                        'fraca' => 'var(--error)',
                                                        'media' => 'var(--warning)',
                                                        'forte' => 'var(--info)',
                                                        'muito-forte' => 'var(--success)',
                                                    } }};
                                            ">
                                        </div>
                                    </div>

                                    <p
                                        class="text-xs font-semibold"
                                        style="color:
                                            {{ match($passwordStrength) {
                                                'fraca' => 'var(--error)',
                                                'media' => 'var(--warning)',
                                                'forte' => 'var(--info)',
                                                'muito-forte' => 'var(--success)',
                                            } }}">
                                        {{ match($passwordStrength) {
                                            'fraca' => 'Senha fraca',
                                            'media' => 'Senha média',
                                            'forte' => 'Senha forte',
                                            'muito-forte' => 'Senha muito forte',
                                        } }}
                                    </p>
                                </div>
                            @endif

                            <div class="flex justify-between items-center">
                                <x-button
                                    type="button"
                                    class="btn-outline btn-secondary"
                                    wire:click="generatePassword">
                                    Gerar senha segura
                                </x-button>

                                @if ($password)
                                    <button
                                        type="button"
                                        class="text-xs text-slate-500 hover:underline"
                                        x-data
                                        @click="navigator.clipboard.writeText('{{ $password }}')">
                                        Copiar senha
                                    </button>
                                @endif
                            </div>

                        </div>
                    @endif
                </div>

                {{-- ACTION --}}
                <div class="flex justify-end">
                    <x-button variant="primary" size="lg" class="mt-4" type="submit">
                        Salvar alterações
                    </x-button>
                </div>

            </div>
        </form>
    </x-card>
</div>
