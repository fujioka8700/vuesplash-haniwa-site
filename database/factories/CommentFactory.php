<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'content' => substr($this->faker->text(), 0, 500),
            'user_id' =>fn() => User::factory()->create()->id,
        ];
    }
}
