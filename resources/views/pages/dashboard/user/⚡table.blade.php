<?php

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public string $search = '';
    public string $status = '';

    public string $sortField = 'id';
    public string $sortDirection = 'desc';

    public ?int $activeRowId = null;

    /* =========================
        EVENTS
    ========================= */

    #[On('users.filters.updated')]
    public function applyFilters(array $filters): void
    {
        $this->search = $filters['search'] ?? '';
        $this->status = $filters['status'] ?? '';

        $this->resetPage();
    }

    #[On('users.saved')]
    public function refreshTable(array $payload = []): void
    {
        $this->activeRowId = $payload['id'] ?? null;
        $this->resetPage();
    }

    /* =========================
        SORT
    ========================= */

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc'
                ? 'desc'
                : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }

        $this->resetPage();
    }

    /* =========================
        DATA
    ========================= */

    public function getUsersProperty()
    {
        return User::query()
            ->when($this->search !== '', fn ($q) =>
                $q->where(fn ($qq) =>
                    $qq->where('name', 'like', "%{$this->search}%")
                       ->orWhere('email', 'like', "%{$this->search}%")
                )
            )
            ->when($this->status !== '', fn ($q) =>
                $q->where('status', $this->status === 'active')
            )
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);
    }

    /* =========================
        ACTIONS
    ========================= */

    public function toggleStatus(int $id): void
    {
        $user = User::findOrFail($id);
        $user->update(['status' => ! $user->status]);

        $this->activeRowId = $id;

        $this->dispatch('users.saved', ['id' => $id]);
    }
};
?>

<x-card class="">

    {{-- HEADER (DESKTOP) --}}
    <div class="hidden md:grid grid-cols-7 px-6 py-3.5 rounded-xl text-xs font-semibold bg-slate-50 text-slate-600">
        <button wire:click="sortBy('name')" class="col-span-2 flex items-center gap-1 cursor-pointer">
            Nome
            @if($sortField === 'name')
                <i class="bi {{ $sortDirection === 'asc' ? 'bi-arrow-up' : 'bi-arrow-down' }}"></i>
            @endif
        </button>

        <button wire:click="sortBy('email')" class="col-span-2 flex items-center gap-1 cursor-pointer">
            Email
            @if($sortField === 'email')
                <i class="bi {{ $sortDirection === 'asc' ? 'bi-arrow-up' : 'bi-arrow-down' }}"></i>
            @endif
        </button>

        <button wire:click="sortBy('created_at')" class="flex items-center gap-1 cursor-pointer">
            Criado em
            @if($sortField === 'created_at')
                <i class="bi {{ $sortDirection === 'asc' ? 'bi-arrow-up' : 'bi-arrow-down' }}"></i>
            @endif
        </button>

        <button wire:click="sortBy('status')" class="flex items-center gap-1 cursor-pointer">
            Status
            @if($sortField === 'status')
                <i class="bi {{ $sortDirection === 'asc' ? 'bi-arrow-up' : 'bi-arrow-down' }}"></i>
            @endif
        </button>

        <div class="text-right">Ações</div>
    </div>

    {{-- BODY --}}
    <div class="space-y-2 px-3 py-3">
        @foreach($this->users as $user)
            @php
                $initials = collect(explode(' ', $user->name))
                    ->map(fn ($n) => strtoupper(substr($n, 0, 1)))
                    ->take(2)
                    ->implode('');
            @endphp

            <div
                class="
                    grid grid-cols-1 gap-2
                    md:grid md:grid-cols-7 md:items-center
                    px-3 py-2 rounded-2xl
                    {{ $loop->index % 2 === 0 ? 'bg-white' : 'bg-slate-50' }}
                    {{ $activeRowId === $user->id ? 'ring-2 ring-primary/30' : '' }}
                    hover:bg-slate-100 transition
                "
            >
                {{-- AVATAR + NOME --}}
                <div class="md:col-span-2 flex items-center gap-3">
                    <div class="shrink-0">
                        @if ($user->avatar)
                            <img
                                src="{{ asset('storage/' . $user->avatar) }}"
                                alt="{{ $user->name }}"
                                class="w-12 h-12 rounded-full object-cover"
                            >
                        @else
                            <div
                                class="w-12 h-12 rounded-full
                                    bg-slate-200 flex items-center justify-center
                                    text-sm font-semibold text-slate-600"
                            >
                                {{ $initials }}
                            </div>
                        @endif
                    </div>

                    <div class="min-w-0">
                        <div class="text-xs text-slate-400 md:hidden">Nome</div>
                        <div class="font-semibold truncate">
                            {{ $user->name }}
                        </div>
                    </div>
                </div>

                {{-- EMAIL --}}
                <div class="md:col-span-2">
                    <div class="text-xs text-slate-400 md:hidden">Email</div>
                    <div class="text-sm break-all">{{ $user->email }}</div>
                </div>

                {{-- CRIADO --}}
                <div>
                    <div class="text-xs text-slate-400 md:hidden">Criado em</div>
                    <div class="text-sm">{{ $user->created_at->format('d/m/Y') }}</div>
                </div>

                {{-- STATUS --}}
                <div>
                    <div class="text-xs text-slate-400 md:hidden">Status</div>
                    <x-badge
                        size="sm"
                        :variant="$user->status ? 'success' : 'danger'"
                        wire:click="toggleStatus({{ $user->id }})"
                        class="cursor-pointer"
                    >
                        {{ $user->status ? 'Ativo' : 'Inativo' }}
                    </x-badge>
                </div>

                {{-- AÇÕES --}}
                <div class="flex justify-end gap-2">
                    <x-button
                        size="sm"
                        variant="primary"
                        :outline="true"
                        @click="$dispatch('logins.open', { id: {{ $user->id }} })"
                    >
                        <i class="bi bi-clock-history"></i>
                        Acessos
                    </x-button>



                    <x-button
                        size="sm"
                        variant="warning"
                        :outline="true"
                        @click="$dispatch('users.edit', { id: {{ $user->id }} })"
                    >
                        <i class="bi bi-pencil"></i>
                    </x-button>


                    <x-button
                        size="sm"
                        variant="primary"
                        :outline="true"
                        @click="$dispatch('users.reset.confirm', { id: {{ $user->id }} })"
                    >
                        <i class="bi bi-key"></i>
                    </x-button>

                </div>

            </div>
        @endforeach
    </div>


    {{-- FOOTER --}}
    <div class="mt-4 flex flex-col md:flex-row justify-between items-center gap-3 text-sm">
        <span>Total: {{ $this->users->total() }}</span>
        {{ $this->users->links() }}
    </div>

</x-card>
