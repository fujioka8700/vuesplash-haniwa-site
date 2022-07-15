<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PictureFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => Str::random(12),
            'user_id' => fn() => User::factory()->create()->id,
            'filename' => Str::random(12) . '.jpg',
            'created_at' => $this->faker->datetime(),
            'updated_at' => $this->faker->datetime()
        ];
    }
}
