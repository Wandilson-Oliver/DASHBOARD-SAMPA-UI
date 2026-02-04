<?php

namespace App\Actions;

use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class UpdateProductAction
{
    public static function execute(Product $product, array $data, int $userId): Product
    {
        return DB::transaction(function () use ($product, $data, $userId) {

            $addressId = $product->address_id;

            if (!empty($data['address']['zipcode'])) {
                $address = AddressLookupAndStoreAction::execute(
                    $data['address']['zipcode']
                );
                $addressId = $address->id;
            }

            unset($data['address']);

            $product->update(array_merge(
                $data,
                [
                    'address_id' => $addressId,
                    'user_id' => $userId,
                ]
            ));

            // garante slug consistente
            $product->update([
                'slug' => Str::slug("{$product->title} {$product->cod}"),
            ]);

            return $product;
        });
    }
}
