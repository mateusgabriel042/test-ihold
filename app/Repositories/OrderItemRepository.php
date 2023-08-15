<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;
use App\Models\OrderItem;
use App\Models\Product;

class OrderItemRepository extends BaseRepository
{
    public function __construct(OrderItem $orderItem)
    {
        parent::__construct($orderItem);
    }

    public function saveMultipleItems($order, $items) {

       foreach($items as $item) {
            $product = Product::find($item['product_id']);

            $orderItem = [
                "order_id" => $order->id,
                "product_id" => $product->id,
                "sales_price" => $product->price,
                "quantity" => $item['quantity']
            ];

           OrderItem::create($orderItem);
       }

    }
}
