<?php

namespace Database\Factories;

use App\Models\Marker;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Marker>
 */
class MarkerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Marker::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid(),
            'plate_id' => $this->faker->uuid(),
            'type' => $this->faker->randomElement([Marker::TYPE_FOUND, Marker::TYPE_LOST]),
            'lat' => $this->faker->randomFloat(8, 48, 55),
            'lng' => $this->faker->randomFloat(8, 10, 27),
            'radius' => $this->faker->numberBetween(1, 100),
            'phone_number' => $this->faker->regexify('[0-9]{9}'),
            'email' => $this->faker->email(),
            'additional_info' => $this->faker->text(),
            'notify_when_found' => $this->faker->boolean(),
        ];
    }
}
