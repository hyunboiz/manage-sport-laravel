<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Customer;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Customer::class;

    public function definition()
{
    return [
        'name' => $this->faker->name(),
        'email' => $this->faker->unique()->safeEmail(),
        'hotline' => $this->faker->phoneNumber(),
        'username' => $this->faker->unique()->userName(),
        'password' => bcrypt('123456'), // hoặc Hash::make('123456')
        'created_at' => now()->subDays(rand(0, 30)),
    ];
}
}
