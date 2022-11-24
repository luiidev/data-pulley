<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'nick' => $this->faker->userName(),
            'password' => 'password',
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'state' => 1,
            'role' => 3,
            'dni' => $this->faker->numerify('##########'),
            'address' => $this->faker->address(),
            'phone' => $this->faker->numerify('+519########'),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }

    public function super(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'role' => 1,
            ];
        });
    }

    public function admin(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'role' => 2,
            ];
        });
    }
}
