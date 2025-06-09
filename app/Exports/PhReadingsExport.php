<?php

namespace App\Exports;

use App\Models\PhReading;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PhReadingsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return PhReading::all([
            'id',
            'device_id',
            'value',
            'recorded_at'
        ]);
    }

    public function headings(): array
    {
        return [
            'ID',
            'Device ID',
            'PH Value',
            'Recorded At',
        ];
    }
}