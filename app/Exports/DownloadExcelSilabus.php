<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class DownloadExcelSilabus implements FromView
{

    public function view(): View
    {
        return view("exports.silabus_excel");
    }
}
