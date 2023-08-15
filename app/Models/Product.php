<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'products';

    protected $fillable = [
        'product_status_id',
        'merchant_id',
        "name",
        "price"

    ];

    public function productStatus(): BelongsTo {
        return $this->belongsTo(ProductStatus::class);
    }

    public function merchant(): BelongsTo{
        return $this->belongsTo(Merchant::class);
    }
}
