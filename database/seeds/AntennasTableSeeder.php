<?php

use Illuminate\Database\Seeder;

class AntennasTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('antennas')->delete();
        
        \DB::table('antennas')->insert(array (
            0 => 
            array (
                'id' => 1,
                'description' => 'UBNT 16dbi',
                'gain' => '16',
                'vertical' => '5',
                'horizontal' => '5',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'description' => 'UBNT 17dbi',
                'gain' => '17',
                'vertical' => '5',
                'horizontal' => '5',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'description' => 'UBNT 19dbi',
                'gain' => '19',
                'vertical' => '',
                'horizontal' => '',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'description' => 'UBNT 20dbi',
                'gain' => '20',
                'vertical' => '',
                'horizontal' => '',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'description' => 'UBNT 21dbi',
                'gain' => '21',
                'vertical' => '',
                'horizontal' => '',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'description' => 'UBNT 22dbi',
                'gain' => '22',
                'vertical' => '',
                'horizontal' => '',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'description' => 'Cambium 16Dbi',
                'gain' => '16',
                'vertical' => '',
                'horizontal' => '',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'description' => 'Cambium 17Dbi',
                'gain' => '17',
                'vertical' => '',
                'horizontal' => '',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));
        
        
    }
}
