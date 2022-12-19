<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $classifications = ['Test1','Test2','Test3'];
        foreach($classifications as $classification){
            DB::table('classifications')->insert([
                "name"=>$classification
            ]);
        }
        
    }
}
