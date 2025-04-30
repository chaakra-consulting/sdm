<?php
namespace App\Exports;

use App\Models\AbsensiHarian;
use App\Models\DatadiriUser;
use App\Models\User;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class AbsensiExport implements FromView
{
    protected $bulan;
    protected $tahun;

    public function __construct($bulan,$tahun)
    {
        $this->bulan = $bulan;
        $this->tahun = $tahun;
    }

    public function view(): View
    {
        $bulan = $this->bulan;
        $tahun = $this->tahun;
        $startDate = Carbon::create($tahun, $bulan, 1)->subMonth()->day(26);
        $endDate = Carbon::create($tahun, $bulan, 25);
    
        $pegawais = DatadiriUser::whereHas('kepegawaian', function ($query) use ($startDate) {
            $query->where(function ($q) use ($startDate) {
                $q->whereNull('tgl_berakhir')
                  ->orWhere('tgl_berakhir', '>=', $startDate);
            });
        })->get();

        $absensis = AbsensiHarian::whereBetween('tanggal_kerja', [$startDate, $endDate])
            ->orderBy('tanggal_kerja')
            ->get()
            ->groupBy('pegawai_id');

        return view('exports.absensi', [
            'pegawais' => $pegawais,
            'absensis' => $absensis,
            'bulan' => $bulan,
            'tahun' => $tahun,
        ]);
    }
    
}
