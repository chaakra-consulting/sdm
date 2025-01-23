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
}
