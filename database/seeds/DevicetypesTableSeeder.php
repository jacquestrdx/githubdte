<?php

use Illuminate\Database\Seeder;

class DevicetypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('devicetypes')->delete();
        
        \DB::table('devicetypes')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Mikrotik Router',
                'sub_type' => 'router',
                'created_at' => '2017-01-09 09:08:04',
                'updated_at' => '2017-01-09 09:08:04',
                'volts' => 0,
                'amps' => 0,
                'watts' => 0,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'UBNT Airmax Sector',
                'sub_type' => 'wireless',
                'created_at' => '2018-02-14 10:00:57',
                'updated_at' => '2018-02-14 10:00:57',
                'volts' => 0,
                'amps' => 0,
                'watts' => 0,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Ubiquiti Toughswitch',
                'sub_type' => 'switch',
                'created_at' => '2017-01-09 09:08:10',
                'updated_at' => '2017-01-09 09:08:10',
                'volts' => 0,
                'amps' => 0,
                'watts' => 0,
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Power Monitor',
                'sub_type' => 'pm',
                'created_at' => '2017-01-09 09:08:16',
                'updated_at' => '2017-01-09 09:08:16',
                'volts' => 0,
                'amps' => 0,
                'watts' => 0,
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'SIAE',
                'sub_type' => 'wireless',
                'created_at' => '2017-01-09 09:07:55',
                'updated_at' => '2017-01-09 09:07:55',
                'volts' => 0,
                'amps' => 0,
                'watts' => 0,
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'Cisco ASR/Router',
                'sub_type' => 'router',
                'created_at' => '2019-08-16 12:33:24',
                'updated_at' => '2019-08-16 12:33:24',
                'volts' => 0,
                'amps' => 0,
                'watts' => 0,
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'Cisco Switch',
                'sub_type' => 'router',
                'created_at' => '2019-07-16 13:08:08',
                'updated_at' => '2019-07-16 13:08:08',
                'volts' => 0,
                'amps' => 0,
                'watts' => 0,
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'Ligowave',
                'sub_type' => 'wireless',
                'created_at' => '2017-01-09 09:07:55',
                'updated_at' => '2017-01-09 09:07:55',
                'volts' => 0,
                'amps' => 0,
                'watts' => 0,
            ),
            8 => 
            array (
                'id' => 9,
                'name' => 'Mimosa',
                'sub_type' => 'wireless',
                'created_at' => '2017-01-09 09:07:55',
                'updated_at' => '2017-01-09 09:07:55',
                'volts' => 0,
                'amps' => 0,
                'watts' => 0,
            ),
            9 => 
            array (
                'id' => 10,
                'name' => 'Ubiquiti Station',
                'sub_type' => 'wireless',
                'created_at' => '2017-01-09 09:07:55',
                'updated_at' => '2017-01-09 09:07:55',
                'volts' => 0,
                'amps' => 0,
                'watts' => 0,
            ),
            10 => 
            array (
                'id' => 11,
                'name' => 'Ubiquiti Access Point',
                'sub_type' => 'wireless',
                'created_at' => '2017-01-09 09:07:55',
                'updated_at' => '2017-01-09 09:07:55',
                'volts' => 0,
                'amps' => 0,
                'watts' => 0,
            ),
            11 => 
            array (
                'id' => 12,
                'name' => 'Radwin',
                'sub_type' => 'wireless',
                'created_at' => '2017-01-09 09:07:55',
                'updated_at' => '2017-01-09 09:07:55',
                'volts' => 0,
                'amps' => 0,
                'watts' => 0,
            ),
            12 => 
            array (
                'id' => 13,
                'name' => 'Wavion',
                'sub_type' => 'wireless',
                'created_at' => '2017-01-09 09:07:55',
                'updated_at' => '2017-01-09 09:07:55',
                'volts' => 0,
                'amps' => 0,
                'watts' => 0,
            ),
            13 => 
            array (
                'id' => 14,
                'name' => 'Airfibre',
                'sub_type' => 'wireless',
                'created_at' => '2017-01-09 09:07:55',
                'updated_at' => '2017-01-09 09:07:55',
                'volts' => 0,
                'amps' => 0,
                'watts' => 0,
            ),
            14 => 
            array (
                'id' => 15,
                'name' => 'Mikrotik Wireless',
                'sub_type' => 'wireless',
                'created_at' => '2017-01-09 09:07:55',
                'updated_at' => '2017-01-09 09:07:55',
                'volts' => 0,
                'amps' => 0,
                'watts' => 0,
            ),
            15 => 
            array (
                'id' => 16,
                'name' => 'Camera',
                'sub_type' => 'camera',
                'created_at' => '2017-01-09 09:08:28',
                'updated_at' => '2017-01-09 09:08:28',
                'volts' => 0,
                'amps' => 0,
                'watts' => 0,
            ),
            16 => 
            array (
                'id' => 17,
                'name' => 'Cambium',
                'sub_type' => 'wireless',
                'created_at' => '2017-01-09 09:07:55',
                'updated_at' => '2017-01-09 09:07:55',
                'volts' => 0,
                'amps' => 0,
                'watts' => 0,
            ),
            17 => 
            array (
                'id' => 18,
                'name' => 'Hotspot Router',
                'sub_type' => 'router',
                'created_at' => '2019-07-16 12:53:48',
                'updated_at' => '2019-07-16 12:53:48',
                'volts' => 0,
                'amps' => 0,
                'watts' => 0,
            ),
            18 => 
            array (
                'id' => 19,
                'name' => 'Ligowave RapidFire',
                'sub_type' => 'wireless',
                'created_at' => '2017-01-18 09:07:23',
                'updated_at' => '2017-01-18 09:07:23',
                'volts' => 0,
                'amps' => 0,
                'watts' => 0,
            ),
            19 => 
            array (
                'id' => 20,
                'name' => 'SMTP SERVER',
                'sub_type' => 'server',
                'created_at' => '2017-02-13 11:24:29',
                'updated_at' => '2017-02-13 11:24:29',
                'volts' => 0,
                'amps' => 0,
                'watts' => 0,
            ),
            20 => 
            array (
                'id' => 21,
                'name' => 'Fibre Switch',
                'sub_type' => 'switch',
                'created_at' => '2017-02-16 12:20:04',
                'updated_at' => '2017-02-16 12:20:04',
                'volts' => 0,
                'amps' => 0,
                'watts' => 0,
            ),
            21 => 
            array (
                'id' => 22,
                'name' => 'UBNT AC Prism Sector',
                'sub_type' => '',
                'created_at' => '2017-03-08 14:41:32',
                'updated_at' => '2017-03-08 14:41:32',
                'volts' => 0,
                'amps' => 0,
                'watts' => 0,
            ),
            22 => 
            array (
                'id' => 23,
                'name' => 'Ruckus',
                'sub_type' => '',
                'created_at' => '2017-07-20 16:35:19',
                'updated_at' => '2017-07-20 16:35:19',
                'volts' => 0,
                'amps' => 0,
                'watts' => 0,
            ),
            23 => 
            array (
                'id' => 24,
                'name' => 'Fiz Nano Station',
                'sub_type' => '',
                'created_at' => '2017-07-20 16:35:19',
                'updated_at' => '2017-07-20 16:35:19',
                'volts' => 0,
                'amps' => 0,
                'watts' => 0,
            ),
            24 => 
            array (
                'id' => 25,
                'name' => 'Other',
                'sub_type' => 'switch',
                'created_at' => '2017-08-29 15:19:50',
                'updated_at' => '2017-08-29 15:19:50',
                'volts' => 0,
                'amps' => 0,
                'watts' => 0,
            ),
            25 => 
            array (
                'id' => 26,
                'name' => 'Client Mikrotik',
                'sub_type' => 'router',
                'created_at' => '2018-01-15 07:54:03',
                'updated_at' => '2018-01-15 07:54:03',
                'volts' => 0,
                'amps' => 0,
                'watts' => 0,
            ),
            26 => 
            array (
                'id' => 27,
                'name' => 'Client CPE',
                'sub_type' => 'router',
                'created_at' => '2018-01-15 07:54:23',
                'updated_at' => '2018-01-15 07:54:23',
                'volts' => 0,
                'amps' => 0,
                'watts' => 0,
            ),
            27 => 
            array (
                'id' => 28,
                'name' => 'Aviat',
                'sub_type' => 'Wireless',
                'created_at' => '2019-07-16 12:50:58',
                'updated_at' => '2019-07-16 12:50:58',
                'volts' => 0,
                'amps' => 0,
                'watts' => 0,
            ),
            28 => 
            array (
                'id' => 29,
                'name' => 'Intracom Wireless Base Station',
                'sub_type' => '',
                'created_at' => '2019-07-19 14:03:35',
                'updated_at' => '2019-07-19 14:03:35',
                'volts' => 0,
                'amps' => 0,
                'watts' => 0,
            ),
            29 => 
            array (
                'id' => 30,
                'name' => 'Intracom Wireless Cpe',
                'sub_type' => '',
                'created_at' => '2019-07-19 14:03:43',
                'updated_at' => '2019-07-19 14:03:43',
                'volts' => 0,
                'amps' => 0,
                'watts' => 0,
            ),
            30 => 
            array (
                'id' => 31,
                'name' => 'Delta Power System Controller',
                'sub_type' => '',
                'created_at' => '2019-07-16 12:53:27',
                'updated_at' => '2019-07-16 12:53:27',
                'volts' => 0,
                'amps' => 0,
                'watts' => 0,
            ),
            31 => 
            array (
                'id' => 32,
                'name' => 'Intracom PTP AP',
                'sub_type' => '',
                'created_at' => '2019-08-16 08:49:23',
                'updated_at' => '2019-08-16 08:49:23',
                'volts' => 0,
                'amps' => 0,
                'watts' => 0,
            ),
            32 => 
            array (
                'id' => 33,
                'name' => 'Intracom PTP Station',
                'sub_type' => '',
                'created_at' => '2019-08-16 08:49:43',
                'updated_at' => '2019-08-16 08:49:43',
                'volts' => 0,
                'amps' => 0,
                'watts' => 0,
            ),
            33 => 
            array (
                'id' => 34,
                'name' => 'Super Micro Power Controller',
                'sub_type' => '',
                'created_at' => '2019-09-13 09:38:20',
                'updated_at' => '2019-09-13 09:38:20',
                'volts' => 0,
                'amps' => 0,
                'watts' => 0,
            ),
        ));
        
        
    }
}
