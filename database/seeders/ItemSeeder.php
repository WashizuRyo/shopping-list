<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('name', 'Test User')->first();
        $items = [
            ['name' => 'りんご'],
            ['name' => '牛乳'],
            ['name' => '卵'],
            ['name' => 'パン'],
            ['name' => 'トマト'],
            ['name' => 'じゃがいも'],
            ['name' => '玉ねぎ'],
            ['name' => 'にんじん'],
            ['name' => 'お米'],
            ['name' => 'お茶'],
        ];
        foreach ($items as $item) {
            Item::create([
                'user_id' => $user->id,
                'name' => $item['name'],
            ]);
        }
    }
}
