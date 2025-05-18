<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Food extends Model
{
    protected $table = 'foods';

    protected $fillable = [
        'name',
        'price',
        'unit',
    ];

    protected $casts = [
        'price' => 'float',
    ];

    public function priceHistories(): HasMany
    {
        return $this->hasMany(FoodPriceHistory::class);
    }
} 