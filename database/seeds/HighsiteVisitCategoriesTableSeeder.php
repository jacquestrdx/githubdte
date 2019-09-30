<?php

use Illuminate\Database\Seeder;

class HighsiteVisitCategoriesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('highsite_visit_categories')->delete();
        
        \DB::table('highsite_visit_categories')->insert(array (
            0 => 
            array (
                'id' => 1,
                'description' => 'Breakdown',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'description' => '911',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'description' => 'Theft',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'description' => 'Maintanace',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));
        
        
    }
}
