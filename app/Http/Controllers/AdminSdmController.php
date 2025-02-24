<?php

namespace App\Http\Controllers;

use App\DTOs\GraphDTO;
use App\Services\DashboardService;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;

class AdminSdmController extends Controller
{
    public function __construct(
        protected DashboardService $dashboardService,
    ) {
    }
    public function dashboard(Request $request)
    {
        $request->validate([
            'date_range' => 'nullable|string',
        ]);

        if ($request->date_range) {
            [$startDateRange, $endDateRange] = explode(" to ", $request->date_range . " to ");
            $endDateRange = $endDateRange ?: $startDateRange;
            
            $startDateRange = Carbon::parse($startDateRange);
            $endDateRange = Carbon::parse($endDateRange);
        }else{
            $startDateRange = Carbon::now()->startOfMonth();
            $endDateRange = Carbon::now()->endOfMonth();        
        }

        $arrYear = range(max($startDateRange->year, $endDateRange->year), min($startDateRange->year, $endDateRange->year));
        //$arrYear = [2025,2024];
        $graphDTO = new GraphDTO($startDateRange,$endDateRange);

        $widgetAbsensi = $this->dashboardService->widgetAbsensi($graphDTO);
        $graphValueAbsensiHarianByKeterangan = $this->dashboardService->graphValueAbsensiHarianByKeterangan($graphDTO);
        $graphPercentageAbsensiHarianByKeterangan = $this->dashboardService->graphPercentageAbsensiHarianByKeterangan($graphDTO);
        $graphBarPegawaiByJamMasuk = $this->dashboardService->graphBarPegawaiByJamMasuk($graphDTO);
        $graphBarValueKehadiranPerBulan = $this->dashboardService->graphBarValueKehadiranPerBulan($graphDTO);
        $graphBarPercentageKehadiranPerBulan = $this->dashboardService->graphBarPercentageKehadiranPerBulan($graphDTO);
        $graphBarValueKehadiranPerHari = $this->dashboardService->graphBarValueKehadiranPerHari($graphDTO);
        $graphBarPercentageKehadiranPerHari = $this->dashboardService->graphBarPercentageKehadiranPerHari($graphDTO);

        $data = [
            'title' => 'Dashboard',
            'arr_year' => $arrYear,
            'widget_absensi' => $widgetAbsensi,
            'value_absensi_harian_by_ket' => $graphValueAbsensiHarianByKeterangan,
            'percentage_absensi_harian_by_ket' => $graphPercentageAbsensiHarianByKeterangan,
            'bar_pegawai_by_jam_masuk' => $graphBarPegawaiByJamMasuk,
            'bar_value_kehadiran_per_bulan' => $graphBarValueKehadiranPerBulan,
            'bar_percentage_kehadiran_per_bulan' => $graphBarPercentageKehadiranPerBulan,
            'bar_value_kehadiran_per_hari' => $graphBarValueKehadiranPerHari,
            'bar_percentage_kehadiran_per_hari' => $graphBarPercentageKehadiranPerHari,
            'default_range' => $startDateRange . ' to ' . $endDateRange,
        ];

        return view('admin_sdm.dashboard', $data);
    }
    

    public function getDashboardKehadiranDataValue(Request $request)
    {
        $year = $request->query('year', date('Y'));
        $startDateRange = Carbon::createFromFormat('Y', $year)->startOfYear(); 
        $endDateRange = Carbon::createFromFormat('Y', $year)->endOfYear();

        // Simulasi Data (Gantilah dengan Query ke Database)
        $graphDTO = new GraphDTO($startDateRange,$endDateRange);
        $graphBarValueKehadiranPerBulan = $this->dashboardService->graphBarValueKehadiranPerBulan($graphDTO);

        return response()->json($graphBarValueKehadiranPerBulan);
    }

    public function getDashboardKehadiranDataPercentage(Request $request)
    {
        $year = $request->query('year', date('Y'));
        $startDateRange = Carbon::createFromFormat('Y', $year)->startOfYear(); 
        $endDateRange = Carbon::createFromFormat('Y', $year)->endOfYear();

        // Simulasi Data (Gantilah dengan Query ke Database)
        $graphDTO = new GraphDTO($startDateRange,$endDateRange);
        $graphBarPercentageKehadiranPerBulan = $this->dashboardService->graphBarPercentageKehadiranPerBulan($graphDTO);

        return response()->json($graphBarPercentageKehadiranPerBulan);
    }

    public function getDashboardKehadiranDataValuePerHari(Request $request)
    {
        $year = $request->query('year', date('Y'));
        $startDateRange = Carbon::createFromFormat('Y', $year)->startOfYear(); 
        $endDateRange = Carbon::createFromFormat('Y', $year)->endOfYear();
        $month = $request->get('month');

        // Simulasi Data (Gantilah dengan Query ke Database)
        $graphDTO = new GraphDTO($startDateRange,$endDateRange,$month);
        $graphBarValueKehadiranPerHari = $this->dashboardService->graphBarValueKehadiranPerHari($graphDTO);

        return response()->json($graphBarValueKehadiranPerHari);
    }

    public function getDashboardKehadiranDataPercentagePerHari(Request $request)
    {
        $year = $request->query('year', date('Y'));
        $startDateRange = Carbon::createFromFormat('Y', $year)->startOfYear(); 
        $endDateRange = Carbon::createFromFormat('Y', $year)->endOfYear();
        $month = $request->get('month');

        // Simulasi Data (Gantilah dengan Query ke Database)
        $graphDTO = new GraphDTO($startDateRange,$endDateRange,$month);
        $graphBarPercentageKehadiranPerHari = $this->dashboardService->graphBarPercentageKehadiranPerHari($graphDTO);

        return response()->json($graphBarPercentageKehadiranPerHari);
    }
}
