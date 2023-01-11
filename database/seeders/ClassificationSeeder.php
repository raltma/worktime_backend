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
        $classifications = [
            '066TITÕMBL',
            '066Tittek õmb 2xTit',
            '066TÕMB1,4-1,55',
            '066Teki õmb 2xS',
            '067TÕMB2,0-2,18',
            '067Teki õmb 2xD',
            '068TÕMB2,18-2,4',
            '068Teki õmb 2xK',
            '069Tõmbeluku Õmb',
            '069Tõmbluku Õmb2x'
        ];
        foreach($classifications as $classification){
            DB::table('classifications')->insert([
                "name"=>$classification
            ]);
        }
        
    }
}
