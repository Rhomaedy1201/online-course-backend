<?php

namespace App\Http\Controllers;

use App\Exports\DownloadExcelSilabus;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportCourse extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return Excel::download(new DownloadExcelSilabus, 'silabus-template.xlsx');
    }
}
