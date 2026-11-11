<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the isolated Royal Collection SQLite database.
     */
    public function run(): void
    {
        $this->call([
            CatalogSeeder::class,
            BackendSeeder::class,
        ]);
    }
}
