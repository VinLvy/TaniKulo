<?php

namespace App\Exports;

use App\Models\MoistureReading;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MoistureReadingsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return MoistureReading::all([
            'id', 'device_id', 'moisture', 'created_at', 'updated_at'
        ]);
    }

    public function headings(): array
    {
        return [
            'ID', 'Device ID', 'Moisture', 'Created At', 'Updated At'
        ];
    }
}
