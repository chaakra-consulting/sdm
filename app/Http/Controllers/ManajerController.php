<?php

namespace App\Http\Controllers;

use App\Models\Perusahaan;
use App\Models\ProjectPerusahaan;
use Illuminate\Http\Request;

class ManajerController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Dashboard'
        ];

        return view('manajer.index', $data);
    }

    // manajemen perusahaan : data perusahaan
    public function show()
    {

        $getDataPerusahaan = Perusahaan::all();

        $data = [
            'title' => 'Daftar Perusahaan',
            'perusahaan' => $getDataPerusahaan
        ];

        return view('manajer.daftar_perusahaan', $data);
    }

    public function store(Request $request) 
    {
        $data = [
            'nama_perusahaan' => $request->nama_perusahaan,
        ];

        Perusahaan::create($data);

        return redirect()->back()->with('success', 'Perusahaan berhasil di tambahkan');

    }

    public function update(Request $request, $id)
    {
        $getDataPerusahaan = Perusahaan::findOrFail($id);
        $data = [
            'nama_perusahaan' => $request->nama_perusahaan
        ];

        $getDataPerusahaan->update($data);

        return redirect()->back()->with('success', 'Nama Perusahaan berhasil di update');
    }

    public function destroy($id)
    {
        $getDataPerusahaan = Perusahaan::findOrFail($id);

        $getDataPerusahaan->delete();

        return redirect()->back()->with('success', 'Perusahaan berhasil di hapus');
    }

    // manajemen project : data project
    public function show_project()
    {
        $getDataPerusahaan = Perusahaan::all();
        $getDataProject = ProjectPerusahaan::all();
        $data = [
            'title' => 'Daftar Project',
            'perusahaan' => $getDataPerusahaan,
            'project' => $getDataProject
        ];

        return view('manajer.daftar_project', $data);
    }

    public function store_project(Request $request)
    {
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

        dd($data);
        ProjectPerusahaan::create($data);

        return redirect()->back()->with('success', 'Project berhasil di tambahkan');
    }
}
