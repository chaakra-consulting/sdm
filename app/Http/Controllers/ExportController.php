<?php

namespace App\Http\Controllers;

use App\Exports\KepegawaianExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function exportKepegawaian(Request $request)
    {
        return Excel::download(new KepegawaianExport(), 'report_kepegawaian.xlsx');
    }
}
