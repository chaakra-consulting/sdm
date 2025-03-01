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
            'keterangan' => 'nullable',
            'upload' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,gif|max:5120',
        ]);
        
        $uploadPath = null;
        
        if($request->hasFile('upload')){
            $file = $request->file('upload');
            $fileName = uniqid() . "_task_" . time(). "." . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $fileName);
            $uploadPath = $fileName;
        }

        $data = [
            'project_perusahaan_id' => $request->project_perusahaan_id,
            'tgl_task' => now()->format('Y-m-d'),
            'user_id' => Auth::user()->id,
            'nama_task' => $request->nama_task,
            'keterangan' => $request->keterangan,
            'upload' => $uploadPath,
        ];

        Task::create($data);
        return redirect()->back()->with('success', 'Task berhasil di tambahkan');
    }
    
    public function update(Request $request, $id)
    {
        $task = Task::find($id);

        $request->validate([
            'nama_task' => 'required',
            'keterangan' => 'nullable',
            'upload' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,gif|max:5120',
            'user_id' => 'required',            
        ]);

        $task->fill($request->except('upload'));

        if ($request->hasFile('upload')) {
            if ($task->upload) {
                $oldPhotoPath = public_path('uploads/' . $task->upload);
                if (file_exists($oldPhotoPath)) {
                    unlink($oldPhotoPath);
                }
            }
            $file = $request->file('upload');
            $filename = uniqid() . '_task_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            $task->upload = $filename;
        }
        
        $data = [
            'user_id' => $request->user_id,
            'nama_task' => $request->nama_task,
            'keterangan' => $request->keterangan,
            'upload' => $task->upload,
            'tgl_task' => now()->format('Y-m-d'),
        ];

        $task->update($data);
        return redirect()->back()->with('success', 'Task berhasil di update');
    }

    public function destroy($id)
    {
        dd($id);
        $task = Task::find($id);
        $task->delete();

        return redirect()->back()->with('succeess', 'Task Berhasil di hapus');
    }
}