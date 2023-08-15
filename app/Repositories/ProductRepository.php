<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;
use App\Models\Product;


class ProductRepository extends BaseRepository
{
    public function __construct(Product $product)
    {
        parent::__construct($product);
    }


}
