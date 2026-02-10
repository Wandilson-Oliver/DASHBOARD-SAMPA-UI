<?php

namespace App\Services;

use App\Models\ChatFaq;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Str;

class FaqChatService
{
    public function ask(string $question): array
    {
        // 🔔 INTENÇÃO HUMANA
        $humanKeywords = [
            'corretor',
            'atendente',
            'humano',
            'whatsapp',
            'telefone',
            'ligar',
            'falar com alguém',
            'falar com corretor',
        ];

        foreach ($humanKeywords as $keyword) {
            if (str_contains(Str::lower($question), $keyword)) {
                return [
                    'type' => 'contact',
                    'data' => [
                        'name' => 'João Silva',
                        'role' => 'Corretor Imobiliário',
                        'phone' => '5511999999999',
                        'photo' => '/images/corretor.jpg',
                        'message' => 'Olá João, vim pelo site e gostaria de falar com um corretor.'
                    ]
                ];
            }
        }

        // 🔎 BUSCA FAQ
        $keywords = collect(
            preg_split('/\s+/', Str::lower($question))
        )->filter(fn ($w) => strlen($w) >= 3);

        $faqs = ChatFaq::query()
            ->where(function ($q) use ($keywords) {
                foreach ($keywords as $word) {
                    $q->orWhere('question', 'like', "%{$word}%")
                      ->orWhere('answer', 'like', "%{$word}%");
                }
            })
            ->limit(5)
            ->get();

        if ($faqs->isEmpty()) {
            return [
                'type' => 'text',
                'data' => 'Não encontrei essa informação na base de conhecimento.'
            ];
        }

        $context = $faqs->map(fn ($faq) =>
            "Pergunta: {$faq->question}\nResposta: {$faq->answer}"
        )->implode("\n\n");

$prompt = <<<PROMPT
Você é um assistente de suporte humano, educado, claro e prestativo.

Utilize a BASE DE CONHECIMENTO abaixo como sua principal e prioritária fonte de informação.
Sua resposta deve se basear diretamente no conteúdo da base.

Você pode:
- explicar com suas próprias palavras
- reorganizar a resposta para ficar mais clara
- resumir ou detalhar pontos importantes
- usar exemplos simples APENAS quando eles estiverem logicamente implícitos na base
- esclarecer dúvidas diretamente relacionadas ao conteúdo apresentado
- usar suas fontes de informacao para enriquecer a resposta, mas SEM CONTRADIZER ou ADICIONAR informações que não estejam na base

Você NÃO pode:
- inventar dados, valores, regras, prazos ou processos
- adicionar informações externas que não estejam implícitas ou diretamente relacionadas à base
- assumir fatos que não estejam claramente sustentados pelo conteúdo fornecido

Quando a pergunta envolver cidades ou localização:
- você pode usar apenas informações gerais e neutras (ex: nome da cidade, localização geográfica básica),
- nunca utilize dados de mercado, preços, infraestrutura ou valorização que não estejam na base.

Se a BASE DE CONHECIMENTO não contiver informações suficientes para responder:
- diga de forma clara e educada que não possui essa informação no momento,
- se possível, sugira procurar um corretor ou atendimento humano.

BASE DE CONHECIMENTO:
{$context}

PERGUNTA DO USUÁRIO:
{$question}

Responda de forma clara, objetiva, didática e humana.
Se fizer sentido, convide o usuário a continuar a conversa ou esclarecer melhor a dúvida.
PROMPT;



        $response = OpenAI::chat()->create([
            'model' => 'gpt-4o-mini',
            'messages' => [
                ['role' => 'system', 'content' => 'Você é um atendente educado e objetivo.'],
                ['role' => 'user', 'content' => $prompt],
            ],
            'temperature' => 0.3,
        ]);

        return [
            'type' => 'text',
            'data' => trim($response->choices[0]->message->content)
        ];
    }
}
