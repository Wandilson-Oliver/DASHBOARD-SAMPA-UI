<?php

// app/Services/ViaCepService.php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ViaCepService
{
    public static function lookup(string $zipcode): array
    {
        $zipcode = preg_replace('/\D/', '', $zipcode);

        $response = Http::timeout(5)
            ->get("https://viacep.com.br/ws/{$zipcode}/json/");

        if (! $response->successful() || $response->json('erro')) {
            throw new \Exception('CEP não encontrado');
        }

        $data = $response->json();

        return [
            'zipcode'      => $data['cep'],
            'street'       => $data['logradouro'] ?? null,
            'neighborhood' => $data['bairro'] ?? null,
            'city'         => $data['localidade'] ?? null,
            'state'        => $data['uf'] ?? null,
            'country'      => 'BR',
        ];
    }
}
