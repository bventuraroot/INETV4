<?php

namespace Database\Seeders;

use App\Models\Catlist;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use File;

class CatListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get("database/data/catlists.json");
        $data = json_decode($json);

        foreach ($data as $obj) {
            Catlist::updateOrCreate(['id' => $obj->id],array(
                'id'            => $obj->id,
                'code'          => $obj->code,
                'namefield'     => $obj->namefield,
                'description'   => $obj->description
                ,
            ));
        }
    }
}
