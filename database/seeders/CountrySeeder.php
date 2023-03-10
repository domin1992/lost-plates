<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countryId = DB::table('countries')->insertGetId([
            'id' => Uuid::uuid4(),
            'iso_code' => 'PL',
            'call_prefix' => 48,
            'active' => true,
            'zip_code_required' => true,
            'zip_code_format' => 'NN-NNN',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        DB::table('country_langs')->insert([
            'id' => Uuid::uuid4(),
            'country_id' => $countryId,
            'lang_id' => 1,
            'name' => 'Polska',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
