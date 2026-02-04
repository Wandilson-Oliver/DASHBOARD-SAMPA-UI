<?php

namespace App\Traits;

use App\Models\Product;
use Exception;

trait GeneratesProductCodes
{
    public function gerarCodigoUnico(
        int $tamanho = 4,
        int $tentativas = 50
    ): string {
        if ($tamanho < 2) {
            throw new Exception('O tamanho mínimo do código é 2.');
        }

        $letras  = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numeros = '0123456789';
        $pool    = $letras . $numeros;

        for ($i = 0; $i < $tentativas; $i++) {

            // força pelo menos 2 números
            $codigo = [
                $numeros[random_int(0, 9)],
                $numeros[random_int(0, 9)],
            ];

            // completa o restante
            for ($j = 2; $j < $tamanho; $j++) {
                $codigo[] = $pool[random_int(0, strlen($pool) - 1)];
            }

            shuffle($codigo);

            $cod = implode('', $codigo);

            if (! Product::where('cod', $cod)->exists()) {
                return $cod;
            }
        }

        throw new Exception(
            "Não foi possível gerar um código único após {$tentativas} tentativas."
        );
    }
}
