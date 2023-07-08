<?php

namespace Database\Factories;

use App\Models\Travel;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Database\Eloquent\Factories\Factory;

class TourFactory extends Factory
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
            'starting_date' => $this->faker->date('Y-m-d', now()),
            'ending_date' => $this->faker->date('Y-m-d', now()->addDay(rand(1,5))),
            'price' => $this->faker->randomFloat(2, 10, 999)
        ];
    }
}
