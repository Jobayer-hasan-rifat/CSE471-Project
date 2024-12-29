<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Club;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ClubSeeder extends Seeder
{
    public function run()
    {
        // Create OCA user
        User::create([
            'name' => 'OCA Admin',
            'email' => 'oca@bracu.ac.bd',
            'password' => Hash::make('oca123'),
            'role' => 'oca'
        ]);

        // Create system admin
        User::create([
            'name' => 'System Admin',
            'email' => 'admin@bracu.ac.bd',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);

        $clubs = [
            [
                'name' => 'BRACU Computer Club',
                'email' => 'bucc@bracu.ac.bd',
                'description' => 'BRACU Computer Club - Promoting technology and computing',
                'president_name' => 'BUCC President',
                'president_contact' => '01700000001',
                'user_name' => 'BUCC Admin',
                'password' => 'bucc123',
                'role' => 'bucc'
            ],
            [
                'name' => 'BRAC University Robotics Club',
                'email' => 'robu@bracu.ac.bd',
                'description' => 'BRAC University Robotics Club - Advancing robotics and automation',
                'president_name' => 'ROBU President',
                'president_contact' => '01700000002',
                'user_name' => 'ROBU Admin',
                'password' => 'robu123',
                'role' => 'robu'
            ],
            [
                'name' => 'BRACU Business Club',
                'email' => 'bizbee@bracu.ac.bd',
                'description' => 'BRACU Business Club - Fostering business leadership',
                'president_name' => 'BizBee President',
                'president_contact' => '01700000003',
                'user_name' => 'BizBee Admin',
                'password' => 'bizbee123',
                'role' => 'bizbee'
            ],
            [
                'name' => 'BRACU Engineering & Design Forum',
                'email' => 'buedf@bracu.ac.bd',
                'description' => 'BRACU Engineering & Design Forum - Engineering excellence',
                'president_name' => 'BUEDF President',
                'president_contact' => '01700000004',
                'user_name' => 'BUEDF Admin',
                'password' => 'buedf123',
                'role' => 'buedf'
            ]
        ];

        foreach ($clubs as $clubData) {
            // Create user for club
            $user = User::create([
                'name' => $clubData['user_name'],
                'email' => $clubData['email'],
                'password' => Hash::make($clubData['password']),
                'role' => $clubData['role']
            ]);

            // Create club
            $club = Club::create([
                'name' => $clubData['name'],
                'email' => $clubData['email'],
                'description' => $clubData['description'],
                'president_name' => $clubData['president_name'],
                'president_contact' => $clubData['president_contact']
            ]);
        }
    }
}
