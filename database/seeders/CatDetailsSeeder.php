<?php

namespace Database\Seeders;

use App\Models\Catdetail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use File;

class CatDetailsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get("database/data/catdetails.json");
        $data = json_decode($json);

        foreach ($data as $obj) {
            Catdetail::updateOrCreate(['id' => $obj->id],array(
                'id'            => $obj->id,
                'codecat'       => $obj->codecat,
                'code'          => $obj->code,
                'description'   => $obj->description,
                'catlist_id'    => $obj->catlist_id
                ,
            ));
        }
    }
}
