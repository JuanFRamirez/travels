<?php

namespace Database\Factories;

use App\Models\Travel;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Database\Eloquent\Factories\Factory;

class TravelFactory extends Factory
{
    use WithFaker;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->text(20),
            'is_public' => $this->faker->boolean(),
            'description' => $this->faker->text(100),
            'number_of_days' => rand(1,10)
        ];
    }
}
