<?php

namespace App\Http\Controllers;

use App\Models\UsersProject;
use Illuminate\Http\Request;
use App\Models\ProjectPerusahaan;
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
        $project = UsersProject::where('project_perusahaan_id', $id)->with('project_perusahaan')->first();
        $title = 'Detail Project User';

        return view('project.user_project.detail_project', compact('project', 'title'));
    }
    public function update(Request $request, $id)
    {
        // dd($request);
        $project = ProjectPerusahaan::find($id);
        $request->validate([
            'waktu_mulai' => 'nullable',
            'waktu_berakhir' => 'nullable',
            'progres' => 'nullable',
            'status' => 'nullable',
        ]);

        $status = null;
        if ($request->waktu_mulai != null && $request->waktu_berakhir == null) {
            $status = 'proses';
        } elseif ($request->waktu_mulai != null && $request->waktu_berakhir != null) {
            $status = 'selesai';
        } else {
            $status = 'belum';
        }
        // dd($request);

        $data = [
            'waktu_mulai' => $request->waktu_mulai,
            'waktu_berakhir' => $request->waktu_berakhir,
            'progres' => $request->progres,
            'status' => $status
        ];
        // dd($data);
        $project->update($data);

        return redirect()->back()->with('success', 'Project berhasil di update');
    }
}
