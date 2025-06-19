<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\SubTask;
use Illuminate\Http\Request;
use App\Models\DetailSubTask;
use Illuminate\Support\Carbon;
use App\Models\LampiranSubTask;
use Illuminate\Support\Facades\Auth;

class SubTaskController extends Controller
{
    public function show()
    {
        $title = "Sub Task";
        $userSubtasks = SubTask::where('user_id', Auth::id())
            ->with([
                'detail_sub_task', 
                'revisi', 
                'task.tipe_task', 
                'task.project_perusahaan.perusahaan', 
                'lampiran'])
            ->get();
        $subtasks = SubTask::with([
            'detail_sub_task', 
            'revisi', 
            'task.tipe_task', 
            'task.project_perusahaan.perusahaan', 
            'lampiran'])
            ->get();
        $tasks = Task::with(
            'users_task',
            'tipe_task',
            'project_perusahaan',
            'project_perusahaan.perusahaan'
            )->get();

        return view('subtask.daftar_subtask', compact('title', 'subtasks', 'userSubtasks', 'tasks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'task_id' => 'required',
            'user_id' => 'required|exists:users,id',
            'nama_subtask' => 'required|',
            'tanggal' => 'required|date',
            'deadline' => 'nullable|date',
            'upload' => 'nullable',
            'upload.*' => 'nullable|file|mimes:pdf,xls,xlsx,doc,docx,jpg,jpeg,png,gif|max:5120',
        ]);

        $subTask = SubTask::create([
            'nama_subtask' => $request->nama_subtask,
            'task_id' => $request->task_id,
            'user_id' => $request->user_id,
            'tgl_sub_task' => $request->tanggal,
            'deadline' => $request->deadline,
        ]);

        if ($request->hasFile('upload')) {
            foreach ($request->file('upload') as $file) {
                $fileName = uniqid() . '_lampiran_' . Auth::user()->name . '_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads'), $fileName);

                LampiranSubTask::create([
                    'sub_task_id' => $subTask->id,
                    'lampiran' => $fileName,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Sub Task berhasil di tambahkan');
    }

    public function detail(Request $request, $id)
    {
        $title = "Detail Sub Task";
        $subtask = SubTask::where('id', $id)
            ->with(
                'task', 
                'user', 
                'lampiran', 
                'detail_sub_task')
            ->firstOrFail();
        $subtaskManager = SubTask::where('id', $id)
            ->with([
                'task', 
                'user', 
                'lampiran', 
                'detail_sub_task' => function ($query) {
                    $query->where('is_active', 1);
                }
            ])
            ->firstOrFail();
        
        return view('subtask.detail_subtask', compact('title', 'subtask', 'subtaskManager'));
    }

    public function update(Request $request, $id)
    {
        $subtask = SubTask::find($id);
        $request->validate([
            'task_id' => 'required|exists:tb_tasks,id',
            'user_id' => 'required|exists:users,id',
            'nama_subtask' => 'required|string',
            'tanggal' => 'required|date',
            'deadline' => 'nullable|date',
            'upload' => 'nullable',
            'upload.*' => 'file|mimes:pdf,xls,xlsx,doc,docx,jpg,jpeg,png,gif|max:5120',
        ]);

        $subtask->task_id = $request->task_id;
        $subtask->user_id = $request->user_id;
        $subtask->nama_subtask = $request->nama_subtask;
        $subtask->deadline = $request->deadline;
        $subtask->tgl_sub_task = $request->tanggal;
        $subtask->save();

        if ($request->hasFile('upload')) {
            $existingLampiran = LampiranSubTask::where('sub_task_id', $subtask->id)->get();
            foreach ($existingLampiran as $lampiran) {
                $filePath = public_path('uploads/' . $lampiran->lampiran);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
                $lampiran->delete();
            }

            foreach ($request->file('upload') as $file) {
                $fileName = uniqid() . '_lampiran_' . Auth::user()->name . '_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads'), $fileName);

                LampiranSubTask::create([
                    'sub_task_id' => $subtask->id,
                    'lampiran' => $fileName,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Sub Task berhasil di update');
    }

    public function updateDetail(Request $request, $id)
    {
        $subtask = SubTask::find($id);
        $request->validate([
            'task_id' => 'required|exists:tb_tasks,id',
            'user_id' => 'required|exists:users,id',
            'nama_subtask' => 'required|string',
            'tgl_sub_task' => 'required|date',
            'tgl_selesai' => 'nullable|date',
            'deadline' => 'nullable|date',
        ]);

        $subtask->task_id = $request->task_id;
        $subtask->user_id = $request->user_id;
        $subtask->nama_subtask = $request->nama_subtask;
        $subtask->deadline = $request->deadline;
        $subtask->tgl_sub_task = $request->tgl_sub_task;
        $subtask->tgl_selesai = $request->tgl_selesai;
        $subtask->save();

        return redirect()->back()->with('success', 'Sub Task berhasil di update');
    }

    public function updateDetailLampiran(Request $request, $id)
    {
        $subtask = SubTask::find($id);
        $request->validate([
            'upload' => 'nullable',
            'upload.*' => 'file|mimes:pdf,xls,xlsx,doc,docx,jpg,jpeg,png,gif|max:5120',
        ]);

        if ($request->hasFile('upload')) {
            foreach ($request->file('upload') as $file) {
                $fileName = uniqid() . '_lampiran_' . Auth::user()->name . '_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads'), $fileName);

                LampiranSubTask::create([
                    'sub_task_id' => $subtask->id,
                    'lampiran' => $fileName,
                ]);
            }
        }
        $subtask->save();

        return redirect()->back()->with('success', 'Lampiran berhasil di update');
    }

    public function kirim($id)
    {
        $updated = DetailSubTask::where('sub_task_id', $id)
            ->where('user_id', Auth::user()->id)            ->where('is_active', 0)
            ->update(['is_active' => 1]);
        if ($updated > 0) {
            return redirect()->back()->with('success', 'Laporan Kinerja berhasil di kirim');
        }
       return redirect()->back()->with('error', 'Tidak ada Laporkan Kinerja untuk di kirim');
    }
    public function batal($id)
    {
        $updated = DetailSubTask::where('sub_task_id', $id)
            ->where('user_id', Auth::user()->id)            ->where('is_active', 1)
            ->update(['is_active' => 0]);
        if ($updated > 0) {
            return redirect()->back()->with('success', 'Laporan Kinerja batal di kirim');
        }
       return redirect()->back()->with('error', 'Laporkan Kinerja tidak dapat dibatalkan');
    }
    
    public function destroy($id)
    {
        $Subtask = SubTask::find($id);
        if (!$Subtask) {
            return redirect()->back()->with('error', 'Subtask tidak ditemukan.');
        }
        $Subtask->delete();
        return redirect()->back()->with('success', 'Subtask berhasil dihapus.');
    }

    public function destroyLampiran($id)
    {
        $lampiran = LampiranSubTask::findOrFail($id);
        if($lampiran->sub_task->user_id != Auth::user()->id) {
            abort(403);
        }
        $filePath = public_path('uploads/' . $lampiran->lampiran);
        if(file_exists($filePath)) {
            unlink($filePath);
        }
        $lampiran->delete();
        
        return redirect()->back()->with('success', 'Lampiran berhasil dihapus.');
    }
}
