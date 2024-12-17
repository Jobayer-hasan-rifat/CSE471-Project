<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Club;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ClubMembersImport implements ToCollection, WithHeadingRow, WithValidation
{
    protected $club;

    public function __construct(Club $club)
    {
        $this->club = $club;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $user = User::firstOrCreate(
                ['email' => $row['email']],
                [
                    'name' => $row['name'],
                    'student_id' => $row['student_id'],
                    'password' => Hash::make('default123'), // Set a default password
                    'role' => 'club_member'
                ]
            );

            // Attach the user to the club if not already attached
            if (!$this->club->members()->where('user_id', $user->id)->exists()) {
                $this->club->members()->attach($user->id, [
                    'role' => strtolower($row['role']) === 'executive' ? 'executive' : 'member'
                ]);
            }
        }
    }

    public function rules(): array
    {
        return [
            '*.name' => ['required', 'string', 'max:255'],
            '*.email' => ['required', 'email'],
            '*.student_id' => ['required', 'string'],
            '*.role' => ['required', 'string', 'in:member,executive,Member,Executive']
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.name.required' => 'Name is required',
            '*.email.required' => 'Email is required',
            '*.email.email' => 'Invalid email format',
            '*.student_id.required' => 'Student ID is required',
            '*.role.required' => 'Role is required',
            '*.role.in' => 'Role must be either member or executive'
        ];
    }
}
