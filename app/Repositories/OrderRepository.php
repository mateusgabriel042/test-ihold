<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;
use App\Models\Order;

class OrderRepository extends BaseRepository
{
    public function __construct(Order $order)
    {
        parent::__construct($order);
    }
}
