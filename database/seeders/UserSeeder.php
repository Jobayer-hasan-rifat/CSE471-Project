<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Club;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles if they don't exist
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $ocaRole = Role::firstOrCreate(['name' => 'oca']);
        $clubRole = Role::firstOrCreate(['name' => 'club']);

        // Create Admin User
        $admin = User::updateOrCreate(
            ['email' => 'admin@bracu.ac.bd'],
            [
                'name' => 'Admin',
                'password' => Hash::make('admin@123'),
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ]
        );
        $admin->assignRole('admin');

        // Create OCA User
        $oca = User::updateOrCreate(
            ['email' => 'oca@bracu.ac.bd'],
            [
                'name' => 'OCA',
                'password' => Hash::make('oca@123'),
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ]
        );
        $oca->assignRole('oca');

        // Output the created users' credentials
        $this->command->info('Default users created with the following credentials:');
        
        $this->command->info('Admin User:');
        $this->command->info('Email: admin@bracu.ac.bd');
        $this->command->info('Password: admin@123');
        $this->command->info('-------------------');
        
        $this->command->info('OCA User:');
        $this->command->info('Email: oca@bracu.ac.bd');
        $this->command->info('Password: oca@123');
        $this->command->info('-------------------');

        // Create club users
        $clubs = [
            [
                'name' => 'BUCC',
                'email' => 'bucc@bracu.ac.bd',
                'password' => 'bucc@123',
                'department' => 'CSE',
                'role_name' => 'bucc',
                'club_info' => [
                    'name' => 'BRAC University Computer Club',
                    'short_name' => 'BUCC',
                    'description' => 'The official computer club of BRAC University',
                    'president_name' => 'John Doe',
                    'phone' => '01712345678',
                    'is_active' => true
                ]
            ],
            [
                'name' => 'ROBU',
                'email' => 'robu@bracu.ac.bd',
                'password' => 'robu@123',
                'department' => 'CSE',
                'role_name' => 'robu',
                'club_info' => [
                    'name' => 'BRAC University Robotics Club',
                    'short_name' => 'ROBU',
                    'description' => 'The official robotics club of BRAC University',
                    'president_name' => 'Jane Smith',
                    'phone' => '01712345679',
                    'is_active' => true
                ]
            ],
            [
                'name' => 'BUAC',
                'email' => 'buac@bracu.ac.bd',
                'password' => 'buac@123',
                'department' => 'BBS',
                'role_name' => 'buac',
                'club_info' => [
                    'name' => 'BRAC University Art & Photography Club',
                    'short_name' => 'BUAC',
                    'description' => 'The official art and photography club of BRAC University',
                    'president_name' => 'Mike Johnson',
                    'phone' => '01712345680',
                    'is_active' => true
                ]
            ],
            [
                'name' => 'BIZBEE',
                'email' => 'bizbee@bracu.ac.bd',
                'password' => 'bizbee@123',
                'department' => 'BBS',
                'role_name' => 'bizbee',
                'club_info' => [
                    'name' => 'BRAC University Business & Economics Forum',
                    'short_name' => 'BIZBEE',
                    'description' => 'The official business and economics forum of BRAC University',
                    'president_name' => 'Sarah Wilson',
                    'phone' => '01712345681',
                    'is_active' => true
                ]
            ],
        ];

        foreach ($clubs as $clubData) {
            // Create the user
            $user = User::updateOrCreate(
                ['email' => $clubData['email']],
                [
                    'name' => $clubData['name'],
                    'password' => Hash::make($clubData['password']),
                    'department' => $clubData['department'],
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]
            );
            
            // Assign club role and club-specific role
            $clubSpecificRole = Role::firstOrCreate(['name' => $clubData['role_name']]);
            $user->syncRoles(['club', $clubData['role_name']]);

            // Create the associated club
            $clubInfo = array_merge($clubData['club_info'], [
                'email' => $clubData['email'],
                'user_id' => $user->id
            ]);

            Club::updateOrCreate(
                ['email' => $clubData['email']],
                $clubInfo
            );

            // Output the created club user credentials
            $this->command->info('Club User:');
            $this->command->info('Email: ' . $clubData['email']);
            $this->command->info('Password: ' . $clubData['password']);
            $this->command->info('-------------------');
        }
    }
}
