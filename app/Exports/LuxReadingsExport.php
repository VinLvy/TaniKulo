<?php

namespace App\Exports;

use App\Models\LuxReading;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LuxReadingsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return LuxReading::all([
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
            'Lux Value',
            'Recorded At',
        ];
    }
}