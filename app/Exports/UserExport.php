<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class UserExport implements FromCollection, WithHeadings, WithTitle
{
    public function collection()
    {
        return User::all([
            'id',
            'name',
            'email',
            'google_id',
            'created_at',
            'updated_at'
        ]);
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Email',
            'Google ID',
            'Created At',
            'Updated At',
        ];
    }

    public function title(): string
    {
        return 'Users';
    }
}
