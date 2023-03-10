<?php

namespace Database\Factories;

use App\Models\Plate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Plate>
 */
class PlateFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Plate::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid(),
            'number' => $this->faker->randomElement([
                $this->faker->regexify('[A-Z]{2}[0-9]{5}'),
                $this->faker->regexify('[A-Z]{3}[0-9]{4}'),
                $this->faker->regexify('[A-Z]{3}[0-9]{5}'),
            ]),
        ];
    }
}
