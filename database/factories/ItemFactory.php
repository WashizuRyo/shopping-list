<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
  public function definition(): array
  {
    return [
      'user_id' => null,
      'name' => $this->faker->word,
    ];
  }
}