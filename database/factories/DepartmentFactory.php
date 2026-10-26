<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DepartmentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'company_id' => null,
            'name' => fake()->unique()->jobTitle(),
            'is_active' => true,
        ];
    }
}
