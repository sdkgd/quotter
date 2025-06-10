<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Quser>
 */
class QuserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_name' => $this->faker->regexify('[a-zA-Z0-9]{10}'),
            'display_name'=> $this->faker->firstName,
            'email' => $this->faker->safeEmail,
            'password' => Hash::make('password'),
            'created_at'=>now(),
            'updated_at'=>now(),
        ];
    }
}
