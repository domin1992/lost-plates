<?php

namespace Database\Factories;

use App\Models\MarkerMedia;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MarkerMedia>
 */
class MarkerMediaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MarkerMedia::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid(),
            'marker_id' => $this->faker->uuid(),
            'media_id' => $this->faker->uuid(),
        ];
    }
}
