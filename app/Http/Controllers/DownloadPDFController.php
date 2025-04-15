<?php

namespace App\Http\Controllers;

use App\Models\DatadiriUser;
use App\Models\DataKepegawaian;
use App\Models\GajiBulanan;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DownloadPDFController extends Controller
{
    public function generatePDFPayslip($id)
    {
        // Ambil data karyawan dari database
        $gajiBulanan = GajiBulanan::where('hash',$id)->firstOrFail();
        $pegawai = $gajiBulanan->pegawai;
        $kepegawaian = $pegawai ? $pegawai->kepegawaian : null;
        $jabatan = $kepegawaian ? $kepegawaian->subJabatan : null;
        $statusPekerjaan = $kepegawaian ? $kepegawaian->statusPekerjaan : null;
        $divisi = $kepegawaian ? $kepegawaian->divisi : null;

        $namaAdminSDM = DatadiriUser::whereHas('user', function ($query) {
            $query->whereHas('role', function ($query) {
                $query->where('slug', 'admin-sdm');
            });
        })
        ->whereHas('kepegawaian', function ($query) {
            $query->where('is_active', true);
        })
        ->value('nama_lengkap');

        $potonganTotal = $gajiBulanan->potongan_gaji_pokok + $gajiBulanan->potongan_uang_makan + $gajiBulanan->potongan_kinerja + $gajiBulanan->potongan_keterlambatan + $gajiBulanan->potongan_pajak + $gajiBulanan->potongan_bpjs_ketenagakerjaan + $gajiBulanan->potongan_bpjs_kesehatan + $gajiBulanan->potongan_kasbon + $gajiBulanan->potongan_lainnya;
        // $insentifTotal = $gajiBulanan->insentif_kinerja + $gajiBulanan->insentif_uang_makan + $gajiBulanan->insentif_uang_bensin + $gajiBulanan->insentif_penjualan + $gajiBulanan->insentif_lainnya;
        $pendapatanTotal = $gajiBulanan->gaji_pokok + $gajiBulanan->insentif_kinerja + $gajiBulanan->insentif_uang_makan + $gajiBulanan->insentif_uang_bensin + $gajiBulanan->insentif_penjualan + $gajiBulanan->overtime + $gajiBulanan->insentif_lainnya;
        $gajiTotal = $pendapatanTotal - $potonganTotal;

        $start = $gajiBulanan->tanggal_gaji ? Carbon::parse($gajiBulanan->tanggal_gaji)->subMonth()->day(26)->translatedFormat('d F Y') : ' ';
        $end = $gajiBulanan->tanggal_gaji ? Carbon::parse($gajiBulanan->tanggal_gaji)->day(25)->translatedFormat('d F Y') : ' ';
        $cutoff = $start. ' - ' .$end;
        $tanggalGaji = $gajiBulanan->tanggal_gaji ? Carbon::parse($gajiBulanan->tanggal_gaji)->translatedFormat('F Y') : ' ';

        $data = [
            'nama_admin_sdm' => $namaAdminSDM,
            'nama_direktur' => 'Herlina Eka Subandriyo Putri., M.Psi.',
            'tanggal_gaji' => $tanggalGaji,
            'cutoff' => $cutoff,
            'pegawai_id' => $kepegawaian ? $kepegawaian->nip : '-',
            'pegawai_nama' => $pegawai ? $pegawai->nama_lengkap : '-',
            'jabatan' => $jabatan ? $jabatan->nama_sub_jabatan : '-',
            'divisi' => $divisi ? $divisi->nama_divisi : '-',
            'npwp' => $kepegawaian && $kepegawaian->npwp ? $kepegawaian->npwp : '-',
            'employment_status' => $statusPekerjaan ? $statusPekerjaan->nama_status_pekerjaan : '-',
            'gaji_total' => number_format($gajiTotal ?? 0, 0, ',', '.'),
            'potongan_total' => number_format($potonganTotal ?? 0, 0, ',', '.'),
            'pendapatan_total' => number_format($pendapatanTotal ?? 0, 0, ',', '.'),
            'keterangan_potongan_lainnya' => $gajiBulanan->keterangan_potongan_lainnya ?? null,
            'earnings' => [
                ['name' => 'Gaji pokok', 'amount' => number_format($gajiBulanan->gaji_pokok ?? 0, 0, ',', '.')],
                ['name' => 'Insentif Uang Makan', 'amount' => number_format($gajiBulanan->insentif_uang_makan ?? 0, 0, ',', '.')],
                ['name' => 'Insentif Kinerja', 'amount' => number_format($gajiBulanan->insentif_kinerja ?? 0, 0, ',', '.')],
                ['name' => 'Insentif Uang Bensin', 'amount' => number_format($gajiBulanan->insentif_uang_bensin ?? 0, 0, ',', '.')],
                ['name' => 'Insentif Penjualan', 'amount' => number_format($gajiBulanan->insentif_penjualan ?? 0, 0, ',', '.')],
                ['name' => 'Overtime', 'amount' => number_format($gajiBulanan->overtime ?? 0, 0, ',', '.')],
                ['name' => 'Insentif Lainnya', 'amount' => number_format($gajiBulanan->insentif_lainnya ?? 0, 0, ',', '.')],
                ['name' => '', 'amount' => ''],
            ],
            'deductions' => [
                ['name' => 'Potongan Gaji Pokok', 'amount' => number_format($gajiBulanan->potongan_gaji_pokok ?? 0, 0, ',', '.')],
                ['name' => 'Potongan Uang Makan', 'amount' => number_format($gajiBulanan->potongan_uang_makan ?? 0, 0, ',', '.')],
                ['name' => 'Potongan Keterlambatan', 'amount' => number_format($gajiBulanan->potongan_keterlambatan ?? 0, 0, ',', '.')],
                ['name' => 'Potongan Kinerja', 'amount' => number_format($gajiBulanan->potongan_kinerja ?? 0, 0, ',', '.')],
                ['name' => 'Potongan Kasbon', 'amount' => number_format($gajiBulanan->potongan_kasbon ?? 0, 0, ',', '.')],
                ['name' => 'BPJS Ketenagakerjaan', 'amount' => number_format($gajiBulanan->potongan_bpjs_ketenagakerjaan ?? 0, 0, ',', '.')],
                ['name' => 'BPJS Kesehatan', 'amount' => number_format($gajiBulanan->potongan_bpjs_kesehatan ?? 0, 0, ',', '.')],
                ['name' => 'Potongan Lainnya', 'amount' => number_format($gajiBulanan->potongan_lainnya ?? 0, 0, ',', '.')],
            ],
        ];

        // Generate PDF
        $pdf = Pdf::loadView('payslip', $data);
        return $pdf->stream('Payslip_' . $gajiBulanan->pegawai->nama_lengkap. '.pdf');
    }
}
