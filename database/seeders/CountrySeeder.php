<?php

namespace Database\Seeders;

use App\Models\Country;
use File;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        $json = File::get("database/data/countries.json");
        $data = json_decode($json);

        foreach ($data as $obj) {
            Country::updateOrCreate(['id' => $obj->id],array(
                'id'            => $obj->id,
                'code'          => $obj->code,
                'name'          => $obj->name
            ));
        }
    }
}
