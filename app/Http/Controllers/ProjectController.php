<?php

namespace App\Http\Controllers;

use App\Models\Perusahaan;
use App\Models\UsersProject;
use Illuminate\Http\Request;
use App\Models\ProjectPerusahaan;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function show()
    {
        $title = 'Daftar Project';
        $project = ProjectPerusahaan::with('perusahaan', 'project_users')->get();
        $userTakenProjects = UsersProject::where('user_id', Auth::user()->id)->pluck('project_perusahaan_id')->toArray();
        $perusahaan = Perusahaan::all();
        $users = User::all();

        return view('project.daftar_project', compact('title', 'project', 'userTakenProjects', 'perusahaan', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_perusahaan' => 'required',
            'nama_project' => 'required',
            'skala_project' => 'required',
            'status' => 'required',
            'waktu_mulai' => 'required',
            'deadline' => 'required',
            'user.*' => 'required',
        ]);
        $data = [
            'perusahaan_id' => $request->nama_perusahaan,
            'nama_project' => $request->nama_project,
            'skala_project' => $request->skala_project,
            'waktu_mulai' => $request->waktu_mulai,
            'waktu_berakhir' => $request->waktu_berakhir,
            'deadline' => $request->deadline,
            'status' => $request->status,
            'progres' => $request->progres,
        ];
        
        $project = ProjectPerusahaan::create($data);

        foreach ($request->user as $user_id) {
            UsersProject::create([
                'project_perusahaan_id' => $project->id,
                'user_id' => $user_id,
                'status' => $request->status,
            ]);
        }

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
            'status' => 'required',
            'waktu_mulai' => 'required',
            'waktu_berakhir' => 'nullable',
            'deadline' => 'required',
        ]);
        $data = [
            'perusahaan_id' => $request->perusahaan_id,
            'nama_project' => $request->nama_project,
            'skala_project' => $request->skala_project,
            'status' => $request->status,
            'waktu_mulai' => $request->waktu_mulai,
            'waktu_berakhir' => $request->waktu_berakhir,
            'deadline' => $request->deadline,
        ];
        
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
            
            $deleted = UsersProject::where('user_id', $userId)
                ->where('project_perusahaan_id', $id)
                ->delete();
                
            if ($deleted) {
                return back()->with('success', 'Project berhasil dihapus dari daftar Anda.');
            } else {
                return back()->with('error', 'Gagal menghapus project.');
            }
    }
}