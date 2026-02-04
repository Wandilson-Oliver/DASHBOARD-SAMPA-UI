<?php

namespace App\Actions;

use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CreateProductAction
{
    public static function execute(array $data, int $userId): Product
    {
        return DB::transaction(function () use ($data, $userId) {

            $addressId = null;

            // ✅ CEP É OPCIONAL
            if (
                !empty($data['address']) &&
                !empty($data['address']['zipcode'])
            ) {
                $address = AddressLookupAndStoreAction::execute(
                    (string) $data['address']['zipcode']
                );

                $addressId = $address->id;
            }

            // remove address do payload (não pertence ao Product)
            unset($data['address']);

            // cria produto
            $product = Product::create(array_merge(
                $data,
                [
                    'address_id' => $addressId,
                    'user_id' => $userId,
                ]
            ));

            // gera slug após create (id + cod já existem)
            $slug = Str::slug(
                "{$product->title} {$product->cod}"
            );

            $product->update([
                'slug' => $slug,
            ]);

            return $product;
        });
    }
}
