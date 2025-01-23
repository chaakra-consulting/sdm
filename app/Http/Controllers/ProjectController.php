<?php

namespace App\Http\Controllers;

use App\Models\Perusahaan;
use Illuminate\Http\Request;
use App\Models\ProjectPerusahaan;

class ProjectController extends Controller
{
    public function show()
    {

        $project = ProjectPerusahaan::with('perusahaan')->get(); // Pastikan relasi `perusahaan` di-load
        return view('manajer.daftar_project', [
            'title' => 'Daftar Project',
            'project' => $project,
            'perusahaan' => Perusahaan::all(),
        ]);
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
            // 'status' => $request->status
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

        return view('manajer.detail_project', compact('project', 'title', 'perusahaan'));
    }  
    
    public function update(Request $request, $id)
    {
        $project = ProjectPerusahaan::find($id);
        // dd($request);
        $request->validate([
            'perusahaan_id' => 'required',
            'nama_project' => 'required',
            'skala_project' => 'required',
            'deadline' => 'required',
        ]);
        
        $data = [
            'perusahaan_id' => $request->perusahaan_id,
            'nama_project' => $request->nama_project,
            'skala_project' => $request->skala_project,
            'waktu_mulai' => $request->waktu_mulai,
            'waktu_berakhir' => $request->waktu_berakhir,
            'deadline' => $request->deadline,
            'progres' => $request->progres,
            'status' => $request->status
        ];

        // dd($data);
        $project->update($data);

        return redirect()->back()->with('success', 'Project berhasil di update');   
    }
}
