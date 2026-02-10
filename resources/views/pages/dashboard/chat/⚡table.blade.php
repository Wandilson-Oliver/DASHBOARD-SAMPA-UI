<?php

use App\Models\ChatFaq;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public string $search = '';
    public string $sortField = 'id';
    public string $sortDirection = 'desc';

    public ?int $activeRowId = null;

    /* =========================
        AUTH
    ========================= */

    public function mount(): void
    {
        abort_unless(
            auth()->user()->hasPermission('chat-faqs.manage'),
            403
        );
    }

    /* =========================
        EVENTS
    ========================= */

    #[On('chatFaq.filters.updated')]
    public function applyFilters(array $filters): void
    {
        $this->search = $filters['search'] ?? '';
        $this->resetPage();
    }

    #[On('chatFaq.saved')]
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

    public function getFaqsProperty()
    {
        return ChatFaq::query()
            ->when($this->search !== '', function ($q) {
                $q->where(function ($qq) {
                    $qq->where('question', 'like', "%{$this->search}%")
                       ->orWhere('answer', 'like', "%{$this->search}%");
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);
    }
};
?>


<x-card>

    {{-- HEADER --}}
    <div
        class="hidden md:grid grid-cols-12 px-6 py-3.5 rounded-xl
               text-xs font-semibold bg-slate-50 text-slate-600"
    >
        {{-- PERGUNTA --}}
        <button wire:click="sortBy('question')" class="col-span-8 flex items-center gap-1">
            Pergunta
            @if($sortField === 'question')
                <i class="bi {{ $sortDirection === 'asc' ? 'bi-arrow-up' : 'bi-arrow-down' }}"></i>
            @endif
        </button>

        {{-- RESPOSTA --}}
        <div class="col-span-2">Pergunta Frequente</div>

        {{-- AÇÕES --}}
        <div class="col-span-2 text-right">Ações</div>
    </div>

    {{-- BODY --}}
    <div class="space-y-1">
        @foreach($this->faqs as $faq)
            <div
                class="
                    grid grid-cols-1 gap-3
                    md:grid-cols-12 md:items-center
                    px-3 py-2 rounded-2xl
                    {{ $loop->even ? 'bg-slate-100' : '' }}
                    {{ $activeRowId === $faq->id ? 'ring-2 ring-primary/30' : '' }}
                    hover:bg-slate-100 transition
                "
            >

                {{-- PERGUNTA --}}
                <div class="col-span-8 font-semibold truncate">
                    {{ $faq->question }}
                </div>

                {{-- RESPOSTA --}}
                <div class="col-span-2 text-sm text-slate-600 line-clamp-2">
                    {{ $faq->question_frequency == 0 ? "Inativo" : 'Ativo' }}
                </div>

                {{-- AÇÕES --}}
                <div class="col-span-2 flex justify-end gap-1">
                    @if(auth()->user()->hasPermission('chat-faqs.manage'))
                        <x-button size="sm" variant="warning" :outline="true"
                            @click="$dispatch('chatFaq.edit', { id: {{ $faq->id }} })">
                            <i class="bi bi-pencil"></i>
                        </x-button>
                    @endif

                    @if(auth()->user()->hasPermission('chat-faqs.manage'))
                        <x-button size="sm" variant="error" :outline="true"
                            @click="$dispatch('chatFaq.delete.confirm', { id: {{ $faq->id }} })">
                            <i class="bi bi-trash"></i>
                        </x-button>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    {{-- FOOTER --}}
    <div class="mt-4 flex flex-col md:flex-row justify-between items-center gap-3 text-sm">
        <span>Total: {{ $this->faqs->total() }}</span>
        {{ $this->faqs->links() }}
    </div>

</x-card>
