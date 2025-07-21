<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShoppingListFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $names = [
            '買い物リスト',
            '食材リスト',
            '日用品リスト',
            '週末の買い物',
            '今日の買い物',
            '家族の買い物',
            'パーティー準備',
            'お弁当材料',
            'まとめ買い',
            '必需品リスト',
        ];
        return [
            'user_id' => null,
            'name' => $this->faker->randomElement($names),
        ];
    }
}
