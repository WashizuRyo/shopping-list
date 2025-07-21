<?php

namespace Database\Seeders;

use App\Models\ShoppingList;
use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Seeder;

class ShoppingListSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('name', 'Test User')->first();

        $itemIds = Item::pluck('id');
        ShoppingList::factory()
            ->count(10)
            ->create(['user_id' => $user->id])
            ->each(function ($shoppingList) use ($itemIds) {
                $itemsToAttach = $itemIds->shuffle()->take(rand(2, 5));

                $pivotData = [];
                foreach ($itemsToAttach as $itemId) {
                    $pivotData[$itemId] = [
                        'quantity' => rand(1, 5),
                        'is_checked' => fake()->boolean(),
                    ];
                }

                $shoppingList->items()->attach($pivotData);
            });
    }
}
