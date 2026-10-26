<?php

namespace Database\Factories;

use App\Enums\EmployeeStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'company_id' => null,
            'branch_id' => null,
            'department_id' => null,
            'employee_number' => 'EMP-'.fake()->unique()->numberBetween(1000, 9999),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'nationality' => fake()->country(),
            'job_title' => fake()->jobTitle(),
            'status' => EmployeeStatus::ACTIVE,
        ];
    }
}
