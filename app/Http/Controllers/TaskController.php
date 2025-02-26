<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'project_perusahaan_id' => 'required',
            'nama_task' => 'required',
            'tgl_task' => 'required',
            'keterangan' => 'nullable',
            'upload' => 'nullable',
            'upload.*' => 'mimes:jpeg,png,jpg,pdf|max:3072',
        ]);
        
        $existingFilesCount = Task::whereDate('tgl_task', $request->tgl_task)
            ->whereNotNull('upload')
            ->get()
            ->sum(function ($task) {
                return is_array(json_decode($task->upload, true)) ? count(json_decode($task->upload, true)) : 0;
            });
        $uploadedFiles = [];
        
        if ($request->hasFile('upload')) {
            $files = is_array($request->file('upload')) ? $request->file('upload') : [$request->file('upload')];
            foreach ($files as $index => $file) {
                $fileNumber = str_pad($existingFilesCount + $index + 1, 2, '0', STR_PAD_LEFT);
                $fileName = "Task{$fileNumber}_" . time() . "_" . $file->getClientOriginalName();
                $file->storeAs('uploads', $fileName, 'public');
                $uploadedFiles[] = $fileName;
            }
        }
        
        Task::create([
            'project_perusahaan_id' => $request->project_perusahaan_id,
            'user_id' => Auth::id(),
            'nama_task' => $request->nama_task,
            'tgl_task' => $request->tgl_task,
            'keterangan' => $request->keterangan,
            'upload' => !empty($uploadedFiles) ? json_encode($uploadedFiles) : null,
        ]);

        return redirect()->back()->with('success', 'Task Project berhasil di tambahkan');
    }
}
