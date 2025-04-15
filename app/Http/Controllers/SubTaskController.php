<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\SubTask;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\LampiranSubTask;

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
        $title = 'Laporan Kinerja';
        $getDataUser = User::where('id', auth()->user()->id)->first();
        $start = Carbon::createFromFormat('Y-m-d', '2025-01-01');
        $dates = collect();
    
        for ($i = 0; $i < 10; $i++) {
            $dates->push($start->copy()->addDays($i));
        }

        return view('karyawan.laporan_kinerja', compact('title', 'getDataUser', 'dates'));
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
    public function update(Request $request, SubTask $subTask)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SubTask $subTask)
    {
        //
    }
}
