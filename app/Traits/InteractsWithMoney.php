<?php

namespace App\Traits;

trait InteractsWithMoney
{
    /**
     * Converte qualquer valor monetário (BR ou numérico)
     * para decimal compatível com banco (ex: 2500000.00)
     *
     * Exemplos de entrada:
     *  - "2.500.000,00"
     *  - "2500000"
     *  - "250000000"
     *  - "R$ 2.500.000,00"
     *
     * @param  string|int|float|null $value
     * @return string|null
     */
    protected function brMoneyToDecimal(string|int|float|null $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        // garante string
        $value = (string) $value;

        // remove tudo que não for número
        $numbers = preg_replace('/\D/', '', $value);

        if ($numbers === '') {
            return null;
        }

        /**
         * Garante ao menos 2 casas decimais
         * Ex:
         *  "1"   => "001"
         *  "10"  => "010"
         */
        if (strlen($numbers) < 3) {
            $numbers = str_pad($numbers, 3, '0', STR_PAD_LEFT);
        }

        // separa centavos
        $integer = substr($numbers, 0, -2);
        $decimal = substr($numbers, -2);

        return $integer . '.' . $decimal;
    }

    /**
     * Formata decimal do banco para BRL (exibição)
     *
     * @param  string|int|float|null $value
     * @return string|null
     */
    protected function decimalToBrMoney(string|int|float|null $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        return number_format((float) $value, 2, ',', '.');
    }
}
