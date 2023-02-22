<?php

namespace Database\Seeders;

use File;
use App\Models\Economicactivity;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EconomicactivitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get("database/data/economicactivities.json");
        $data = json_decode($json);

        foreach ($data as $obj) {
            Economicactivity::updateOrCreate(['id' => $obj->id],array(
                'id'            => $obj->id,
                'code'          => $obj->code,
                'name'          => $obj->name
            ));
        }
    }
}
