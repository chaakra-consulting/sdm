<?php

namespace App\Exports;

use App\Models\DatadiriUser;
use App\Models\SocialMedia;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class KepegawaianExport implements WithMapping, WithHeadings, FromCollection, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return DatadiriUser::all();
    }

    public function map($datadiri): array
    {
        $userId = $datadiri->user_id;
        $kepegawaian = $userId ? $datadiri->kepegawaian : null;

        $jabatan = $kepegawaian ? $kepegawaian->subJabatan : null;
        $statusPekerjaan = $kepegawaian ? $kepegawaian->statusPekerjaan : null;
        $pendidikan = $datadiri ? $datadiri->pendidikan : null;
        $kesehatan = $datadiri ? $datadiri->kesehatan : null;
        $pengalamanKerjas = $datadiri ? $datadiri->pengalamanKerjas()->orderBy('tgl_selesai', 'desc')->take(2)->get() : collect();
        $pengalamanKerja1 = $pengalamanKerjas[0] ?? null;
        $pengalamanKerja2 = $pengalamanKerjas[1] ?? null;
        
        $pelatihans = $datadiri ? $datadiri->pelatihans()->orderBy('tahun_pelatihan', 'desc')->take(2)->get() : collect();
        $pelatihan1 = $pelatihans[0] ?? null;
        $pelatihan2 = $pelatihans[1] ?? null;

        $socialMedias = collect(config('constants.social_media'))->map(function ($social) {
            $socialMedia = SocialMedia::where('nama_social_media', $social)->first();
            return $socialMedia ? $socialMedia->link : '-';
        })->toArray();

        return array_merge([
            $datadiri->nip ?? '-',
            $datadiri->foto_user ? url('uploads/' . $datadiri->foto_user) : '-',
            $datadiri->nik ?? '-',
            $datadiri->foto_ktp ? url('uploads/' . $datadiri->foto_ktp) : '-',
            $datadiri->nama_lengkap ?? '-',
            $datadiri->tempat_lahir ?? '-',
            $datadiri->tanggal_lahir ?? '-',
            $datadiri->email_nonchaakra ?? '-',
            $datadiri->alamat_domisili ?? '-',
            $datadiri->agama ?? '-',
            $datadiri->jenis_kelamin ?? '-',
            $datadiri->status_pernikahan ?? '-',
            $datadiri->nama_emergency ?? '-',
            $datadiri->no_emergency ?? '-',
            $jabatan && $jabatan->nama_sub_jabatan ? $jabatan->nama_sub_jabatan : '-',
            $statusPekerjaan && $statusPekerjaan->nama_status_pekerjaan ? $statusPekerjaan->nama_status_pekerjaan : '-',
            $kepegawaian && $kepegawaian->tgl_masuk ? $kepegawaian->tgl_masuk : '-',
            $kepegawaian && $kepegawaian->tgl_berakhir ? $kepegawaian->tgl_berakhir : '-',
            $kepegawaian && $kepegawaian->no_npwp ? $kepegawaian->no_npwp : '-',
            $kesehatan && $kesehatan->golongan_darah ? $kesehatan->golongan_darah : '-',
            $kesehatan && $kesehatan->riwayat_alergi ? $kesehatan->riwayat_alergi : '-',
            $kesehatan && $kesehatan->riwayat_penyakit ? $kesehatan->riwayat_penyakit : '-',
            $kesehatan && $kesehatan->riwayat_penyakit_lain ? $kesehatan->riwayat_penyakit_lain : '-',
            $pendidikan && $pendidikan->nama_sekolah ? $pendidikan->nama_sekolah : '-',
            $pendidikan && $pendidikan->jurusan_sekolah ? $pendidikan->jurusan_sekolah : '-',
            $pendidikan && $pendidikan->alamat_sekolah ? $pendidikan->alamat_sekolah : '-',
            $pendidikan && $pendidikan->tahun_mulai ? $pendidikan->tahun_mulai : '-',
            $pendidikan && $pendidikan->tahun_selesai ? $pendidikan->tahun_selesai : '-',
            $pengalamanKerja1 && $pengalamanKerja1->nama_perusahaan ? $pengalamanKerja1->nama_perusahaan : '-',
            $pengalamanKerja1 && $pengalamanKerja1->tgl_mulai ? $pengalamanKerja1->tgl_mulai : '-',
            $pengalamanKerja1 && $pengalamanKerja1->tgl_selesai ? $pengalamanKerja1->tgl_selesai : '-',
            $pengalamanKerja1 && $pengalamanKerja1->jabatan_akhir ? $pengalamanKerja1->jabatan_akhir : '-',
            $pengalamanKerja1 && $pengalamanKerja1->alasan_keluar ? $pengalamanKerja1->alasan_keluar : '-',
            $pengalamanKerja1 && $pengalamanKerja1->no_hp_referensi ? $pengalamanKerja1->no_hp_referensi : '-',
            $pengalamanKerja1 && $pengalamanKerja1->upload_surat_referensi ? $pengalamanKerja1->upload_surat_referensi : '-',
            $pengalamanKerja2 && $pengalamanKerja2->nama_perusahaan ? $pengalamanKerja2->nama_perusahaan : '-',
            $pengalamanKerja2 && $pengalamanKerja2->tgl_mulai ? $pengalamanKerja2->tgl_mulai : '-',
            $pengalamanKerja2 && $pengalamanKerja2->tgl_selesai ? $pengalamanKerja2->tgl_selesai : '-',
            $pengalamanKerja2 && $pengalamanKerja2->jabatan_akhir ? $pengalamanKerja2->jabatan_akhir : '-',
            $pengalamanKerja2 && $pengalamanKerja2->alasan_keluar ? $pengalamanKerja2->alasan_keluar : '-',
            $pengalamanKerja2 && $pengalamanKerja2->no_hp_referensi ? $pengalamanKerja2->no_hp_referensi : '-',
            $pengalamanKerja2 && $pengalamanKerja2->upload_surat_referensi ? $pengalamanKerja2->upload_surat_referensi : '-',
            $pelatihan1 && $pelatihan1->nama_pelatihan ? $pelatihan1->nama_pelatihan : '-',
            $pelatihan1 && $pelatihan1->tujuan_pelatihan ? $pelatihan1->tujuan_pelatihan : '-',
            $pelatihan1 && $pelatihan1->tahun_pelatihan ? $pelatihan1->tahun_pelatihan : '-',
            $pelatihan1 && $pelatihan1->nomor_sertifikat ? $pelatihan1->nomor_sertifikat : '-',
            $pelatihan1 && $pelatihan1->upload_sertifikat ? $pelatihan1->upload_sertifikat : '-',
            $pelatihan2 && $pelatihan2->nama_pelatihan ? $pelatihan2->nama_pelatihan : '-',
            $pelatihan2 && $pelatihan2->tujuan_pelatihan ? $pelatihan2->tujuan_pelatihan : '-',
            $pelatihan2 && $pelatihan2->tahun_pelatihan ? $pelatihan2->tahun_pelatihan : '-',
            $pelatihan2 && $pelatihan2->nomor_sertifikat ? $pelatihan2->nomor_sertifikat : '-',
            $pelatihan2 && $pelatihan2->upload_sertifikat ? $pelatihan2->upload_sertifikat : '-',
        ],$socialMedias);
    }

    public function headings(): array
    {
        return [
                    [
                        'Data Diri', '', '', '', '','', '','', '', '', '', '', '', '',
                        'Data Kepegawaian', '', '', '', '',
                        'Data Kesehatan', '', '', '',
                        'Data Pendidikan', '', '', '', '',
                        'Data Pengalaman Kerja', '', '', '', '', '', '', '', '', '', '', '', '', '', 
                        'Data Pelatihan', '', '', '', '', '', '', '', '', '',
                        'Social Media', '', '', '',
                    ],
                    [
                        'NIP', 'Foto User', 'NIK', 'Foto KTP','Nama Lengkap', 'Tempat Lahir', 'Tanggal Lahir', 'Email', 'Alamat Domisili', 'Agama', 'Jenis Kelamin', 'Status Pernikahan','Nama Emergency', 'No Emergency',
                        'Jabatan','Status','Tanggal Masuk','Tanggal Berakhir','No NPWP',
                        'Golongan Darah','Riwayat Alergi','Riwayat Penyakit','Riwayat Penyakit Lain',
                        'Nama Sekolah','Jurusan Sekolah','Alamat Sekolah','Tahun Mulai','Tahun Selesai',
                        'Nama Perusahaan 1','Tanggal Mulai 1','Tanggal Selesai 1','Jabatan Akhir 1', 'Alasan Keluar 1', 'No. HP Referensi 1', 'Surat Referensi 1',
                        'Nama Perusahaan 2','Tanggal Mulai 2','Tanggal Selesai 2','Jabatan Akhir 2', 'Alasan Keluar 2', 'No. HP Referensi 2', 'Surat Referensi 2', 
                        'Nama Pelatihan 1','Tujuan Pelatihan 1','Tahun Pelatihan 1','Nomor Sertifikat 1','Sertifikat 1',
                        'Nama Pelatihan 2','Tujuan Pelatihan 2','Tahun Pelatihan 2','Nomor Sertifikat 2','Sertifikat 2',
                        'Instagram', 'Twitter','Github','Linkedin'
                    ]

        ];
    }

    public function registerEvents(): array
    {   
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                // Merge cells
                $mergeCells = [
                    'A1:N1',
                    'O1:S1',
                    'T1:W1',
                    'X1:AB1',
                    'AC1:AP1',
                    'AQ1:AZ1',
                    'BA1:BD1',
                ];

                foreach ($mergeCells as $cell) {
                    $sheet->mergeCells($cell);
        
                    // Apply border styling to each merge cell
                    $sheet->getStyle($cell)->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['argb' => '000000'], // Black color
                            ],
                        ],
                    ]);
                }

                $sheet->getStyle('A1:BD2')->applyFromArray([
                    'font' => ['bold' => true],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ]);
            },
        ];

    }
}
