<?php

namespace App\Http\Controllers;

use App\DTOs\GraphDTO;
use App\Services\DashboardService;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            $startDateRange = Carbon::now()->subMonth()->day(26);
            $endDateRange = Carbon::now()->day(25);           
        }

        $arrYear = range(max($startDateRange->year, $endDateRange->year), min($startDateRange->year, $endDateRange->year));
        //$arrYear = [2025,2024];
        $graphDTO = new GraphDTO($startDateRange,$endDateRange);

        $widgetAbsensi = $this->dashboardService->widgetAbsensi($graphDTO);
        // $graphValueAbsensiHarianByKeterangan = $this->dashboardService->graphValueAbsensiHarianByKeterangan($graphDTO);
        // $graphPercentageAbsensiHarianByKeterangan = $this->dashboardService->graphPercentageAbsensiHarianByKeterangan($graphDTO);
        // $graphBarPegawaiByJamMasuk = $this->dashboardService->graphBarPegawaiByJamMasuk($graphDTO);
        // $graphBarValueKehadiranPerBulan = $this->dashboardService->graphBarValueKehadiranPerBulan($graphDTO);
        // $graphBarPercentageKehadiranPerBulan = $this->dashboardService->graphBarPercentageKehadiranPerBulan($graphDTO);
        // $graphBarValueKehadiranPerHari = $this->dashboardService->graphBarValueKehadiranPerHari($graphDTO);
        // $graphBarPercentageKehadiranPerHari = $this->dashboardService->graphBarPercentageKehadiranPerHari($graphDTO);
        //dd(url('/').'/admin_sdm/dashboard_chart?type=bar_pegawai_by_jam_masuk&date_range='.$request->date_range);
        // dd($request->date_range);
        $data = [
            'title' => 'Dashboard Absensi',
            'date_range' => $request->date_range,
            'arr_year' => $arrYear,
            'widget_absensi' => $widgetAbsensi,
            'url'=> url('/'),
            'default_range' => $startDateRange . ' to ' . $endDateRange,
        ];

        return view('admin_sdm.dashboard', $data);
    }

    public function dashboardChart(Request $request)
    {
        $request->validate([
            'date_range' => 'nullable|string',
            'chart' => 'nullable|string',
        ]);

        if ($request->date_range) {
            [$startDateRange, $endDateRange] = explode(" to ", $request->date_range . " to ");
            $endDateRange = $endDateRange ?: $startDateRange;
            
            $startDateRange = Carbon::parse($startDateRange);
            $endDateRange = Carbon::parse($endDateRange);
        }else{
            $startDateRange = Carbon::now()->subMonth()->day(26);
            $endDateRange = Carbon::now()->day(25);           
        }

        // $arrYear = range(max($startDateRange->year, $endDateRange->year), min($startDateRange->year, $endDateRange->year));
        // $arrYear = [2025,2024];
        $graphDTO = new GraphDTO($startDateRange,$endDateRange);

        switch($request->chart){
            case 'value_absensi_harian_by_ket':
                $graphValueAbsensiHarianByKeterangan = $this->dashboardService->graphValueAbsensiHarianByKeterangan($graphDTO);
                return $graphValueAbsensiHarianByKeterangan;
            case 'percentage_absensi_harian_by_ket':
                $graphPercentageAbsensiHarianByKeterangan = $this->dashboardService->graphPercentageAbsensiHarianByKeterangan($graphDTO);
                return $graphPercentageAbsensiHarianByKeterangan;
            case 'bar_pegawai_by_jam_masuk':
                $graphBarPegawaiByJamMasuk = $this->dashboardService->graphBarPegawaiByJamMasuk($graphDTO);
                return $graphBarPegawaiByJamMasuk;
            case 'bar_value_kehadiran_per_bulan':
                $graphBarValueKehadiranPerBulan = $this->dashboardService->graphBarValueKehadiranPerBulan($graphDTO);
                return $graphBarValueKehadiranPerBulan;
            case 'bar_percentage_kehadiran_per_bulan':
                $graphBarPercentageKehadiranPerBulan = $this->dashboardService->graphBarPercentageKehadiranPerBulan($graphDTO);
                return $graphBarPercentageKehadiranPerBulan;
            case 'bar_value_kehadiran_per_hari':
                $graphBarValueKehadiranPerHari = $this->dashboardService->graphBarValueKehadiranPerHari($graphDTO);
                return $graphBarValueKehadiranPerHari;
            case 'bar_percentage_kehadiran_per_hari':
                $graphBarPercentageKehadiranPerHari = $this->dashboardService->graphBarPercentageKehadiranPerHari($graphDTO);
                return $graphBarPercentageKehadiranPerHari;
            default:
                //jika tidak ada chart yang dipilih, kembalikan semua data chart
                break;
        }
    }
    
    public function dashboardGaji(Request $request)
    {
        $request->validate([
            'month' => 'nullable|in:1,2,3,4,5,6,7,8,9,10,11,12',
            'year' => 'nullable|in:' . implode(',', range(1900, date('Y'))),
        ]);

        $month = $request->month ?? Carbon::now()->format('m');
        $year = $request->year ?? Carbon::now()->format('Y');
        $month_text = Carbon::createFromDate($year, $month, 1)->translatedFormat('F');
        $years = range(2022, now()->year);
        $months = [
            '1' => 'Januari', '2' => 'Februari', '3' => 'Maret',
            '4' => 'April', '5' => 'Mei', '6' => 'Juni',
            '7' => 'Juli', '8' => 'Agustus', '9' => 'September',
            '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
        ];

        $startDateRange = Carbon::create($year, $month, 1)->startOfMonth();
        // $endDateRange = Carbon::create($year, $month, 1)->endOfMonth();

        $graphDTO = new GraphDTO($startDateRange);
        $widgetGaji = $this->dashboardService->widgetGaji($graphDTO);

        $data = [
            'title' => 'Dashboard Gaji',
            'month' => $month,
            'month_text' => $month_text,
            'year' => $year,
            'months' => $months,
            'years' => $years,
            'widget_gaji' => $widgetGaji,
        ];

        return view('admin_sdm.dashboard_gaji', $data);
    }

    public function getDashboardKehadiranDataValue(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $startDateRange = Carbon::createFromFormat('Y', $year)->startOfYear(); 
        $endDateRange = Carbon::createFromFormat('Y', $year)->endOfYear();

        $user = Auth::user() ?? null;
        if($user && in_array($user->role->slug,['direktur','admin-sdm'])){
            $userId = null;
        }else{
            $userId = $user->id;
        }

        // Simulasi Data (Gantilah dengan Query ke Database)
        $graphDTO = new GraphDTO($startDateRange,$endDateRange,null,$userId);
        $graphBarValueKehadiranPerBulan = $this->dashboardService->graphBarValueKehadiranPerBulan($graphDTO);

        return response()->json($graphBarValueKehadiranPerBulan);
    }

    public function getDashboardKehadiranDataPercentage(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $startDateRange = Carbon::createFromFormat('Y', $year)->startOfYear(); 
        $endDateRange = Carbon::createFromFormat('Y', $year)->endOfYear();

        $user = Auth::user() ?? null;
        if($user && in_array($user->role->slug,['direktur','admin-sdm'])){
            $userId = null;
        }else{
            $userId = $user->id;
        }

        // Simulasi Data (Gantilah dengan Query ke Database)
        $graphDTO = new GraphDTO($startDateRange,$endDateRange,null,$userId);
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
