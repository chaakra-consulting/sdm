<?php

namespace App\Http\Controllers;

use App\Models\Divisi;
use Illuminate\Http\Request;
use App\Models\DataKepegawaian;
use App\Models\HariLibur;

class HariLiburController extends Controller
{
    public function index()
    {
        $title = 'Hari Libur Nasional dan Cuti Bersama';
        $hari_libur = HariLibur::all();

        return view('admin_sdm.hari_libur', compact('title', 'hari_libur'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'nama' => 'required|string|max:255',
            //'jenis_libur' => 'required|string|in:Libur Nasional,Cuti Bersama'
        ]);

        $data = [
            'tanggal' => $request->tanggal,
            'nama' => $request->nama,
            //'jenis_libur' => $request->jenis_libur,
        ];

        HariLibur::create($data);

        return redirect()->back()->with('success', 'Hari Libur berhasil disimpan.');
    }

    public function update(Request $request, $id) 
    {   
        $request->validate([
            'tanggal' => 'required|date',
            'nama' => 'required|string|max:255',
            //'jenis_libur' => 'required|string|in:Libur Nasional,Cuti Bersama'
        ]);

        $getHariLibur = HariLibur::find($id);

        $data = [
            'tanggal' => $request->tanggal,
            'nama' => $request->nama,
            //'jenis_libur' => $request->jenis_libur,
        ];

        $getHariLibur->update($data);

        return redirect()->back()->with('success', 'Hari Libur berhasil di update');
    }

    public function destroy($id)
    {
        $getHariLibur = HariLibur::find($id);

        $getHariLibur->delete();

        return redirect()->back()->with('success',  $getHariLibur->nama .' berhasil di hapus');
    }
}
