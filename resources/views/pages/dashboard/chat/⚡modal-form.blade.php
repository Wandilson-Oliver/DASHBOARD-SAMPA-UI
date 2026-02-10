<?php

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\ChatFaq;

new class extends Component
{
    public bool $open = false;
    public bool $isEdit = false;

    public array $form = [
        'id' => null,
        'question' => '',
        'answer' => '',
        'question_frequency' => 0,
    ];

    /* =========================
        EVENTS
    ========================= */

    #[On('chatFaq.create')]
    public function create(): void
    {
        abort_unless(auth()->user()->hasPermission('chat-faqs.manage'), 403);

        $this->resetForm();
        $this->open = true;
    }

    #[On('chatFaq.edit')]
    public function edit(int $id): void
    {
        abort_unless(auth()->user()->hasPermission('chat-faqs.manage'), 403);

        $faq = ChatFaq::findOrFail($id);

        $this->resetForm();

        $this->form = [
            'id' => $faq->id,
            'question' => $faq->question,
            'answer' => $faq->answer,
            'question_frequency' => $faq->question_frequency,
        ];

        $this->isEdit = true;
        $this->open = true;
    }

    /* =========================
        VALIDATION
    ========================= */

    protected function rules(): array
    {
        return [
            'form.question' => ['required', 'string', 'max:255'],
            'form.answer' => ['required', 'string'],
        ];
    }

    /* =========================
        ACTIONS
    ========================= */

    public function save(): void
    {
        $this->validate();

        $faq = ChatFaq::updateOrCreate(
            ['id' => $this->form['id']],
            [
                'question' => $this->form['question'],
                'answer' => $this->form['answer'],
                'question_frequency' => $this->form['question_frequency'],
            ]
        );

        $this->dispatch('chatFaq.saved', ['id' => $faq->id]);

        $this->dispatch(
            'toast',
            type: 'success',
            title: $this->isEdit ? 'FAQ atualizado' : 'FAQ criado',
            message: 'As informações foram salvas com sucesso.'
        );

        $this->open = false;
        $this->resetForm();
    }

    protected function resetForm(): void
    {
        $this->reset(['form', 'isEdit']);
        $this->resetValidation();
        $this->resetErrorBag();
    }
};
?>


<x-modal
    model="open"
    title="{{ $isEdit ? 'Editar FAQ' : 'Novo FAQ' }}"
    subtitle="Preencha a pergunta e a resposta"
    size="4xl"
>
    <form
        wire:submit.prevent="save"
        wire:loading.class="opacity-50 pointer-events-none"
        class="space-y-6"
    >

       <x-select
            name="status"
            label="Status"
            placeholder="Selecione o status"
            wire:model.defer="form.question_frequency"
            :options="[
                ['id' => 1, 'label' => 'Ativo'],
                ['id' => 0, 'label' => 'Inativo'],
            ]"
        />


        {{-- PERGUNTA --}}
        <x-input
            label="Pergunta"
            wire:model.defer="form.question"
            variant="secondary"
            size="lg"
            class="bg-slate-50"
            placeholder="Ex: Como faço para redefinir minha senha?"
        />

        {{-- RESPOSTA --}}
        <div>
            <label class="block text-sm font-medium mb-1">
                Resposta
            </label>

            <textarea
                wire:model.defer="form.answer"
                rows="6"
                class="w-full rounded-lg border border-slate-300 bg-slate-50 p-3"
                placeholder="Digite aqui a resposta que o chatbot irá utilizar..."
            ></textarea>
        </div>

        {{-- ACTIONS --}}
        <div class="flex justify-end gap-2 pt-4">
            <x-button
                type="button"
                variant="secondary"
                size="sm"
                wire:click="$set('open', false)"
            >
                Cancelar
            </x-button>

            <x-button type="submit" variant="primary" size="sm">
                <span wire:loading.remove>Salvar</span>
                <span wire:loading>Salvando...</span>
            </x-button>
        </div>

    </form>
</x-modal>
