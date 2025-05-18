<?php

namespace App\Observers;

use App\Models\Food;

class FoodObserver
{
    /**
     * Handle the Food "created" event.
     */
    public function created(Food $food): void
    {
        //
    }

    /**
     * Handle the Food "updated" event.
     */
    public function updated(Food $food): void
    {
        if ($food->isDirty(['price', 'unit'])) {
            $food->priceHistories()->create([
                'old_price' => $food->getOriginal('price'),
                'new_price' => $food->price,
                'unit' => $food->unit,
                'changed_at' => now(),
            ]);
        }
    }

    /**
     * Handle the Food "deleted" event.
     */
    public function deleted(Food $food): void
    {
        //
    }

    /**
     * Handle the Food "restored" event.
     */
    public function restored(Food $food): void
    {
        //
    }

    /**
     * Handle the Food "force deleted" event.
     */
    public function forceDeleted(Food $food): void
    {
        //
    }
}
