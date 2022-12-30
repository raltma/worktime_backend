<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'bs_id'=>"0",
            'fingerprint_id'=>"0",
            'username'=> "admin",
            'name'=>"admin",
            'email'=>"admin@admin.ee",
            'taavi_code'=>"0000",
            'bs_department_id'=>"3",
            'password'=> Hash::make("Nimda2102!"),
            'admin'=> true
        ]);
        DB::table('admin_departments')->insert([
            'user_id' => 1,
            'department_id' => 1
        ]);
        DB::table('admin_departments')->insert([
            'user_id' => 1,
            'department_id' => 2
        ]);

        DB::table('users')->insert([
            'bs_id'=>"1",
            'fingerprint_id'=>"1",
            'username'=> "mats",
            'name'=>"madis",
            'email'=>"admin@admin.ee",
            'taavi_code'=>"0000",
            'bs_department_id'=>"2",
            'password'=> Hash::make("mats"),
            'admin'=> false
        ]);
    }
}
