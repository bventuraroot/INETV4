<?php

namespace Database\Seeders;

use App\Models\Ambiente;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AmbientesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Ambiente::create([
            'cod'=>'00',
            'description'=>'Modo Test',
            'url_credencial'=>'https://apitest.dtes.mh.gob.sv/seguridad/auth',
            'url_envio'=>'https://apitest.dtes.mh.gob.sv/fesv/recepciondte',
            'url_invalidacion'=>'https://apitest.dtes.mh.gob.sv/fesv/anulardte',
            'url_contingencia'=>'https://apitest.dtes.mh.gob.sv/fesv/contingencia',
            'url_firmador'=>'http://localhost:8113/firmardocumento/'

        ],[
            'cod'=>'01',
            'description'=>'Modo produccion',
            'url_credencial'=>'https://api.dtes.mh.gob.sv/seguridad/auth',
            'url_envio'=>'https://api.dtes.mh.gob.sv/fesv/recepciondte',
            'url_invalidacion'=>'https://api.dtes.mh.gob.sv/fesv/anulardte',
            'url_contingencia'=>'https://api.dtes.mh.gob.sv/fesv/contingencia',
            'url_firmador'=>'http://localhost:8113/firmardocumento/'
        ]);
    }
}
