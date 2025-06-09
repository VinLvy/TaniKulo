<?php

namespace App\Exports;

use App\Models\FertilizerLog;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FertilizerLogsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return FertilizerLog::all([
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