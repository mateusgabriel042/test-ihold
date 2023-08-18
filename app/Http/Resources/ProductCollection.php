<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return $this->collection->transform(function ($product) {
            return [
                'id' => $product->id,
                'product_status_id' => $product->product_status_id,
                'merchant_id' => $product->merchant_id,
                'name' => $product->name,
                'price' => $product->price,
                'merchant' => new MerchantResource($product->merchant),
            ];
        });
    }
}
