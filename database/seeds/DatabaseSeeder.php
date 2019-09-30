<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $this->call('DevicetypesTableSeeder');
        $this->call('HscontactsTableSeeder');
        $this->call('HighsiteVisitCategoriesTableSeeder');
        $this->call('BackhaultypesTableSeeder');
        $this->call('SystemsTableSeeder');
    }
}
