<?php

namespace Database\Factories;

use App\Models\ModelHasRole;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Subscription;

class SubscriptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => $this->faker->numberBetween(1, 30),
            'plan' => $this->faker->randomElement(['basic', 'premium']),
            'status' => $this->faker->numberBetween(0, 1),
            'ends_at' => $this->faker->dateTimeBetween('-1 day', 'now'),
        ];

    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
