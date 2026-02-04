<?php

// app/Actions/AddressLookupAndStoreAction.php

namespace App\Actions;

use App\Models\Address;
use App\Services\ViaCepService;

class AddressLookupAndStoreAction
{
    public static function execute(string $zipcode): Address
    {
        $zipcode = preg_replace('/\D/', '', $zipcode);
        // 1️⃣ Reutiliza se já existir
        $address = Address::where('zipcode', $zipcode)->first();
        if ($address) {
            return $address;
        }

        // 2️⃣ Consulta no Correios
        $data = ViaCepService::lookup($zipcode);

        // 3️⃣ Salva no banco
        return Address::create($data);
    }
}
