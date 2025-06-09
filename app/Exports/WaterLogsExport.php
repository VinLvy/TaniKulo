<?php

namespace App\Exports;

use App\Models\WaterLog;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class WaterLogsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return WaterLog::all([
            'id',
            'device_id',
            'status',
            'amount',
            'recorded_at'
        ]);
    }

    public function headings(): array
    {
        return [
            'ID',
            'Device ID',
            'Status',
            'Amount',
            'Recorded At',
        ];
    }
}