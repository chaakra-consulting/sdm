<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\UsersTask;
use Illuminate\Http\Request;
use App\Models\ProjectPerusahaan;
use App\Models\SubTask;
use App\Models\TipeTask;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index()
    {    
        $title = 'List Task';
        $project = ProjectPerusahaan::all();
        $tasks = Task::all();
        $tipeTask = TipeTask::all();
        $users = User::all();
        $userTasks = UsersTask::where('user_id', Auth::user()->id)
            ->with([
                'task.tipe_task',
                'task.project_perusahaan',
                'task.users_task.user.socialMedias',
                'task.users_task.user.dataDiri.kepegawaian.subJabatan',
            ])
            ->get();
        return view('task.daftar_task', compact('title', 'tasks', 'tipeTask', 'users', 'project','userTasks'));
    }
    
    public function detail($id)
    {
        $title = 'Detail Task';
        $project = ProjectPerusahaan::all();
        $task = Task::find($id);
        $tipeTask = TipeTask::all();
        $user = UsersTask::where('task_id', $id)
            ->with([
                'user.socialMedias',
                'user.dataDiri.kepegawaian.subJabatan',
            ])
            ->get();
        $existingUserIds = UsersTask::where('task_id', $id)->pluck('user_id')->toArray();
        $users = User::whereNotIn('id', $existingUserIds)->get();
        $subTask = SubTask::where('task_id', $id)->with(['lampiran'])->get();


        return view('task.detail_task', compact('title', 'task', 'user', 'users', 'tipeTask', 'project', 'subTask'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'tipe_task' => 'nullable',
            'project_perusahaan_id' => 'nullable',
            'nama_task' => 'required',
            'keterangan' => 'nullable',
            'upload' => 'nullable|file|mimes:pdf,xls,xlsx,doc,docx,jpg,jpeg,png|max:5120',
            'user.*' => 'nullable',
        ]);

        $tipeTask = TipeTask::where('slug', $request->tipe_task)->first();
        if (!$tipeTask) {
            return redirect()->back()->with('error', 'Tipe Task tidak ditemukan!.');
        }
        
        $uploadPath = null;
        
        if($request->hasFile('upload')){
            $file = $request->file('upload');
            $fileName = uniqid() . "_task_" . auth()->user()->name . "_" . time(). "." . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $fileName);
            $uploadPath = $fileName;
        }

        $data = [
            'tipe_tasks_id' => $tipeTask->id,
            'project_perusahaan_id' => $request->project_perusahaan_id,
            'tgl_task' => now()->format('Y-m-d'),
            'user_id' => Auth::user()->id,
            'nama_task' => $request->nama_task,
            'keterangan' => $request->keterangan,
            'upload' => $uploadPath,
        ];

        $task = Task::create($data);

        if (Auth::check() && Auth::user()->role->slug == 'manager') {
            foreach ($request->user as $user_id) {
                UsersTask::create([
                    'task_id' => $task->id,
                    'user_id' => $user_id,
                ]);
            }            
        } else {
            UsersTask::create([
                'task_id' => $task->id,
                'user_id' => Auth::user()->id,
            ]);
        } 
        return redirect()->back()->with('success', 'Task berhasil di tambahkan');
    }
    
    public function update(Request $request, $id)
    {
        $task = Task::find($id);

        $request->validate([
            'tipe_task' => 'nullable',
            'project_perusahaan_id' => 'nullable',
            'nama_task' => 'required',
            'keterangan' => 'required',
            'upload' => 'nullable|file|mimes:pdf,xls,xlsx,doc,docx,jpg,jpeg,png,gif|max:5120',
            'user_id' => 'required',    
            'user.*' => 'required' 
        ]);

        $tipeTask = TipeTask::where('slug', $request->tipe_task)->first();
        if (!$tipeTask) {
            return redirect()->back()->with('error', 'Tipe Task tidak ditemukan!.');
        }

        $task->fill($request->except('upload'));

        if ($request->hasFile('upload')) {
            if ($task->upload) {
                $oldPhotoPath = public_path('uploads/' . $task->upload);
                if (file_exists($oldPhotoPath)) {
                    unlink($oldPhotoPath);
                }
            }
            $file = $request->file('upload');
            $filename = uniqid() . '_task_' . auth()->user()->name . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            $task->upload = $filename;
        }
        
        $data = [
            'tipe_tasks_id' => $tipeTask->id,
            'nama_project' => $request->project_perusahaan_id,
            'user_id' => $request->user_id,
            'nama_task' => $request->nama_task,
            'keterangan' => $request->keterangan,
            'upload' => $task->upload,
            'tgl_task' => now()->format('Y-m-d'),
        ];

        $task->update($data);
        return redirect()->back()->with('success', 'Task berhasil di update');
    }
    public function updateUserTask(Request $request)
    {
        $request->validate([
            'user.*' => 'required',
            'task_id' => 'required',
        ]);
        if (empty($request->user)) {
            return redirect()->back()->with('error', 'Tidak ada anggota yang dipilih.');
        }
        $tambahUser = 0;
        foreach ($request->user as $userId) {
            $existing = UsersTask::where('user_id', $userId)
                ->where('task_id', $request->task_id)
                ->first();
            if (!$existing) {
                UsersTask::create([
                    'user_id' => $userId,
                    'task_id' => $request->task_id,
                ]);
                $tambahUser++;
            }
        }
        $message = $tambahUser > 0 
        ? "$tambahUser anggota berhasil ditambahkan."
        : "Semua anggota sudah terdaftar di task ini.";

        return redirect()->back()->with('success', $message);
    }

    public function updateLampiran(Request $request, $id)
    {
        $task = Task::find($id);

        $request->validate([
            'upload' => 'nullable|file|mimes:pdf,xls,xlsx,doc,docx,jpg,jpeg,png,gif|max:5120',
        ]);

        if ($request->hasFile('upload')) {
            if ($task->upload) {
                $oldPhotoPath = public_path('uploads/' . $task->upload);
                if (file_exists($oldPhotoPath)) {
                    unlink($oldPhotoPath);
                }
            }
            $file = $request->file('upload');
            $filename = uniqid() . '_task_' . auth()->user()->name . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            $task->upload = $filename;
        }

        $task->save();

        return redirect()->back()->with('success', 'Lampiran berhasil di update');
    }

    public function destroy($id)
    {
        $task = Task::find($id);
        $task->delete();

        return redirect()->back()->with('success', 'Task berhasil di Hapus');
    }
    
    public function destroyUserTask($id)
    {
        $deleted = UsersTask::where('id', $id)
            ->delete();

        if ($deleted) {
            return back()->with('success', 'Anggota berhasil dihapus dari Task.');
        } else {
            return back()->with('error', 'Gagal menghapus Anggota Task.');
        }
    }
}