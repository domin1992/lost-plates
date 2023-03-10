<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('languages')->insert([
            'id' => Uuid::uuid4(),
            'name' => 'Polski',
            'iso' => 'pl',
            'active' => true,
            'date_format' => 'Y-m-d',
            'datetime_format' => 'Y-m-d H:i:s',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
