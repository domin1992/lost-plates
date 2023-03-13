<?php

namespace Database\Factories;

use App\Models\MarkerComment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MarkerComment>
 */
class MarkerCommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MarkerComment::class;

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
            'name' => $this->faker->name(),
            'content' => $this->faker->text(),
        ];
    }
}
