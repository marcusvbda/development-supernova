<?php

namespace Database\Seeders;

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StartUpSeeder extends Seeder
{
    public function run()
    {
        DB::beginTransaction();
        $this->createUsers();
        DB::commit();
    }

    protected function createUsers()
    {
        User::insert([
            "name" => "Root",
            "email" => "root",
            "role" =>  "root",
            "password" => bcrypt("roottoor")
        ]);
    }
}