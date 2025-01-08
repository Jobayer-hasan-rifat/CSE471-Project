<?php

namespace App\Exports;

use App\Models\Club;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ClubMembersExport implements FromCollection, WithHeadings, WithMapping
{
    protected $club;

    public function __construct(Club $club)
    {
        $this->club = $club;
    }

    public function collection()
    {
        return $this->club->members()
            ->with('pivot')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Name',
            'Email',
            'Student ID',
            'Role',
            'Joined Date'
        ];
    }

    public function map($member): array
    {
        return [
            $member->name,
            $member->email,
            $member->student_id,
            ucfirst($member->pivot->role),
            $member->pivot->created_at->format('Y-m-d')
        ];
    }
}
