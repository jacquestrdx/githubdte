<?php

use Illuminate\Database\Seeder;

class HscontactsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('hscontacts')->delete();
        
        \DB::table('hscontacts')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'default',
                'email' => 'default@domain.com',
                'cellnum' => 'NO NUMBER',
                'cellnum2' => 'NO NUMBER',
                'created_at' => '2016-04-12 09:30:35',
                'updated_at' => '2018-11-15 13:15:08',
                'surname' => '',
                'address' => '',
            )
        ));
        
        
    }
}
