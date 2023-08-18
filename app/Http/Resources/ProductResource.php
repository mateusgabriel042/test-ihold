<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'product_status_id' => $this->product_status_id,
            'merchant_id' => $this->merchant_id,
            'name' => $this->name,
            'price' => $this->price,
            'merchant' => new MerchantResource($this->merchant),
        ];
    }
}
