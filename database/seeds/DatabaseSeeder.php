<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder {

    public function run() {
        Model::unguard();
        $path = __DIR__ . '/../initial_data.sql';
        DB::unprepared(file_get_contents($path));
        $this->command->info('Base data seeded!');
        $this->call(ConvertData::class);
        Model::reguard();
    }
}