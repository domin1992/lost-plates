<?php

use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $countryId = DB::table('countries')->insertGetId([
            'currency_id' => 1,
            'iso_code' => 'PL',
            'call_prefix' => 48,
            'active' => true,
            'zip_code_required' => true,
            'zip_code_format' => 'NN-NNN',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        DB::table('country_langs')->insert([
            'country_id' => $countryId,
            'lang_id' => 1,
            'name' => 'Polska',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
