<?php

use Illuminate\Database\Seeder;

class BackhaultypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('backhaultypes')->delete();
        
        \DB::table('backhaultypes')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => '3.5GHz Powerbeam',
                'color' => 'Blue',
                'created_at' => '2018-02-27 09:31:11',
                'updated_at' => '2016-06-13 14:20:43',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Wireless 17 Ghz  Siae',
                'color' => 'Blue',
                'created_at' => '2019-08-01 09:41:52',
                'updated_at' => '2019-08-01 09:41:52',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Wireless Ligowave',
                'color' => 'Green',
                'created_at' => '2019-08-06 12:54:44',
                'updated_at' => '2019-08-06 12:54:44',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Wireless AC Powerbeam',
                'color' => 'Green',
                'created_at' => '2019-08-06 12:54:48',
                'updated_at' => '2019-08-06 12:54:48',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Wireless Rocket M5',
                'color' => 'Green',
                'created_at' => '2019-08-06 12:54:52',
                'updated_at' => '2019-08-06 12:54:52',
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'Wireless Cambium',
                'color' => 'Green',
                'created_at' => '2019-08-06 12:54:55',
                'updated_at' => '2019-08-06 12:54:55',
            ),
            6 => 
            array (
                'id' => 7,
                'name' => '',
                'color' => 'Yellow',
                'created_at' => '2019-08-06 12:48:09',
                'updated_at' => '2019-08-06 12:48:09',
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'Wireless Powerbeam',
                'color' => 'Green',
                'created_at' => '2019-08-06 12:54:58',
                'updated_at' => '2019-08-06 12:54:58',
            ),
            8 => 
            array (
                'id' => 9,
                'name' => 'Wireless Youncta 17 Ghz ',
                'color' => 'Blue',
                'created_at' => '2019-08-06 10:32:08',
                'updated_at' => '2019-08-06 10:32:08',
            ),
            9 => 
            array (
                'id' => 10,
                'name' => '',
                'color' => 'Blue',
                'created_at' => '2019-08-01 09:37:54',
                'updated_at' => '2019-08-01 09:37:54',
            ),
            10 => 
            array (
                'id' => 11,
                'name' => 'Wireless Siklu',
                'color' => 'Teal',
                'created_at' => '2019-08-01 09:38:02',
                'updated_at' => '2019-08-01 09:38:02',
            ),
            11 => 
            array (
                'id' => 12,
                'name' => 'Wireless 11GHz',
                'color' => 'Orange',
                'created_at' => '2019-08-01 09:38:23',
                'updated_at' => '2019-08-01 09:38:23',
            ),
            12 => 
            array (
                'id' => 13,
                'name' => 'Wireless AC Prism',
                'color' => 'Green',
                'created_at' => '2019-08-06 12:55:01',
                'updated_at' => '2019-08-06 12:55:01',
            ),
            13 => 
            array (
                'id' => 14,
                'name' => 'Wireless AirFibre',
                'color' => 'Green',
                'created_at' => '2019-08-06 12:55:04',
                'updated_at' => '2019-08-06 12:55:04',
            ),
            14 => 
            array (
                'id' => 15,
                'name' => '',
                'color' => 'Blue',
                'created_at' => '2019-08-01 09:38:45',
                'updated_at' => '2019-08-01 09:38:45',
            ),
            15 => 
            array (
                'id' => 16,
                'name' => 'Wireless Ignite',
                'color' => 'Brown',
                'created_at' => '2019-08-01 09:39:00',
                'updated_at' => '2019-08-01 09:39:00',
            ),
            16 => 
            array (
                'id' => 17,
                'name' => 'Wireless Mimosa',
                'color' => 'Green',
                'created_at' => '2019-08-06 12:55:06',
                'updated_at' => '2019-08-06 12:55:06',
            ),
            17 => 
            array (
                'id' => 18,
                'name' => 'Wireless 18 Ghz ',
                'color' => 'Purple',
                'created_at' => '2019-08-01 09:40:49',
                'updated_at' => '2019-08-01 09:40:49',
            ),
            18 => 
            array (
                'id' => 19,
                'name' => 'Fibre',
                'color' => 'Black',
                'created_at' => '2018-01-26 17:33:52',
                'updated_at' => '2018-01-26 17:33:39',
            ),
            19 => 
            array (
                'id' => 20,
                'name' => 'Wireless 17 Ghz Alcoma',
                'color' => 'Blue',
                'created_at' => '2019-08-01 09:42:00',
                'updated_at' => '2019-08-01 09:42:00',
            ),
            20 => 
            array (
                'id' => 21,
                'name' => 'BDcom Switch',
                'color' => 'Pink',
                'created_at' => '2019-08-01 09:39:30',
                'updated_at' => '2019-08-01 09:39:30',
            ),
        ));
        
        
    }
}
