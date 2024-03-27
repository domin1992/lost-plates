<?php

namespace Database\Seeders;

use App\Models\Marker;
use App\Models\MarkerComment;
use App\Models\MarkerMedia;
use App\Models\Media;
use App\Models\Plate;
use Illuminate\Database\Seeder;

class MarkerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Plate::factory()
            ->count(10)
            ->create()
            ->each(function ($plate) {
                $marker = Marker::factory()
                    ->state(function (array $attributes) use ($plate) {
                        return [
                            'plate_id' => $plate->id,
                        ];
                    })
                    ->create();

                MarkerMedia::factory()
                    ->state(function (array $attribute) use ($marker) {
                        return [
                            'marker_id' => $marker->id,
                        ];
                    })
                    ->count(rand(0, 5))
                    ->create()
                    ->each(function ($markerMedia) use ($marker) {
                        $media = Media::factory()
                            ->create();

                        $markerMedia->update([
                            'media_id' => $media->id,
                        ]);
                    });

                MarkerComment::factory()
                    ->state(function (array $attribute) use ($marker) {
                        return [
                            'marker_id' => $marker->id,
                        ];
                    })
                    ->count(rand(0, 20))
                    ->create();
            });
    }
}
