<?php

use Livewire\Component;

new class extends Component
{
    //
};
?>

<x-content title="Faqs" description="Perguntas e respostas frequentes usadas no chat pela ia">

    <x-slot name="actions">

        @if(auth()->user()->hasPermission('chat-faqs.manage'))
            <x-button
                size="md"
                variant="primary"
                :outline="true"
                wire:click="$dispatch('chatFaq.create')"
            >
                <i class="bi bi-plus-lg"></i>
                Criar Faq
            </x-button>
        @endif

    </x-slot>

    <livewire:pages::dashboard.chat.filters />
    <livewire:pages::dashboard.chat.table />

    <livewire:pages::dashboard.chat.modal-form />
</x-content>