<?php

use Livewire\Component;
use App\Services\FaqChatService;
use App\Models\ChatFaq;

new class extends Component
{
    public function ask(string $message): array
    {
        return app(FaqChatService::class)->ask($message);
    }

    public function getFaqsProperty()
    {
        return ChatFaq::select('question')
            ->where('question_frequency', 1)
            ->orderBy('id')
            ->limit(5)
            ->get();
    }
};
?>

<style>
    [x-cloak] { display: none !important; }
    .chat-scroll { scroll-behavior: smooth; }
</style>

<div
    x-data="{
        open: false,
        input: '',
        loading: false,
        showFaqs: true,
        messages: [
            {
                id: 1,
                from: 'bot',
                type: 'text',
                text: 'Olá! 👋\nSelecione uma pergunta frequente ou digite sua dúvida.'
            }
        ],

        toggle() {
            this.open = !this.open
            this.$nextTick(() => this.scroll())
        },

        async sendMessage(text) {
            if (!text.trim()) return

            this.showFaqs = false
            this.loading = true
            this.input = ''

            this.messages.push({
                id: Date.now(),
                from: 'user',
                type: 'text',
                text
            })

            this.$nextTick(() => this.scroll())

            try {
                const response = await $wire.ask(text)

                if (response.type === 'text') {
                    this.messages.push({
                        id: Date.now() + 1,
                        from: 'bot',
                        type: 'text',
                        text: response.data
                    })
                }

                if (response.type === 'contact') {
                    this.messages.push({
                        id: Date.now() + 1,
                        from: 'bot',
                        type: 'contact',
                        contact: response.data
                    })
                }
            } catch {
                this.messages.push({
                    id: Date.now() + 2,
                    from: 'bot',
                    type: 'text',
                    text: '😕 Não consegui responder agora.\nTente novamente.'
                })
            }

            this.loading = false
            this.$nextTick(() => this.scroll())
        },

        send() {
            this.sendMessage(this.input)
        },

        sendPreset(text) {
            this.sendMessage(text)
        },

        scroll() {
            const el = this.$refs.messages
            el.scrollTo({ top: el.scrollHeight, behavior: 'smooth' })
        },

        format(text) {
            return (text ?? '').replace(/\\n/g, '<br>')
        }
    }"
    class="fixed bottom-6 right-6 z-50 font-sans"
>
    {{-- BOTÃO --}}
    <button
        @click="toggle"
        class="w-14 h-14 rounded-full bg-primary shadow-xl
               flex items-center justify-center hover:scale-105 transition cursor-pointer"
    >
        <img src="/images/chatboot.png" class="w-12 h-12 rounded-full">
    </button>

    {{-- CHAT --}}
    <div
        x-show="open"
        x-cloak
        @click.outside="open = false"
        class="absolute bottom-20 right-0 w-[380px] h-[520px]
               bg-white rounded-2xl shadow-2xl
               flex flex-col overflow-hidden"
    >
        {{-- HEADER --}}
        <div class="flex items-center gap-3 px-4 py-2.5 bg-primary text-white shrink-0">
            <img src="/images/chatboot.png" class="w-10 h-10 rounded-full">
            <div class="flex-1">
                <div class="font-semibold">Assistente Virtual</div>
                <div class="text-xs opacity-80">Online • responde em segundos</div>
            </div>
            <button @click="open = false">✕</button>
        </div>

        {{-- MENSAGENS --}}
        <div
            class="flex-1 px-4 py-3 space-y-3 overflow-y-auto
                   bg-slate-50 chat-scroll text-sm"
            x-ref="messages"
        >
            <template x-for="m in messages" :key="m.id">
                <div class="flex" :class="m.from === 'user' ? 'justify-end' : 'justify-start'">

                    {{-- TEXTO --}}
                    <div
                        x-show="m.type === 'text'"
                        class="max-w-[80%] px-4 py-2 rounded-2xl leading-relaxed"
                        :class="m.from === 'user'
                            ? 'bg-primary text-white rounded-br-md'
                            : 'bg-white text-slate-700 shadow-sm rounded-bl-md'"
                        x-html="format(m.text)"
                    ></div>

                    {{-- CARD CONTATO --}}
                    <div>
                        <div
                            x-show="m.type === 'contact'"
                            class="bg-white shadow-md rounded-xl p-3 w-64"
                        >
                            <div class="flex items-center gap-3">
                                <img
                                    src="/images/avatar-02.webp"
                                    class="w-14 h-14 rounded-full"
                                >
                                <div>
                                    <div class="font-semibold text-sm" x-text="m.contact?.name"></div>
                                    <div class="text-xs text-slate-500" x-text="m.contact?.role"></div>
                                </div>
                            </div>

                            <a
                                x-show="m.contact?.phone"
                                :href="`https://wa.me/${m.contact?.phone}?text=${encodeURIComponent(m.contact?.message ?? '')}`"
                                target="_blank"
                                class="block mt-3 text-center py-2 rounded-lg
                                       bg-teal-500 text-white text-sm
                                       hover:bg-teal-600 transition"
                            >
                                Falar no WhatsApp
                            </a>
                        </div>
                    </div>
                </div>
            </template>

            {{-- FAQs --}}
            <div x-show="showFaqs" class="flex flex-wrap gap-2 mt-2">
                @foreach($this->faqs as $faq)
                    <button
                        @click="sendPreset('{{ addslashes($faq->question) }}')"
                        class="px-3 py-2 rounded-full text-xs
                               bg-white shadow-sm hover:bg-primary
                               hover:text-white transition cursor-pointer"
                    >
                        {{ $faq->question }}
                    </button>
                @endforeach

                <button
                    @click="sendPreset('Falar com o Corretor')"
                    class="px-3 py-2 rounded-full text-xs
                           bg-white shadow-sm hover:bg-primary
                           hover:text-white transition cursor-pointer"
                >
                    Falar com o Corretor
                </button>
            </div>

            <div x-show="loading" class="text-xs text-slate-400 animate-pulse">
                Assistente digitando…
            </div>
        </div>

        {{-- INPUT --}}
        <form @submit.prevent="send" class="px-3 py-2 bg-white flex gap-2 shrink-0">
            <input
                x-model="input"
                placeholder="Digite sua pergunta…"
                class="flex-1 px-4 py-2 rounded-full
                       bg-slate-100 focus:ring-2
                       focus:ring-primary/40 outline-none"
            >
            <button class="w-9 h-9 rounded-full bg-primary text-white">➤</button>
        </form>
    </div>
</div>
