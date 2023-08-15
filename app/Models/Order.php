<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'customer_id',
        'order_status_id',
    ];

    public function orderItem(): HasMany {
        return $this->hasMany(OrderItem::class);
    }

    public function orderStatus(): BelongsTo {
        return $this->belongsTo(OrderStatus::class);
    }

    public function customer(): BelongsTo {
        return $this->belongsTo(Customer::class);
    }
}
