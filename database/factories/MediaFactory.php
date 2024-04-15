<?php

namespace Database\Factories;

use App\Models\Media;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Media>
 */
class MediaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Media::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid(),
            'user_id' => null,
            'type' => 'image',
            'image_type' => 'marker',
            'name' => Str::random(32),
            'extension' => $this->faker->randomElement(['png', 'jpeg', 'jpg', 'webp']),
        ];
    }

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function (Media $media) {
            $file = $media->extension === 'png'
                ? storage_path('app/seeds/example-image1.png')
                : ($media->extension === 'jpeg'
                    ? $this->faker->randomElement([
                        storage_path('app/seeds/example-image2.jpeg'),
                        storage_path('app/seeds/example-image3.jpeg'),
                        storage_path('app/seeds/example-image4.jpeg'),
                    ])
                    : ($media->extension === 'jpg'
                        ? $this->faker->randomElement([
                            storage_path('app/seeds/example-image5.jpg'),
                            storage_path('app/seeds/example-image8.jpg'),
                        ])
                        : $this->faker->randomElement([
                            storage_path('app/seeds/example-image6.webp'),
                            storage_path('app/seeds/example-image7.webp'),
                        ])
                    )
                );

            $media->addFile($file, true);
        });
    }
}
