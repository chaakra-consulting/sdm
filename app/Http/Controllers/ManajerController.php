<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\HariLibur;
use App\Models\Perusahaan;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\DetailSubTask;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ManajerController extends Controller
{
    private function hariKerja($start, $end)
    {
        $cacheKey = "hari_kerja_{$start}_{$end}";

        return Cache::remember($cacheKey, 3600, function () use ($start, $end){
            $libur = HariLibur::whereBetween('tanggal', [$start, $end])
                ->pluck('tanggal')
                ->map(function($tgl){
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
        });
    }

    public function index()
    {
        $title = 'Dashboard';

        return view('manajer.index', compact('title'));
    }

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
            ->with([
                'task' => function ($query) {
                    $query->select('id', 'nama_task', 'project_perusahaan_id', 'tipe_tasks_id');
                },
                'task.project_perusahaan' => function ($query) {
                    $query->select('id', 'nama_project', 'perusahaan_id');
                },
                'task.tipe_task' => function ($query) {
                    $query->select('id', 'nama_tipe');
                },
                'lampiran' => function ($query) {
                    $query->select('id', 'sub_task_id');
                },
                'detail_sub_task' => function ($query) {
                    $query->select('id', 'sub_task_id', 'tanggal', 'durasi', 'status');
                }
            ])
            ->select('id', 'nama_subtask', 'task_id', 'user_id', 'status')
            ->where('user_id', $id)
            ->paginate(20);

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
        
        $getDataLaporan = DetailSubTask::query()
            ->with([
                'subtask.task.tipe_task' => function ($query) {
                    $query->select('id', 'nama_tipe');
                },
                
                'subtask.task.project_perusahaan' => function ($query) {
                    $query->select('id', 'nama_project', 'perusahaan_id'); 
                },
                
                'subtask.task.project_perusahaan.perusahaan' => function ($query) {
                    $query->select('id', 'nama_perusahaan'); 
                },
                
                'subtask.lampiran' => function ($query) {
                    $query->select('id', 'sub_task_id');
                }
            ])
            ->whereHas('subtask', function ($query) use ($id) {
                $query->where('user_id', $id);
            })
            ->where('is_active', 1)
            ->when($filterTanggal, function ($q) use ($filterTanggal) {
                return $q->whereBetween('tanggal', $filterTanggal);
            })
            ->orderBy('tanggal', 'asc')
            ->orderBy('created_at', 'asc')
            ->get();
            
        $totalDurasiMenit = $getDataLaporan->sum('durasi');
        
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

    public function approveDetailSubTask(Request $request, $id)
    {
        $request->validate([
            'approval_notes' => 'nullable|string|max:500'
        ]);

        try {
            $detailSubTask = DetailSubTask::findOrFail($id);
            if (Auth::user()->role->slug !== 'manager') {
                return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk menyetujui laporan kinerja.');
            }

            $detailSubTask->update([
                'status' => 'approved',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
                'approval_notes' => $request->approval_notes,
            ]);

            return redirect()->back()->with('success', 'Laporan kinerja berhasil disetujui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyetujui laporan kinerja: ' . $e->getMessage());
        }
    }

    public function rejectDetailSubTask(Request $request, $id)
    {
        $request->validate([
            'approval_notes' => 'required|string|max:500'
        ]);

        try {
            $detailSubTask = DetailSubTask::findOrFail($id);

            if (Auth::user()->role->slug !== 'manager') {
                return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk menolak laporan kinerja.');
            }
            
            $detailSubTask->update([
                'status' => 'rejected',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
                'approval_notes' => $request->approval_notes,
            ]);

            $this->kirimNotifikasi($detailSubTask, 'ditolak', $request->approval_notes);

            return redirect()->back()->with('success', 'Laporan kinerja berhasil ditolak.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menolak laporan kinerja: ' . $e->getMessage());
        }
    }

    public function reviseDetailSubTask(Request $request, $id)
    {
        $request->validate([
            'approval_notes' => 'required|string|max:500',
        ]);

        try {
            $detailSubTask = DetailSubTask::findOrFail($id);
            if (Auth::user()->role->slug !== 'manager') {
                return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk meminta revisi laporan kinerja.');
            }
            
            $detailSubTask->update([
                'status' => 'revise',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
                'approval_notes' => $request->approval_notes,
            ]);
            
            $this->kirimNotifikasi($detailSubTask, 'perlu direvisi', $request->approval_notes, 'laporan_kinerja_revised');

            return redirect()->back()->with('success', 'Permintaan revisi laporan kinerja berhasil dikirim.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal meminta revisi laporan kinerja: ' . $e->getMessage());
        }
    }
    
    public function bulkApproveDetailSubTask(Request $request)
    {
        $request->validate(['detail_ids' => 'required|string']);
        $detailIds = explode(',', $request->detail_ids);

        if (Auth::user()->role->slug !== 'manager') {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk menyetujui laporan kinerja.');
        }

        try {
            $details = DetailSubTask::whereIn('id', $detailIds)
                ->where('status', 'submitted')->get();
                
            foreach ($details as $detail) {
                $detail->update([
                    'status' => 'approved',
                    'approved_by' => Auth::id(),
                    'approved_at' => now(),
                    'approval_notes' => $request->approval_notes,
                ]);
            }

            return redirect()->back()->with('success', count($details) . ' laporan kinerja berhasil disetujui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyetujui laporan kinerja: ' . $e->getMessage());
        }
    }

    public function bulkRejectDetailSubTask(Request $request)
    {
        $request->validate(['detail_ids' => 'required|string',]);
        $detailIds = explode(',', $request->detail_ids);

        if (Auth::user()->role->slug !== 'manager') {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk menolak laporan kinerja.');
        }

        try {
            $details = DetailSubTask::whereIn('id', $detailIds)->where('status', 'submitted')->get();
            foreach ($details as $detail) {
                $detail->update([
                    'status' => 'rejected',
                    'approved_by' => Auth::id(),
                    'approved_at' => now(),
                    'approval_notes' => $request->approval_notes,
                ]);
                $this->kirimNotifikasi($detail, 'ditolak', $request->approval_notes);   
            }

            return redirect()->back()->with('success', $details->count() . ' laporan kinerja berhasil ditolak.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menolak laporan kinerja: ' . $e->getMessage());
        }
    }

    public function bulkReviseDetailSubTask(Request $request)
    {
        $request->validate(['detail_ids' => 'required|string']);
        $detailIds = explode(',', $request->detail_ids);

        if (Auth::user()->role->slug !== 'manager') {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk meminta revisi laporan kinerja.');
        }

        try {
            $details = DetailSubTask::whereIn('id', $detailIds)->where('status', 'submitted')->get();
            foreach ($details as $detail) {
                $detail->update([
                    'status' => 'revise',
                    'approved_by' => Auth::id(),
                    'approved_at' => now(),
                    'approval_notes' => $request->approval_notes,
                ]);
                $this->kirimNotifikasi($detail, 'perlu direvisi', $request->approval_notes, 'laporan_kinerja_revised');
            }

            return redirect()->back()->with('success', $details->count() . ' laporan kinerja berhasil diminta revisi.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal meminta revisi laporan kinerja: ' . $e->getMessage());
        }
    }

    private function kirimNotifikasi($detail, $msg, $notes, $type = 'laporan_kinerja_rejected') {
        $karyawan = $detail->user;

        $namaProject = '-';

        if ($detail->subtask && $detail->subtask->task && $detail->subtask->task->project_perusahaan) {
            $namaProject = $detail->subtask->task->project_perusahaan->nama_project;
        }

        Notification::create([
            'type' => $type, 
            'notifiable_type' => User::class,
            'notifiable_id' => $karyawan->id,
            'data' => [
                'message'           => 'Laporan Kinerja Anda ' . $msg,
                'detail_subtask_id' => $detail->id,
                'tanggal'           => $detail->tanggal,
                'subtask'           => $detail->subtask->nama_subtask,
                'project'           => $namaProject,
                'task_induk'        => $detail->subtask->task->nama_task,
                'approved_by'       => Auth::user()->name,
                'notes'             => $notes,
                'action_url'        => route('karyawan.laporan_kinerja', ['tanggal' => $detail->tanggal])
            ] 
        ]);
    }

    public function pendingApprovals()
    {
        $title = 'Pending Approvals';
        $getDataLaporan = DetailSubTask::with([
            'user',
            'subtask.task.project_perusahaan'
        ])
        ->where('status', 'submitted')
        ->where('is_active', 1)
        ->orderBy('created_at', 'desc')
        ->paginate(10);

        return view('manajer.laporan_kinerja.pending', compact('title', 'getDataLaporan'));
    }
}
