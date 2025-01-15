<?php

namespace App\Http\Controllers;

use App\Models\PengalamanKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PengalamanKerjaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $getPengalamanKerja = PengalamanKerja::where('user_id', Auth::id())->get();
        
        $data = [
            'title' => 'Pengalaman Kerja',
            'pengalaman_kerja' => $getPengalamanKerja
        ];

        return view('karyawan.pengalaman_kerja', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $surat_referensi = null;
        
        if ($request->hasFile('upload_surat_referensi')) {
            $file = $request->file('upload_surat_referensi');
            $filename = time() . '_surat_referensi.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            $surat_referensi = $filename;
        }

        $data = [
            'user_id' => Auth::id(),
            'nama_perusahaan' => $request->nama_perusahaan,
            'periode' => $request->periode,
            'jabatan_akhir' => $request->jabatan_akhir,
            'alasan_keluar' => $request->alasan_keluar,
            'no_hp_referensi' => $request->no_hp_referensi,
            'upload_surat_referensi' => $surat_referensi

        ];

        // DB::table('tb_data_pengalaman_kerja')->insert($data);
        PengalamanKerja::create($data);

        return back()->with('success', 'Pengalaman kerja berhasil di tambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        
        $pengalaman_kerja = PengalamanKerja::findOrFail($id);
        
        $surat_referensi = null;
        if ($request->hasFile('upload_surat_referensi')) {
            $file = $request->file('upload_surat_referensi');
            $filename = time() . '_surat_referensi.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            $surat_referensi = $filename;

            if($pengalaman_kerja->upload_surat_referensi != null){
                unlink('uploads/'. $pengalaman_kerja->upload_surat_referensi);
            }
        }

        $data = [
            'nama_perusahaan' => $request->nama_perusahaan,
            'periode' => $request->periode,
            'jabatan_akhir' => $request->jabatan_akhir,
            'alasan_keluar' => $request->alasan_keluar,
            'no_hp_referensi' => $request->no_hp_referensi,
            'upload_surat_referensi' => $surat_referensi
        ];

        $pengalaman_kerja->update($data);

        return back()->with('success', 'Pengalaman kerja berhasil di ubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $pengalaman_kerja = PengalamanKerja::findOrFail($id);
        if($pengalaman_kerja->upload_surat_referensi != null){
            unlink('uploads/'. $pengalaman_kerja->upload_surat_referensi);
        }
        $pengalaman_kerja->delete();

        return back()->with('success', 'Pengalaman kerja berhasil di hapus');
    }
}
