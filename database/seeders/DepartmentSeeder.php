<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('departments')->insert([
            "bs_id"=> "2",
            "name"=>"Läinud inimesed",
            "Department"=>"Läinud inimesed"
        ]);
        DB::table('departments')->insert([
            "bs_id"=> "3",
            "name"=>"Kontor",
            "Department"=>"Kontor"
        ]);
    }
}
