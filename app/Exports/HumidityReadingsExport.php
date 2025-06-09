<?php

namespace App\Exports;

use App\Models\HumidityReading;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class HumidityReadingsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return HumidityReading::all([
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
            'Humidity Value',
            'Recorded At'
        ];
    }
}
