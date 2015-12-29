<?php

use Illuminate\Database\Seeder;
use DB;

class OrganizationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $organizations = [
            'Paradise Island',
            'Banana tree',
            'Yellow Banana',
            'Brown Banana',
            'Green Banana',
            'Nestle',
        ];
        foreach($organizations as $organization) {
            DB::table('organizations')->insert([
                'name' =>$organization
            ]);
        }
    }
}
