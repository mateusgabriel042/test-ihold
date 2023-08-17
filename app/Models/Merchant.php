<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Merchant extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'merchants';

    protected $fillable = [
        'id',
        'merchant_name',
        'user_id',
    ];

    public function user(): BelongsTo{
        return $this->belongsTo(User::class);
    }
}
