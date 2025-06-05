<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DevicesExport;
use App\Exports\MoistureReadingsExport;
use App\Exports\HumidityReadingsExport;
use App\Exports\PhReadingsExport;
use App\Exports\LuxReadingsExport;
use App\Exports\FertilizerLogsExport;
use App\Exports\WaterLogsExport;

class ExportController extends Controller
{
    public function devices()
    {
        return Excel::download(new DevicesExport, 'devices.xlsx');
    }

    public function moistures()
    {
        return Excel::download(new MoistureReadingsExport, 'moistures.xlsx');
    }

    public function humidity()
    {
        return Excel::download(new HumidityReadingsExport, 'humidity.xlsx');
    }

    public function ph()
    {
        return Excel::download(new PhReadingsExport, 'ph.xlsx');
    }

    public function lux()
    {
        return Excel::download(new LuxReadingsExport, 'lux.xlsx');
    }

    public function fertilizer()
    {
        return Excel::download(new FertilizerLogsExport, 'fertilizer.xlsx');
    }

    public function water()
    {
        return Excel::download(new WaterLogsExport, 'water.xlsx');
    }
}
