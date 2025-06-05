<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DevicesExport;
use App\Exports\MoistureReadingsExport;
// dan lainnya...

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

    // Tambahkan method lain untuk humidity, ph, lux, fertilizer, water, dll.
}

