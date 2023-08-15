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

    public function getProducts()
    {
        return Product::paginate(10);
    }


}
