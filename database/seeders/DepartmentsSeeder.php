<?php

namespace Database\Seeders;

use File;
use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get("database/data/departments.json");
        $data = json_decode($json);

        foreach ($data as $obj) {
            Department::updateOrCreate(['id' => $obj->id],array(
                'id'            => $obj->id,
                'code'          => $obj->code,
                'name'          => $obj->name,
                'zone'          =>$obj->zone
            ));
        }
    }
}
