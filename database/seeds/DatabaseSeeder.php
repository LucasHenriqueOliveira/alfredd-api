<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {

    public function run() {
        Model::unguard();
        $this->call(ConvertData::class);
        Model::reguard();
    }
}