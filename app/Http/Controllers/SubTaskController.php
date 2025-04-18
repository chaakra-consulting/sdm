<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\SubTask;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\LampiranSubTask;
use App\Models\Task;

class SubTaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'task_id' => 'required|exists:tb_tasks,id',
            'user_id' => 'required|exists:users,id',
            'tanggal' => 'required|date',
            'durasi_jam' => 'nullable|integer',
            'durasi_menit' => 'nullable|integer',
            'keterangan' => 'nullable|string',
            'upload' => 'nullable',
            'upload.*' => 'file|mimes:pdf,xls,xlsx,doc,docx,jpg,jpeg,png,gif|max:5120',
        ]);
        $subTask = SubTask::create([
            'task_id' => $request->task_id,
            'user_id' => $request->user_id,
            'tgl_sub_task' => $request->tanggal,
            'durasi' => ($request->durasi_jam * 60) + $request->durasi_menit,
            'keterangan' => $request->keterangan,
        ]);

        if ($request->hasFile('upload')) {
            foreach ($request->file('upload') as $file) {
                $fileName = uniqid() . '_lampiran_' . auth()->user()->name . '_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads'), $fileName);

                LampiranSubTask::create([
                    'sub_task_id' => $subTask->id,
                    'lampiran' => $fileName,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Sub Task berhasil di tambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(SubTask $subTask)
    {
        $getSubtask = SubTask::with('task', 'task.tipe_task', 'lampiran')->find($subTask->id);
        $task = Task::all();
        $title = 'Laporan Kinerja';
        $getDataUser = User::where('id', auth()->user()->id)->first();
        $today = Carbon::now();

        if ($today->day < 26) {
            $startDate = $today->copy()->subMonth()->day(26);
            $endDate = $today->copy()->day(25);
        } else {
            $startDate = $today->copy()->day(26);
            $endDate = $today->copy()->addMonth()->day(25);
        }

        $dates = collect();
        $current = $startDate->copy();
        while ($current <= $endDate) {
            $dates->push([
                'date' => $current->copy(),
                'is_today' => $current->isSameDay($today),
            ]);
            $current->addDay();
        }
        return view('karyawan.laporan_kinerja', compact('title', 'getDataUser', 'dates', 'startDate', 'endDate', 'today', 'task', 'getSubtask'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SubTask $subTask)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $subtask = SubTask::find($id);
        $request->validate([
            'task_id' => 'required|exists:tb_tasks,id',
            'user_id' => 'required|exists:users,id',
            'tanggal' => 'required|date',
            'durasi_jam' => 'nullable|integer',
            'durasi_menit' => 'nullable|integer',
            'keterangan' => 'nullable|string',
            'upload' => 'nullable',
            'upload.*' => 'file|mimes:pdf,xls,xlsx,doc,docx,jpg,jpeg,png,gif|max:5120',
        ]);

        $subtask->task_id = $request->task_id;
        $subtask->user_id = $request->user_id;
        $subtask->tgl_sub_task = $request->tanggal;
        $subtask->durasi = ($request->durasi_jam * 60) + $request->durasi_menit;
        $subtask->keterangan = $request->keterangan;
        $subtask->save();

        if ($request->hasFile('upload')) {
            foreach ($request->file('upload') as $file) {
                $fileName = uniqid() . '_lampiran_' . auth()->user()->name . '_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads'), $fileName);

                LampiranSubTask::create([
                    'sub_task_id' => $subtask->id,
                    'lampiran' => $fileName,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Sub Task berhasil di update');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $lampiranSubTask = LampiranSubTask::where('sub_task_id', $id)->get();
        foreach ($lampiranSubTask as $lampiran) {
            $filePath = public_path('uploads/' . $lampiran->lampiran);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            $lampiran->delete();
        }
        $subtask = SubTask::find($id);
        if ($subtask) {
            $subtask->delete();
            return redirect()->back()->with('success', 'Sub Task berhasil di hapus');
        } else {
            return redirect()->back()->with('error', 'Sub Task tidak ditemukan');
        }
    }


    public function getDataByDate(Request $request)
    {
        $tanggal = $request->input('tanggal');
        $subtasks = SubTask::with('task', 'task.tipe_task', 'lampiran')
            ->whereDate('tgl_sub_task', $tanggal)
            ->get();

        $data = $subtasks->map(function ($subtask) {
            return [
                'id' => $subtask->id,
                'task_id' => $subtask->task_id,
                'user_id' => $subtask->user_id,
                'tgl_sub_task' => $subtask->tgl_sub_task,
                'durasi' => $subtask->durasi,
                'keterangan' => $subtask->keterangan,
                'nama_task' => $subtask->task->nama_task,
                'nama_tipe' => $subtask->task->tipe_task->nama_tipe,
                'lampiran' => $subtask->lampiran->isNotEmpty() ? $subtask->lampiran->map(function ($lampiran) {
                    return [
                        'lampiran' => $lampiran->lampiran,
                    ];
                }) : [],
            ];
        });

        return response()->json([
            'data' => $data
        ]);
    }
}
