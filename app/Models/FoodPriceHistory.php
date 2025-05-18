<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FoodPriceHistory extends Model
{
    protected $table = 'food_price_histories';

    protected $fillable = [
        'food_id',
        'old_price',
        'new_price',
        'unit',
        'changed_at',
    ];

    protected $casts = [
        'old_price' => 'float',
        'new_price' => 'float',
        'changed_at' => 'datetime',
    ];

    public function food(): BelongsTo
    {
        return $this->belongsTo(Food::class);
    }
} 