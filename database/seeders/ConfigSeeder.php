<?php

namespace Database\Seeders;

use App\Models\Config;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Config::create([
            'version'=>'3',
            'ambiente'=>'01',
            'typeModel'=>'1',
            'typeTransmission'=>'1',
            'typeContingencia'=>'0',
            'versionJson'=>'3',
            'passPrivateKey'=>'pass',
            'passkeyPublic'=>'pass',
            'passMH'=>'pass',
            'codeCountry'=>'9300',
            'nameCountry'=>'EL SALVADOR'

        ]);
    }
}
