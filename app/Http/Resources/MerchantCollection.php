<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MerchantCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray($request)
    {
        return $this->collection->transform(function ($merchant) {
            return [
                'id' => $merchant->id,
                'merchant_name' => $merchant->merchant_name,
                'user_id' => $merchant->user_id,
                'user' => new UserResource($merchant->user),
            ];
        });
    }
}
