<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\SubTask;
use App\Models\HariLibur;
use App\Models\Perusahaan;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\RevisiLaporan;
use App\Models\ProjectPerusahaan;
use Illuminate\Support\Facades\DB;
use App\Events\SubtaskStatusChanged;
use GrahamCampbell\ResultType\Success;

class ManajerController extends Controller
{
    public function index()
    {
        $title = 'Dashboard';

        return view('manajer.index', compact('title'));
    }

    // manajemen perusahaan : data perusahaan
    public function show()
    {
        $perusahaan = Perusahaan::all();
        $title = 'List Instansi';

        return view('master.daftar_perusahaan', compact('title', 'perusahaan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_perusahaan' => 'required',
            'alamat' => 'nullable',
            'nama_pimpinan' => 'nullable',
            'kontak' => 'nullable',
            'gender' => 'nullable'
        ]);

        $data = [
            'nama_perusahaan' => $request->nama_perusahaan,
            'alamat' => $request->alamat,
            'nama_pimpinan' => $request->nama_pimpinan,
            'kontak' => $request->kontak,
            'gender' => $request->gender
        ];

        Perusahaan::create($data);

        return redirect()->back()->with('success', 'Instansi berhasil di tambahkan');
    }

    public function update(Request $request, $id)
    {
        $getDataPerusahaan = Perusahaan::findOrFail($id);
        $request->validate([
            'nama_perusahaan' => 'required',
            'alamat' => 'nullable',
            'nama_pimpinan' => 'nullable',
            'kontak' => 'nullable',
            'gender' => 'nullable'
        ]);
        $data = [
            'nama_perusahaan' => $request->nama_perusahaan,
            'alamat' => $request->alamat,
            'nama_pimpinan' => $request->nama_pimpinan,
            'kontak' => $request->kontak,
            'gender' => $request->gender
        ];
        $getDataPerusahaan->update($data);

        return redirect()->back()->with('success', 'Instansi berhasil di update');
    }

    public function destroy($id)
    {
        $getDataPerusahaan = Perusahaan::findOrFail($id);
        foreach ($getDataPerusahaan->projects as $project) {
            $project->project_users()->delete();
            $project->tasks()->delete();
            $project->delete();
        }

        $getDataPerusahaan->delete();

        return redirect()->back()->with('success', 'Instansi berhasil di hapus');
    }

    public function dataTransfer()
    {
        $getDataPerusahaan = DB::connection('db_bukukas')->table('master_customers')
            ->select(
                'id', 
                'name', 
                'address', 
                'email', 
                'contact', 
                'gender_contact'
                )
            ->get();

        $insert = 0;
        $update = 0;
        foreach ($getDataPerusahaan as $perusahaan) {
            $exists = DB::connection('mysql')->table('tb_m_perusahaans')->where('nama_perusahaan', $perusahaan->name)->first();

            if ($exists) {
                if (
                    $exists->nama_perusahaan !== $perusahaan->name ||
                    $exists->alamat !== $perusahaan->address ||
                    $exists->nama_pimpinan !== $perusahaan->email ||
                    $exists->kontak !== $perusahaan->contact ||
                    $exists->gender !== $perusahaan->gender_contact
                ) {
                    DB::connection('mysql')->table('tb_m_perusahaans')->where('nama_perusahaan', $perusahaan->name)->update([
                            'nama_perusahaan' => $perusahaan->name,
                            'alamat' => $perusahaan->address,
                            'nama_pimpinan' => $perusahaan->email,
                            'kontak' => $perusahaan->contact,
                            'gender' => $perusahaan->gender_contact,
                        ]);
                    $update++;
                }
            } else {
                Perusahaan::create([
                    'nama_perusahaan' => $perusahaan->name,
                    'alamat' => $perusahaan->address,
                    'nama_pimpinan' => $perusahaan->email,
                    'kontak' => $perusahaan->contact,
                    'gender' => $perusahaan->gender_contact
                ]);

                $insert++;
            }
        }
        if ($insert == 0 && $update == 0) {
            return redirect()->back()->with('success', 'Tidak ada data yang di transfer');
        }

        return redirect()->back()->with('success', 'Data Instansi berhasil di transfer');
    }
    
    public function laporanKinerja()
    {
        $title = 'Laporan Kinerja';
        $getDataUser = User::all();

        return view('manajer.laporan_kinerja', compact('title', 'getDataUser'));
    }

    private function hariKerja($start, $end)
    {
        $libur = HariLibur::whereBetween('tanggal', [$start, $end])
            ->pluck('tanggal')
            ->map(function ($tgl) {
                return \Carbon\Carbon::parse($tgl)->format('Y-m-d');
            })
            ->toArray();
        $periode = [];
        $tanggalSekarang = Carbon::parse($start);
        $tanggalAkhir = Carbon::parse($end);
        while ($tanggalSekarang->lte($tanggalAkhir)) {
            $hari = $tanggalSekarang->format('Y-m-d');
            if ($tanggalSekarang->dayOfWeek != Carbon::SUNDAY && !in_array($hari, $libur)) {
                $periode[] = $hari;
            }
            $tanggalSekarang->addDay();
        }

        return $periode;
    }

    public function listLaporanKinerja($id, Request $request)
    {
        $title = 'Laporan Kinerja';
        $getDataUser = User::with(
            'users_project.project_perusahaan',
            'users_task.task',
            )->find($id);
        $periode = $request->query('periode');
        $tanggalAwal = null;
        $tanggalAkhir = null;
        if($periode){
            [$tanggalAwal, $tanggalAkhir] = explode('_', $periode);
        }
        $getDataLaporan = $getDataUser->subtask()
            ->with(
                'task',
                'task.project_perusahaan.project_users',
                'task.tipe_task', 
                'lampiran',
                'detail_sub_task')
            ->where('user_id', $id)
            ->get();
        $periodeMap = [];
        foreach ($getDataLaporan as $subtask) {
            foreach ($subtask->detail_sub_task as $detail) {
                $tanggal = $detail->tanggal;
                if (!$tanggal) continue;
                try {
                    $tgl = \Carbon\Carbon::parse($tanggal);

                    $start = $tgl->copy()->day(26);
                    if ($tgl->day < 26) {
                        $start->subMonth();
                    }

                    $end = $start->copy()->addMonth()->day(25);
                    $periodeKey = $start->format('Y-m-d') . '_' . $end->format('Y-m-d');

                    if (!isset($periodeMap[$periodeKey])) {
                        $periodeMap[$periodeKey] = [
                            'start' => $start,
                            'end' => $end,
                            'items' => []
                        ];
                    }

                    $periodeMap[$periodeKey]['items'][] = [
                        'subtask' => $subtask,
                        'detail' => $detail
                    ];
                } catch (\Exception $e) {
                    continue;
                }

            }
        }

        $groupedLaporan = collect($periodeMap);
        $statuses = [];

            foreach ($groupedLaporan as $periodeKey => $data) {
                $items = $data['items'];
                $start = $data['start'];
                $end = $data['end'];
                
                $tanggalKerja = $this->hariKerja($start->format('Y-m-d'), $end->format('Y-m-d'));
                $tanggalApprove = [];
                $tanggalDikirim = [];
                $hasRevise = false;
                $hasNull = false;

                foreach ($items as $item) {
                    $subtask = $item['subtask'];
                    $detail = $item['detail'];

                    if ($detail->tanggal) {
                        $tanggal = \Carbon\Carbon::parse($detail->tanggal)->format('Y-m-d');
                        $tanggalDikirim[] = $tanggal;
                        
                        if ($subtask->status === 'approve') {
                            $tanggalApprove[] = $tanggal;
                        }
                        
                        if ($subtask->status === 'revise') {
                            $hasRevise = true;
                        }
                        
                        if ($subtask->status === null) {
                            $hasNull = true;
                        }
                    }
                }

                $tanggalDikirim = array_unique($tanggalDikirim);
                $tanggalApprove = array_unique($tanggalApprove);
                $hariKerjaCount = count($tanggalKerja);
                $dikirimCount = count($tanggalDikirim);
                $approveCount = count($tanggalApprove);
                $semuaDikirim = $dikirimCount === $hariKerjaCount;
                $semuaApprove = $approveCount === $hariKerjaCount;

                $statuses[$periodeKey] = [
                    'perlu_revisi' => $hasRevise,
                    'belum_dikirim' => !$semuaDikirim && !$hasRevise,
                    'semua_approve' => $semuaApprove,
                    'semua_dikirim' => $semuaDikirim,
                    'hari_kerja_count' => $hariKerjaCount,
                    'dikirim_count' => $dikirimCount,
                    'approve_count' => $approveCount,
                ];
            }

        return view('manajer.list_laporan_kinerja', compact(
            'title', 
            'getDataUser', 
            'getDataLaporan', 
            'groupedLaporan',
            'periode',
            'statuses'
        ));
    }
    public function detailLaporanKinerja($id, Request $request)
    {
        $title = 'Detail Laporan Kinerja';
        $getDataUser = User::findOrFail($id);
        $periode = $request->query('periode');
        $filterTanggal = [];
        if ($periode) {
            if (!preg_match('/\d{4}-\d{2}-\d{2}_\d{4}-\d{2}-\d{2}/', $periode)) {
                abort(400, 'Format periode tidak valid');
            }
            
            [$start, $end] = explode('_', $periode);
            
            try {
                $filterTanggal = [
                    Carbon::parse($start)->startOfDay(),
                    Carbon::parse($end)->endOfDay()
                ];
            } catch (\Exception $e) {
                abort(400, 'Format tanggal tidak valid');
            }
        }

        $getDataLaporan = $getDataUser->subtask()
            ->with([
                'task.tipe_task',
                'lampiran',
                'detail_sub_task' => function ($query) {
                    $query->where('is_active', 1);
                }
            ])
            ->where('user_id', $id)
            ->when($filterTanggal, function ($query) use ($filterTanggal) {
                return $query->whereBetween('tgl_sub_task', $filterTanggal);
            })
            ->get();
        $totalDurasiMenit = 0;
        foreach($getDataLaporan as $subtask){
            foreach($subtask->detail_sub_task as $detail){
                $totalDurasiMenit += $detail->durasi ?? 0;
            }
        }
        $durasiJam = floor($totalDurasiMenit / 60);
        $durasiMenit = $totalDurasiMenit % 60;
        return view('manajer.detail_laporan_kinerja', compact(
            'title', 
            'getDataUser', 
            'getDataLaporan',
            'periode',
            'durasiJam',
            'durasiMenit'
        ));
    }
    public function approveLaporanKinerja(Request $request, $id)
    {
        $request->validate([
            'periode' => 'required|string'
        ]);

        $dates = explode('_', $request->periode);
        $startDate = Carbon::parse($dates[0])->startOfDay();
        $endDate = Carbon::parse($dates[1])->endOfDay();
        
        $subtasks = SubTask::where('user_id', $id)
            ->whereHas('detail_sub_task', function ($query) use ($startDate, $endDate) {
                $query->where('is_active', 1)
                    ->whereBetween('tanggal', [$startDate, $endDate]);
            })
            ->get();
        
        $updated = SubTask::whereIn('id', $subtasks->pluck('id'))
            ->update(['status' => 'approve']);
        
        foreach ($subtasks as $subtask) {
            event(new SubtaskStatusChanged($subtask->task));
        }

        if ($updated > 0) {
            return redirect()->back()->with('success', 'Laporan Kinerja berhasil di approve');
        }
       return redirect()->back()->with('error', 'Tidak ada Laporkan Kinerja untuk di approve');
    }
    public function approveSubtask($id)
    {
        $subtask = SubTask::where('id', $id)
            ->whereHas('detail_sub_task', function ($query){
                $query->where('is_active', 1);
            })
            ->first();
        
        if (!$subtask) {
            return redirect()->back()->with('error', 'Subtask tidak ditemukan');
        }

        $subtask->update(['status' => 'approve']);
        
        event(new SubtaskStatusChanged($subtask->task));

        
        if ($subtask->status == 'approve') {
            return redirect()->back()->with('success', 'Subtask berhasil di approve');
        }
       return redirect()->back()->with('error', 'Subtask tidak dapat di approve');
    }
    public function reviseLaporanKinerja(Request $request, $id)
    {
        $request->validate([
            'periode' => 'required|string',
            'pesan_revisi' => 'required|string'
        ]);
        $dates = explode('_', $request->periode);
        try {
            $startDate = Carbon::parse($dates[0])->startOfDay();
            $endDate = Carbon::parse($dates[1])->endOfDay();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Format periode tidak valid');
        }
        $revisi = RevisiLaporan::create([
            'user_id' => $id,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'pesan' => $request->pesan_revisi
        ]);
        
        $updated = SubTask::where('user_id', $id)
            ->whereHas('detail_sub_task', function($query) {
                $query->where('is_active', 1);
            })
            ->whereBetween('tgl_sub_task', [$startDate, $endDate])
            ->update([
                'status' => 'revise',
                'revisi_laporan_id' => $revisi->id
            ]);

        if ($updated > 0) {
            return redirect()->back()->with('success', 'Laporan Kinerja di revise');
        }
       return redirect()->back()->with('error', 'Gagal merevise');
    }
    public function reviseSubtask(Request $request, $id)
    {
        $request->validate(['pesan_revisi' => 'required|string']);

        $subtask = SubTask::with('task')->findOrFail($id);
        
        if ($subtask->revisi_laporan_id) {
            $revisiLama = $subtask->revisi_laporan_id;
            
            $subtask->revisi_laporan_id = null;
            $subtask->save();
            
            RevisiLaporan::where('id', $revisiLama)->delete();
        }

        $revisi = RevisiLaporan::create([
            'user_id' => $subtask->user_id,
            'start_date' => $subtask->tgl_sub_task,
            'end_date' => $subtask->tgl_selesai,
            'pesan' => $request->pesan_revisi
        ]);

        $subtask->status = 'revise';
        $subtask->revisi_laporan_id = $revisi->id;
        $subtask->save();

        event(new SubtaskStatusChanged($subtask->task));

        return redirect()->back()->with('success', 'Subtask berhasil di revisi');
    }
}
