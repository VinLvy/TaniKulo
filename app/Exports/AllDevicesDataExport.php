<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;


class AllDevicesDataExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new UserExport(),
            new DevicesExport(),
            new MoistureReadingsExport(),
            new HumidityReadingsExport(),
            new PhReadingsExport(),
            new LuxReadingsExport(),
            new FertilizerLogsExport(),
            new WaterLogsExport(),
        ];
    }
}
