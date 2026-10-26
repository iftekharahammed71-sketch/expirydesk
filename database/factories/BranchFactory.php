<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BranchFactory extends Factory
{
    public function definition(): array
    {
        return [
            'company_id' => null,
            'name' => fake()->city().' Branch',
            'code' => fake()->unique()->lexify('???'),
            'address' => fake()->address(),
            'is_active' => true,
        ];
    }
}
