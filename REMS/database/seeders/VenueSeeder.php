<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VenueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $venues = [
            [
                'name' => 'Multipurpose Hall',
                'description' => 'Large, versatile hall suitable for various events',
                'capacity' => 300,
                'location' => 'Main Campus Building',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Theatre Room',
                'description' => 'Auditorium-style room with professional stage and seating',
                'capacity' => 200,
                'location' => 'Arts and Culture Center',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Auditorium',
                'description' => 'Large formal venue for major presentations and events',
                'capacity' => 500,
                'location' => 'Academic Complex',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Club Room',
                'description' => 'Intimate space for smaller club meetings and gatherings',
                'capacity' => 50,
                'location' => 'Student Center',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ];

        DB::table('venues')->insert($venues);
    }
}
