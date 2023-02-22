<?php

namespace Database\Seeders;

use File;
use App\Models\Municipality;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MunicipalitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get("database/data/municipalities.json");
        $data = json_decode($json);

        foreach ($data as $obj) {
            Municipality::updateOrCreate(['id' => $obj->id],array(
                'id'            => $obj->id,
                'code'          => $obj->code,
                'name'          => $obj->name,
                'district'      => $obj->district,
                'department_id' => $obj->department_id,
                'title'         => $obj->title,
                'zipcode'       => $obj->zipcode
            ));
        }
    }
}
