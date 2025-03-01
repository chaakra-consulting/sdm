<?php

namespace App\Http\Controllers;

use App\Helpers\Functions;
use App\Models\Absensi;
use App\Models\AbsensiHarian;
use App\Models\AbsensiVerifikasi;
use App\Models\DatadiriUser;
use App\Models\HariLibur;
use App\Models\KeteranganAbsensi;
use App\Rules\TimeFormat;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AbsensiHarianController extends Controller
{
    //  /**
    //  * Display a listing of the resource.
    //  */
    // public function index()
    // {
    //     $data_diri = DatadiriUser::all();
    //     $title = 'Data Kepegawaian';

    //     return view('admin_sdm.index_absensi_harian', compact('data_diri', 'title'));
    // }
    /**
     * Display a listing of the resource.
     */
    public function show(Request $request,$id)
    {
        $user = Auth::user();
        if($user->role->slug == 'karyawan'){
            $id = $user->dataDiri->id;
        }

        $request->validate([
            //'pegawai_id'              => 'required|exists:\App\Models\DatadiriUser,id',
            // 'year'                    => 'nullable|string|in:' . implode(',', range(1900, date('Y') + 1)),
            // 'month'                   => 'nullable|string|in:01,02,03,04,05,06,07,08,09,10,11,12',
            'date_range'                   => 'nullable|string',
        ]);

        $keteranganAbsensis = KeteranganAbsensi::all();

        $widgetCollection = collect();
        if ($request->date_range) {
            [$startDateRange, $endDateRange] = explode(" to ", $request->date_range . " to ");
            $startDateRange = Carbon::parse($startDateRange);
            $endDateRange = $endDateRange ? Carbon::parse($endDateRange) : $startDateRange;
        }else{
            $startDateRange = Carbon::now()->subMonth()->day(26);
            $endDateRange = Carbon::now()->day(25);             
        }

        $countHariKerja = 0;
        for ($date = clone $startDateRange; $date->lte($endDateRange); $date->addDay()) {
            $hari = $date->translatedFormat('l') ?? '-';          
            $isLibur = Absensi::where('hari',$hari)->value('is_libur');
            $isHariLibur = HariLibur::where('tanggal',$date)->first();

            if($isLibur == false && !$isHariLibur) $countHariKerja++;
        }
    
        $widgetCollection->push((object)[
            'nama' => 'Hari Masuk Kerja',
            'count'    => $countHariKerja ? $countHariKerja : 0,
        ]);

        $widget = AbsensiHarian::where('pegawai_id',$id)->whereBetween('tanggal_kerja', [$startDateRange, $endDateRange])->get();

        $terlambatCount = $widget->filter(function ($item) {
            $data = json_decode($item->data ?? null, true);
            return isset($data['batas_waktu_terlambat']) && $item->waktu_masuk > $data['batas_waktu_terlambat'];
        })->count();

        $widgetCollection->push((object)[
            'nama' => 'Terlambat',
            'count'    => $widget ? $terlambatCount : 0,
        ]);

        foreach($keteranganAbsensis as $keterangan){
            $widgetCollection->push((object)[
                'nama' => $keterangan->nama ?? '-',
                'count'    => $widget ? $widget->where('keterangan_id', $keterangan->id)->count() : 0,
            ]);
        }

        $absensiCollection = collect();
        for ($date = $startDateRange->copy(); $date->lte($endDateRange); $date->addDay()) {
            $hari = $date->translatedFormat('l') ?? '-';
            $year = $date->format('Y');
            $month = $date->format('m');
            $day = $date->format('d');
        
            $isLibur = Absensi::where('hari',$hari)->value('is_libur');
            $isHariLibur = HariLibur::where('tanggal',$date)->first();

            $statusLibur = ($isLibur == true || $isHariLibur) ? true : false;

            $absensiHarian = AbsensiHarian::where('pegawai_id',$id)->where('tanggal_kerja', $date->toDateString())->first();           
            $keterangan = $absensiHarian ? $absensiHarian->keteranganAbsensi : null;

            $data = json_decode($absensiHarian->data ?? null, true);
            $batasWaktuTerlambat = $data && $data['batas_waktu_terlambat'] ? $data['batas_waktu_terlambat'] : null;

            if($batasWaktuTerlambat && $absensiHarian->waktu_masuk && $absensiHarian->waktu_masuk > $batasWaktuTerlambat) $isTelat = "Terlambat";
            elseif($batasWaktuTerlambat && $absensiHarian->waktu_masuk && $absensiHarian->waktu_masuk <= $batasWaktuTerlambat) $isTelat = "On Time";
            else $isTelat = "-";

            if ($day >= 26) $dateVerif = Carbon::create($year, $month + 1, 1);
            else $dateVerif = Carbon::create($year, $month, 1);

            $absensiVerifikasi = AbsensiVerifikasi::where('pegawai_id',$id)
                ->where('tahun',intval($dateVerif->format('Y')))
                ->where('bulan',intval($dateVerif->format('m')))
                ->first();
    
            $absensiCollection->push((object)[
                'tanggal' => $date->toDateString(),
                'hari'    => $absensiHarian && $absensiHarian->hari_kerja ? $absensiHarian->hari_kerja : $hari,
                'status_verifikasi_absensi'=> $absensiVerifikasi ? true : false,
                'status_libur'=> $statusLibur,
                'absensi' => $absensiHarian ? (object)[
                    'id' => $absensiHarian->id,
                    'waktu_masuk' => $absensiHarian->waktu_masuk,
                    'waktu_pulang' => $absensiHarian->waktu_pulang,
                    'keterangan_id' => $absensiHarian->keterangan_id,
                    'keterangan' => $absensiHarian->keterangan,
                    'durasi_lembur' => $absensiHarian->durasi_lembur,
                    'upload_surat_dokter' => $absensiHarian->upload_surat_dokter,
                    'keterangan_absensi' => $keterangan ? $keterangan->nama : null,
                    'status_keterlambatan' => $isTelat,
                ] : null,
            ]);
        }
        //dd($absensiCollection);

        $roleSlug = Auth::user()->role->slug;
        $role = Functions::generateUrlByRoleSlug($roleSlug);
        $pegawai = DatadiriUser::where('id',$id)->first();
        $kepegawaian = $pegawai ? $pegawai->kepegawaian : null;
        $jabatan = $kepegawaian ? $kepegawaian->subJabatan : null;
        $divisi = $kepegawaian ? $kepegawaian->divisi : null;

        $verifikasi = AbsensiVerifikasi::where('pegawai_id',$id)
            ->where('tahun',Carbon::now()->format('Y'))
            ->where('bulan',Carbon::now()->format('m'))->first();
        if($verifikasi) $statusVerifikasi = 'Terverifikasi';
        else $statusVerifikasi = 'Belum Terverifikasi';
        
        $data = [
            'title' => 'Detail Absensi',
            'role'=>$role,
            'pegawai_id'=>$id,
            'verifikasi'=> $statusVerifikasi,
            'nama'=> $pegawai ? $pegawai->nama_lengkap : null,
            'foto_user'=> $pegawai ? $pegawai->foto_user : null,
            'nip'=> $kepegawaian ? $kepegawaian->nip : '-',
            'jabatan'=> $jabatan ? $jabatan->nama_sub_jabatan : '-',
            'divisi'=> $divisi ? $divisi->nama_divisi : '-',
            'filter_year' => $request->year ? $request->year : Carbon::now()->format('Y'),
            'filter_month' => $request->month ? $request->month : Carbon::now()->format('m'),
            'month_text' => $request->month ? Carbon::createFromFormat('m', $request->month)->translatedFormat('F') : Carbon::now()->translatedFormat('F'),
            'absensi_harian' => $absensiCollection,
            'keterangan_absensi' => $keteranganAbsensis,
            'widget' => $widgetCollection,
            'default_range' => $startDateRange . ' to ' . $endDateRange,
        ];
        //dd($data);
        // return view('admin.sub_jabatan', $data);
        return view('admin_sdm.detail_absensi_harian', $data);
    }

    public function store(Request $request,$id)
    {
         try {
             DB::beginTransaction();
             $request->validate([
                 //'pegawai_id'                => 'required|exists:\App\Models\DatadiriUser,id',
                 'tanggal_kerja'             => 'required|date',
                 'hari_kerja'                => 'required|string',
                 'waktu_masuk'               => ['nullable', new TimeFormat],
                 'waktu_pulang'              => ['nullable', new TimeFormat],
                 'keterangan_id'             => 'nullable|exists:\App\Models\KeteranganAbsensi,id',
                 'keterangan'                => 'nullable|string',
                 'durasi_lembur'             => 'nullable|numeric',
                //  'upload_surat_dokter'       => 'nullable|mimes:jpeg,png,jpg,gif|max:2048',
                 'upload_surat_dokter'       => 'nullable|mimes:pdf|max:2048',
                ]);

             $userId = DatadiriUser::where('id',$id)->value('user_id');
             $absensi = Absensi::where('hari',strtolower($request->hari_kerja))->first();

            $filename = null;

            if ($request->hasFile('upload_surat_dokter')) {
                $file = $request->file('upload_surat_dokter');
                $filename = uniqid() . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads'), $filename);
            }

             $data = [
                'user_id'               => $userId,
                'pegawai_id'            => $id,
                'hari_kerja'            => $request->hari_kerja,
                'tanggal_kerja'         => $request->tanggal_kerja,
                'waktu_masuk'           => $request->waktu_masuk,
                'waktu_pulang'          => $request->waktu_pulang,
                'keterangan_id'         => $request->keterangan_id,
                'keterangan'            => $request->keterangan,
                'durasi_lembur'         => $request->durasi_lembur,
                'data'                  => $absensi,
                'upload_surat_dokter'   => $filename,
            ];
    
            AbsensiHarian::create($data);
             
             DB::commit();
             return redirect()->back()->with('success', 'Data Absensi Harian Berhasil Diubah');
         } catch (Exception $e) {
             DB::rollback();
             //return redirect()->back()->withInput()->with('error', 'Gagal Mengubah Data');
             return redirect()->back()->withInput()->with('error', "{$e->getMessage()}");
         }
    }

    /**
    * Store a newly created resource in storage.
    */
   public function update(Request $request, $pegawai_id, $id)
   {
        try {
            DB::beginTransaction();

            $request->validate([
                // 'id'                       => 'required|exists:\App\Models\Absensi,id',
                'hari_kerja'                => 'required|string',
                'tanggal_kerja'             => 'required|date',
                'hari_kerja'                => 'nullable|string',
                'waktu_masuk'               => ['nullable', new TimeFormat],
                'waktu_pulang'              => ['nullable', new TimeFormat],
                'keterangan_id'             => 'nullable|exists:\App\Models\KeteranganAbsensi,id',
                'keterangan'                => 'nullable|string',
                'durasi_lembur'             => 'nullable|numeric',
                // 'upload_surat_dokter'       => 'nullable|mimes:jpeg,png,jpg,gif|max:2048',
                'upload_surat_dokter'       => 'nullable|mimes:pdf|max:2048',
            ]);

            $userId = DatadiriUser::where('id',$pegawai_id)->value('user_id');
            $absensiHarian = AbsensiHarian::where('id',$id)->first();

            $filename = null;
            if ($request->hasFile('upload_surat_dokter')) {
                $file = $request->file('upload_surat_dokter');
                $filename = uniqid() . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads'), $filename);
            }
            //dd($request->all());
            $updateData = collect([
                //'user_id'               => $userId,
                //'pegawai_id'            => $id,
                'hari_kerja'            => $request->hari_kerja,
                'tanggal_kerja'         => $request->tanggal_kerja,
                'waktu_masuk'           => $request->waktu_masuk,
                'waktu_pulang'          => $request->waktu_pulang,
                'keterangan_id'         => $request->keterangan_id,
                'keterangan'            => $request->keterangan,
                'durasi_lembur'         => $request->durasi_lembur,
                'upload_surat_dokter'   => $filename ?? $absensiHarian->upload_surat_dokter,
            ]);
            
            $absensiHarian->update($updateData->toArray());

            DB::commit();
            return redirect()->back()->with('success', 'Data Absensi Harian Berhasil Diubah');
        } catch (Exception $e) {
            DB::rollback();
            //return redirect()->back()->with('error', 'Gagal Mengubah Data');
            return redirect()->back()->with('error', "{$e->getMessage()}");
        }
   }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $getAbsensiJabatan = AbsensiHarian::findOrFail($id);
    
            $getAbsensiJabatan->delete();
    
            return redirect()->back()->with('success', 'Data Absensi Harian berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
    }

        /**
    * Store a newly created resource in storage.
    */
   public function storeVerifikasi(Request $request,$id)
   {
        try {
            DB::beginTransaction();

            $pegawai = DatadiriUser::where('id',$id)->firstOrFail();

            $check = AbsensiVerifikasi::where('pegawai_id',$id)
                ->where('tahun',Carbon::now()->format('Y'))
                ->where('bulan',Carbon::now()->format('m'))
                ->first();
            if($check) return redirect()->back()->with('error', 'Verifikasi Sudah Dilakukan');

            $data = [
                'user_id'               => $pegawai->user_id,
                'pegawai_id'            => $pegawai->id,
                'tahun'                 => Carbon::now()->format('Y'),
                'bulan'                 => Carbon::now()->format('m'),
                'tanggal_verifikasi'    => Carbon::now(),
            ];
    
            AbsensiVerifikasi::create($data);

            DB::commit();
            return redirect()->back()->with('success', 'Verifikasi Berhasil');
        } catch (Exception $e) {
            DB::rollback();
            //return redirect()->back()->with('error', 'Gagal Mengubah Data');
            return redirect()->back()->with('error', "{$e->getMessage()}");
        }
   }
}
