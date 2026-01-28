<?php

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\DB;
use App\Models\UserLogin;

new #[Layout('layouts::app')] class extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public bool $open = false;
    public ?int $userId = null;

    public string $currentSessionId;

    public function mount(): void
    {
        $this->currentSessionId = session()->getId();
    }

    #[On('logins.open')]
    public function open(int $id): void
    {
        $this->userId = $id;
        $this->open = true;
        $this->resetPage();
    }

    public function close(): void
    {
        $this->reset(['open', 'userId']);
    }

    public function getLoginsProperty()
    {
        if (! $this->open || ! $this->userId) {
            return collect();
        }

        return UserLogin::query()
            ->with('user:id,name,email')
            ->where('user_id', $this->userId)
            ->latest('logged_in_at')
            ->paginate(10);
    }

    public function terminateSession(int $loginId): void
    {
        $login = UserLogin::whereKey($loginId)
            ->where('user_id', $this->userId)
            ->whereNull('logged_out_at')
            ->first();


        if (! $login) {
            return;
        }

        // 🚫 Nunca encerrar a sessão atual
        if ($login->session_id === $this->currentSessionId) {
            $this->dispatch(
                'toast',
                type: 'warning',
                title: 'Ação não permitida',
                message: 'Você não pode encerrar a sessão atual.'
            );
            return;
        }

        // 🔥 Encerra a sessão REAL
        if (! empty($login->session_id)) {
            DB::table('sessions')
                ->where('id', $login->session_id)
                ->delete();
        }

        // 🧾 Fecha o histórico
        $login->forceFill([
            'logged_out_at' => now(),
        ])->save();

        // 🔄 Atualiza a lista
        $this->resetPage();

        $this->dispatch(
            'toast',
            type: 'success',
            title: 'Sessão encerrada',
            message: 'A sessão foi encerrada com sucesso.'
        );
    }
};
?>


<x-modal
    model="open"
    title="Histórico de acessos"
    subtitle="Sessões e dispositivos conectados:{{ $userId ? ' ' . \App\Models\User::find($userId)?->name : '' }}"
    size="4xl"
>
    <div class="space-y-4">

        @if(! $userId)
            <p class="text-sm text-slate-500">
                Selecione um usuário para visualizar o histórico.
            </p>

        @elseif($this->logins->isEmpty())
            <p class="text-sm text-slate-500">
                Nenhuma sessão encontrada.
            </p>

        @else
            <div class="space-y-3">
                @foreach($this->logins as $login)
                    @php
                        $isCurrent =
                            auth()->id() === $userId &&
                            is_null($login->logged_out_at) &&
                            $login->session_id === session()->getId();

                        $endAt = $login->logged_out_at ?? now();
                        $duration = $login->logged_in_at->diff($endAt);
                    @endphp

                    <div
                        class="
                            grid grid-cols-1 md:grid-cols-6 gap-4 items-center
                            px-4 py-3 rounded-xl border
                            {{ $isCurrent
                                ? 'bg-emerald-50 border-emerald-300'
                                : 'bg-slate-50 border-slate-200'
                            }}
                        "
                    >
                        <div class="text-sm font-semibold">
                            {{ $login->browser }} · {{ $login->platform }}
                        </div>

                        <div class="text-sm">
                            {{ $login->ip_address }}
                        </div>

                        <div class="text-sm">
                            {{ $login->logged_in_at->format('d/m/Y H:i') }}
                        </div>

                        <div class="text-sm font-semibold">
                            {{ $duration->d }}d {{ $duration->h }}h {{ $duration->i }}m
                        </div>

                        <div class="text-sm">
                            @if($login->logged_out_at)
                                <span class="text-slate-400">Encerrada</span>
                            @elseif($isCurrent)
                                <span class="text-emerald-700 font-semibold">
                                    Sessão atual
                                </span>
                            @else
                                <span class="text-emerald-600">Ativa</span>
                            @endif
                        </div>

                        <div class="text-right">
                            @if(! $login->logged_out_at && ! $isCurrent)
                                <x-button
                                    size="sm"
                                    variant="error"
                                    :outline="true"
                                    wire:click="terminateSession({{ $login->id }})"
                                >
                                    <i class="bi bi-box-arrow-right"></i>
                                    Encerrar
                                </x-button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-4">
                {{ $this->logins->links() }}
            </div>
        @endif

        <div class="flex justify-end pt-4">
            <x-button
                type="button"
                class="btn-sm btn-secondary"
                wire:click="close"
            >
                Fechar
            </x-button>
        </div>

        <div>{{ $this->logins->count() }}, registros de login.</div>
    </div>
</x-modal>
