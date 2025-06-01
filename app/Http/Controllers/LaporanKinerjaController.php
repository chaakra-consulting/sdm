<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\SubTask;
use App\Models\HariLibur;
use App\Models\UsersTask;
use Illuminate\Http\Request;
use App\Models\DetailSubTask;
use App\Models\LampiranSubTask;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;

class LaporanKinerjaController extends Controller
{
    public function show(SubTask $subTask, Request $request)
    {
        $title = 'Laporan Kinerja';
        Carbon::setLocale('id');
        $subtasks = SubTask::where('user_id', auth()->user()->id)
            ->with([
                'task',
                'task.tipe_task',
                'lampiran',
                'detail_sub_task'])
            ->get();
        $today = Carbon::now();
        $getDataUser = User::where('id', auth()->user()->id)->first();        
        $selectedMonth = $request->input('month', $today->month);
        $selectedYear = $request->input('year', $today->year);

        $months = collect(range(1, 12))->mapWithKeys(function ($month) {
            $date = Carbon::create()->month($month);
            return [$month => $date->translatedFormat('F')];
        });
        $validator = Validator::make($request->all(), [
            'month' => 'sometimes|integer|between:1,12',
            'year' => 'sometimes|integer|min:2000|max:2100'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        };
        if (!$request->has('month') && $today->day >= 26) {
            $nextMonth = $today->copy()->addMonth();
            $selectedMonth = $nextMonth->month;
            $selectedYear = $nextMonth->year;
        }
        // $startDate = Carbon::create($selectedYear, $selectedMonth, 1)
        //     ->subMonth()
        //     ->day(26);
        // $endDate = Carbon::create($selectedYear, $selectedMonth, 25);
        $startDate = Carbon::create($selectedYear, $selectedMonth, 26)
            ->subMonth();
        $endDate = Carbon::create($selectedYear, $selectedMonth, 25);
        if ($selectedMonth < $today->month && $selectedYear == $today->year) {
            $startDate->addMonth();
            $endDate->addMonth();
        }
        if ($selectedMonth == $today->month && $selectedYear == $today->year && $today->day >= 26) {
            $startDate->addMonth();
            $endDate->addMonth();
        }
        $dates = collect();
        $detailSubtasks = DetailSubTask::where('user_id', auth()->id())
        ->whereBetween('tanggal', [$startDate, $endDate])
        ->with([
            'subtask.task.tipe_task',
            'subtask.lampiran'
            ])
            ->get();
            $datesData = $detailSubtasks->groupBy(function ($item) {
                return Carbon::parse($item->tanggal)->format('Y-m-d');
            })->map(function ($group) {
                return [
                    'total_durasi' => $group->sum('durasi'),
                    'jumlah_task' => $group->unique('sub_task_id')->count()
                ];
            });
        $current = $startDate->copy();
        $activeFound = false;
        $hariLibur = HariLibur::whereYear('tanggal', $selectedYear)
            ->whereMonth('tanggal', $selectedMonth)
            ->pluck('tanggal')
            ->map(function ($date) {
                return Carbon::parse($date)->format('Y-m-d');
            })
            ->toArray();
        $filterDate = $selectedMonth != date('m') || $selectedYear != date('Y');

        while ($current <= $endDate) {
            $dateString = $current->format('Y-m-d');
            $data = $datesData->get($dateString, [
                'total_durasi' => 0,
                'jumlah_task' => 0
            ]);
            $isSunday = $current->isSunday();
            $isHoliday = in_array($dateString, $hariLibur);
            $isWorkingDay = !$isSunday && !$isHoliday;
            $isToday = $current->isSameDay($today);
            if ($isWorkingDay) {
                $data = $datesData->get($dateString, [
                    'total_durasi' => 0,
                    'jumlah_task' => 0
                ]);
                $dates->push([
                    'date' => $current->copy(),
                    'is_today' => $isToday,
                    'is_first_date' => $current->isSameDay($startDate),
                    'total_durasi' => $data['total_durasi'],
                    'jumlah_task' => $data['jumlah_task'],
                    'filter_date' => $filterDate,
                    'is_working_day' => true,
                    'is_active' => $isToday
                ]);
            }
            $current->addDay();
        }

        return view('karyawan.laporan_kinerja', compact(
            'title',
            'getDataUser',
            'dates',
            'startDate',
            'endDate',
            'today',
            'subtasks',
            'detailSubtasks',
            'selectedMonth',
            'selectedYear',
            'months',
            'filterDate'
        ));
    }
    public function detail($id, Request $request)
    {
        $title = 'Detail Laporan Kinerja';
        Carbon::setLocale('id');
        $getDataUser = User::where('id', auth()->user()->id)->first();
        $selectedMonth = $request->has('month') ? (int)$request->month : (int)date('m');
        $selectedYear = $request->has('year') ? (int)$request->year : (int)date('Y');
        $selectedMonth = (int)$selectedMonth;
        $selectedYear = (int)$selectedYear;
        $months = collect(range(1, 12))->mapWithKeys(function ($month) {
            $date = Carbon::create()->month($month);
            return [$month => $date->translatedFormat('F')];
        });
        $validator = Validator::make(request()->all(), [
            'month' => 'sometimes|integer|between:1,12',
            'year' => 'sometimes|integer|min:2000|max:2100'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        };
        $today  = Carbon::now();
        if ($today->day >= 26) {
            $selectedMonth = $today->copy()->addMonth()->month;
            $selectedYear = $today->copy()->addMonth()->year;
        } else {
            $selectedMonth = $today->month;
            $selectedYear = $today->year;
        }

        $selectedMonth = $request->has('month') ? (int)$request->month : $selectedMonth;
        $selectedYear = $request->has('year') ? (int)$request->year : $selectedYear;

        $startDate = Carbon::create($selectedYear, $selectedMonth, 1)
            ->subMonth()
            ->day(26)
            ->startOfDay();
        $endDate = Carbon::create($selectedYear, $selectedMonth, 25)
            ->endOfDay();
        $dates = collect();
        $detailSubtasks = DetailSubTask::where('user_id', $id)
            ->where('is_active', 1)
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->with([
                'subtask.task.tipe_task',
                'subtask.lampiran',
                'subtask.revisi',
            ])
            ->get();
        $datesData = $detailSubtasks->groupBy(function ($item) {
            return Carbon::parse($item->tanggal)->format('Y-m-d');
        });
        $current = $startDate->copy();
        while ($current <= $endDate) {
            $dateString = $current->format('Y-m-d');
            $date = $datesData->get($dateString, collect());

            $dates->push([
                'date' => $current->copy(),
                'is_today' => $current->isSameDay($today),
            ]);
            $current->addDay();
        }

        $totalDurasiMenit = $detailSubtasks->sum('durasi');
        $totalDurasiJam = floor($totalDurasiMenit / 60);
        $totalDurasiSisaMenit = $totalDurasiMenit % 60;
        $totalDurasiPerhari = floor($totalDurasiJam / 8);
        $totalDurasiSisaJam = $totalDurasiJam % 8;

        return view('karyawan.detail_laporan_kinerja', compact(
            'title',
            'id',
            'getDataUser',
            'startDate',
            'endDate',
            'today',
            'detailSubtasks',
            'totalDurasiMenit',
            'totalDurasiJam',
            'totalDurasiSisaMenit',
            'totalDurasiPerhari',
            'totalDurasiSisaJam',
            'months',
            'selectedMonth',
            'selectedYear',
        ));
    }
    public function getDataByDate(Request $request)
    {
        $tanggal = $request->input('tanggal');
        $detailSubtasks = DetailSubTask::with([
                'subtask.task.tipe_task',
                'subtask.lampiran',
                'subtask.task.project_perusahaan.perusahaan'
            ])
            ->whereDate('tanggal', $tanggal)
            ->where('user_id', auth()->user()->id)
            ->get();
            
        $data = $detailSubtasks->map(function ($detailSubtask) {
            $subtask = $detailSubtask->subtask;
            return [
                'id' => $detailSubtask->id,
                'nama_subtask' => $subtask->nama_subtask ?? '-',
                'nama_task' => $subtask->task->nama_task ?? '-',
                'nama_tipe' => $subtask->task->tipe_task->nama_tipe ?? '-',
                'tanggal' => $detailSubtask->tanggal,
                'durasi' => $detailSubtask->durasi,
                'keterangan' => $detailSubtask->keterangan,
                'is_active' => $detailSubtask->is_active,
                'lampiran' => $subtask->lampiran->map(function ($lampiran) {
                    return ['lampiran' => $lampiran->lampiran];
                })
            ];
        });

        return response()->json(['data' => $data]);
    }
    public function kirim(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date'
        ]);

        try {
            $affectedRows = DetailSubTask::where('user_id', $id)
                ->whereDate('tanggal', $request->tanggal)
                ->where('is_active', 0)
                ->update(['is_active' => 1]);

            if ($affectedRows > 0) {
                return redirect()->back()->with('success', 'Berhasil mengirim ' . $affectedRows . ' Sub Task!');
            }

            return redirect()->back()->with('error', 'Tidak ada Sub Task untuk dikirim pada tanggal ini');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengirim Sub Task: ' . $e->getMessage());
        }
    }
    public function batal(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date'
        ]);

        try {
            $affectedRows = DetailSubTask::where('user_id', $id)
                ->whereDate('tanggal', $request->tanggal)
                ->where('is_active', 1)
                ->update(['is_active' => 0]);

            if ($affectedRows > 0) {
                return redirect()->back()->with('success', 'Batal mengirim ' . $affectedRows . ' Sub Task!');
            }

            return redirect()->back()->with('error', 'Tidak ada Sub Task untuk dibatalkan pada tanggal ini');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membatalkan Sub Task: ' . $e->getMessage());
        }
    }
    public function store(Request $request)
    {
        $request->validate([
            'sub_task_id' => 'required|exists:sub_tasks,id',
            'user_id' => 'required|exists:users,id',
            'tanggal' => 'required|date',
            'keterangan' => 'required|string',
            'durasi_jam' => 'required|integer',
            'durasi_menit' => 'required|integer',
        ]);
        
        $data = [
            'sub_task_id' => $request->sub_task_id,
            'user_id' => $request->user_id,
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan,
            'durasi' => ($request->durasi_jam * 60) + $request->durasi_menit,
        ];

        DetailSubTask::create($data);
        return redirect()->back()->with('success', 'Laporan Kinerja berhasil dikirim');
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'sub_task_id' => 'required|exists:sub_tasks,id',
            'user_id' => 'required|exists:users,id',
            'tanggal' => 'required|date',
            'keterangan' => 'required|string',
            'durasi_jam' => 'required|integer',
            'durasi_menit' => 'required|integer',
        ]);

        $detail = DetailSubTask::where('id', $id)
            ->where('sub_task_id', $request->sub_task_id)
            ->firstOrFail();

        $data = [
            'sub_task_id' => $request->sub_task_id,
            'user_id' => $request->user_id,
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan,
            'durasi' => ($request->durasi_jam * 60) + $request->durasi_menit,
        ];
        $detail->update($data);

        return redirect()->back()->with('success', 'Update Pekerjaan berhasil di edit');
    }
    public function destroy($id)
    {
        $detail = DetailSubTask::findOrFail($id);
        $detail->delete();
        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }
}
