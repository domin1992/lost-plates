<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // $this->call(UserSeeder::class);
        $this->call(LanguageSeeder::class);
        $this->call(CountrySeeder::class);

        if (app()->isLocal()) {
            $this->removeMedia(storage_path('app/public/image/marker'));
            $this->call(MarkerSeeder::class);
        }
    }

    public function removeMedia($dir)
    {
        foreach (scandir($dir) as $file) {
            if (!in_array($file, ['.', '..'])) {
                unlink($dir . '/' . $file);
            }
        }
    }
}
