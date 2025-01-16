<?php

namespace App\Http\Controllers;

use App\Models\Divisi;
use Illuminate\Http\Request;
use App\Models\DataKepegawaian;

class DivisiController extends Controller
{
    public function index()
    {
        $title = 'Divisi';
        $divisi = Divisi::all();

        return view('admin_sdm.divisi', compact('title', 'divisi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_divisi' => 'required|string|max:255|regex:/^Divisi\s.+$/'
        ], [
            'nama_divisi.regex' => 'Nama divisi harus diawali dengan kata "Divisi".',
        ]);

        $data = [
            'nama_divisi' => $request->nama_divisi,
        ];

        Divisi::create($data);

        return redirect()->back()->with('success', 'Nama divisi berhasil disimpan.');
    }

    public function update(Request $request, $id) 
    {   
        $getDivisi = Divisi::find($id);

        $data = [
            'nama_divisi' => $request->nama_divisi
        ];

        $getDivisi->update($data);

        return redirect()->back()->with('success', 'Nama status pekerjaan berhasil di update');
    }

    public function destroy(string $id)
    {
        //
        $getDivisi = Divisi::find($id);

        $getKepegawaian = DataKepegawaian::where('divisi_id', $id)->first();

        if($getKepegawaian){
            return redirect()->back()->with('error', 'Nama Divisi '.$getDivisi->nama_divisi.' gagal di hapus. Terdapat data di dalam ini');
        }

        $getDivisi->delete();

        return redirect()->back()->with('success', 'Nama Divisi '. $getDivisi->nama_divisi .' berhasil di hapus');
    }
}
