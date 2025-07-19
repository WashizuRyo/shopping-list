<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ItemShoppingList extends Pivot
{
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_checked' => 'boolean',
    ];
}
