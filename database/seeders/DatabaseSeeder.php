<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            CountrySeeder::class,
            DepartmentsSeeder::class,
            MunicipalitiesSeeder::class,
            EconomicactivitiesSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            TypeDocumentSeeder::class,
            CatListSeeder::class,
            CatDetailsSeeder::class,
            AmbientesSeeder::class,
            ConfigSeeder::class


        ]);
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
