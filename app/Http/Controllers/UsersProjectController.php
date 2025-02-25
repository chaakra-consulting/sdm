<?php

namespace App\Http\Controllers;

use App\Models\UsersProject;
use Illuminate\Http\Request;
use App\Models\ProjectPerusahaan;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class UsersProjectController extends Controller
{
    public function index()
    {
        $title = 'List Project';
        $project = ProjectPerusahaan::with('perusahaan')->get();

        return view('project.daftar_project', compact('title', 'project'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'project_perusahaan_id' => 'required',
            'user_id' => 'required',
            'status' => 'required',
        ]);

        $data = [
            'project_perusahaan_id' => $request->project_perusahaan_id,
            'user_id' => $request->user_id,
            'status' => $request->status,
        ];
        UsersProject::create($data);

        return redirect()->back()->with('success', 'Project berhasil di tambahkan');
    }
    public function detail($id)
    {
        $title = 'Detail Project User';
        $project = UsersProject::where('project_perusahaan_id', $id)->with('project_perusahaan')->first();

        return view('project.user_project.detail_project', compact('project', 'title'));
    }
    public function getTasks($id)
    {
        $project = UsersProject::where('project_perusahaan_id', $id)->with('project_perusahaan')->first();
        $tasks = Task::where('project_perusahaan_id', $id)->get();

        $events = [];
        foreach ($tasks as $task) {
            $events[] = [
                'id'    => $task->id,
                'title' => $task->nama_task,
                'start' => $task->tgl_task,
                'description' => $task->keterangan,
                'color' => '#28a745',
                'waktu_mulai' => $project->project_perusahaan->waktu_mulai ?? null,
                'waktu_berakhir' => $project->project_perusahaan->waktu_berakhir ?? null,
                'deadline' => $project->project_perusahaan->deadline ?? null,
            ];
        }

        return response()->json($events);
    }
    public function update(Request $request, $id)
    {
        // dd($request);
        $project = ProjectPerusahaan::find($id);
        $request->validate([
            'waktu_mulai_hidden' => 'nullable',
            'waktu_berakhir' => 'nullable',
            'progres' => 'nullable',
            'status' => 'nullable',
        ]);

        $status = null;
        $progres = $request->progres ?? 0;
        if ($request->waktu_mulai_hidden != null && $request->waktu_berakhir == null) {
            $status = 'proses';
            $progres;
        } elseif ($request->waktu_mulai_hidden != null && $request->waktu_berakhir != null) {
            $status = 'selesai';
            $progres = 100;
        } else {
            $status = 'belum';
            $progres = 0;
        }

        $data = [
            'waktu_mulai' => $request->waktu_mulai_hidden,
            'waktu_berakhir' => $request->waktu_berakhir,
            'progres' => $progres,
            'status' => $status
        ];
        // dd($data);
        $project->update($data);

        return redirect()->back()->with('success', 'Project berhasil di update');
    }
}
