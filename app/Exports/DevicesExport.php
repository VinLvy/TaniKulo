<?php

namespace App\Exports;

use App\Models\Device;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DevicesExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Device::all([
            'id',
            'user_id',
            'name',
            'serial_number',
            'ssid',
            'password',
            'is_active',
            'created_at',
            'updated_at'
        ]);
    }

    public function headings(): array
    {
        return [
            'ID',
            'User ID',
            'Name',
            'Serial Number',
            'SSID',
            'Password',
            'Is Active',
            'Created At',
            'Updated At'
        ];
    }
}
