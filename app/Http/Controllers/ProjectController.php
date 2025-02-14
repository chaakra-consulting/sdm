<?php

namespace App\Http\Controllers;

use App\Models\Perusahaan;
use App\Models\UsersProject;
use Illuminate\Http\Request;
use App\Models\ProjectPerusahaan;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function show()
    {
        $title = 'Daftar Project';
        $project = ProjectPerusahaan::with('perusahaan', 'project_users')->get();
        $userTakenProjects = UsersProject::where('user_id', Auth::user()->id)->pluck('project_perusahaan_id')->toArray();
        $perusahaan = Perusahaan::all();

        return view('project.daftar_project', compact('title', 'project', 'userTakenProjects', 'perusahaan'));
    }

    public function store(Request $request)
    {
        // dd($request);

        $request->validate([
            'nama_perusahaan' => 'required',
            'nama_project' => 'required',
            'skala_project' => 'required',
            'deadline' => 'required',
        ]);

        $data = [
            'perusahaan_id' => $request->nama_perusahaan,
            'nama_project' => $request->nama_project,
            'skala_project' => $request->skala_project,
            'waktu_mulai' => $request->waktu_mulai,
            'waktu_berakhir' => $request->waktu_berakhir,
            'deadline' => $request->deadline,
            'progres' => $request->progres,
        ];

        // dd($data);
        ProjectPerusahaan::create($data);

        return redirect()->back()->with('success', 'Project berhasil di tambahkan');
    }

    public function detail(string $id)
    {
        $project = ProjectPerusahaan::where('id', $id)->first();
        $perusahaan = Perusahaan::all();
        $title = 'Detail Project';

        return view('project.detail_project', compact('project', 'title', 'perusahaan'));
    }

    public function update(Request $request, $id)
    {
        $project = ProjectPerusahaan::find($id);

        $request->validate([
            'perusahaan_id' => 'required',
            'nama_project' => 'required',
            'skala_project' => 'required',
            'deadline' => 'required',
        ]);
        // dd($request);

        $data = [
            'perusahaan_id' => $request->perusahaan_id,
            'nama_project' => $request->nama_project,
            'skala_project' => $request->skala_project,
            'deadline' => $request->deadline,
        ];
        // dd($data);
        $project->update($data);

        return redirect()->back()->with('success', 'Project berhasil di update');
    }

    public function destroy(string $id)
    {
        UsersProject::where('project_perusahaan_id', $id)->delete();
        $project = ProjectPerusahaan::find($id);

        $project->delete();

        return redirect()->back()->with('success', 'Project berhasil dihapus.');
    }

    public function destroyUserProject($id)
    {
            $userId = Auth::id();
            // dd($userId, $id);

            $deleted = UsersProject::where('user_id', $userId)
                ->where('project_perusahaan_id', $id)
                ->delete();

            // dd($deleted);
            if ($deleted) {
                return back()->with('success', 'Project berhasil dihapus dari daftar Anda.');
            } else {
                return back()->with('error', 'Gagal menghapus project.');
            }
    }
}
