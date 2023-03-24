<?php

namespace Database\Seeders;

use App\Models\Typedocument;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use File;

class TypeDocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get("database/data/typedocuments.json");
        $data = json_decode($json);

        foreach ($data as $obj) {
            Typedocument::updateOrCreate(['id' => $obj->id],array(
                'id'                                    => $obj->id,
                'company_id'                            => $obj->company_id,
                'type'                                  => $obj->type,
                'description'                           => $obj->description,
                'codemh'                                => $obj->codemh,
                'versionjson'                           => $obj->versionjson,
                'versionjsoncontingencia'               => $obj->versionjsoncontingencia,
                'contingencia'                          => $obj->contingencia,
                'ambiente'                              => $obj->ambiente,
                'invalidation'                          => $obj->invalidation,
                'periodinvalidation'                    => $obj->periodinvalidation,
                'versionjsoncontingenciainvalidation'   => $obj->versionjsoncontingenciainvalidation
                ,
            ));
        }
    }
}
