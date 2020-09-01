<?php

use Illuminate\Database\Seeder;

class PhoneNumberPrefixSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('phone_number_prefixes')->insert([
            'country_id' => 1,
            'number' => '48',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
