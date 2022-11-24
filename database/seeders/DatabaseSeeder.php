<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
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
        User::factory()
            ->admin()
            ->create();

        User::first()
            ->update([
                'nick' => 'admin',
                'first_name' => 'Administrador',
                'last_name' => 'Data Pulley',
            ]);
    }
}
