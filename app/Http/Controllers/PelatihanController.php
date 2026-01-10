<?php

namespace App\Http\Controllers;

use App\Models\DataPelatihan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PelatihanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    function getUSer()
    {
        $getUser = User::where('id', Auth::id())->first();

        return $getUser;
    }

    public function index()
    {
        //
        $getPelatihan = DataPelatihan::where('user_id', $this->getUSer()->id)->get();
        $data = [
            'title' => 'Pelatihan',
            'pelatihan' => $getPelatihan
        ];

        return view('karyawan.pelatihan', $data);
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
        $upload_sertifikat = null;

        if ($request->hasFile('upload_sertifikat')) {
            $file = $request->file('upload_sertifikat');
            $filename = time() . '_sertifikat.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            $upload_sertifikat = $filename;
        }

        $data = [
            'user_id' =>  $request->user_id ? $request->user_id :$this->getUSer()->id,
            'nama_pelatihan' => $request->nama_pelatihan,
            'tujuan_pelatihan' => $request->tujuan_pelatihan,
            'tahun_pelatihan' => $request->tahun_pelatihan,
            'nomor_sertifikat' => $request->nomor_sertifikat,
            'upload_sertifikat' => $upload_sertifikat,
        ];

        //dd($data);

        DataPelatihan::create($data);

        return redirect()->back()->with('success', 'Data pelatihan berhasil di tambahkan');
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
        $getPelatihan = DataPelatihan::find($id);

        $request->validate([
            'nama_pelatihan' => 'required',
            'tujuan_pelatihan' => 'required',
            'tahun_pelatihan' => 'required',
            'nomor_sertifikat' => 'required',
            'upload_sertifikat' => 'nullable|file|mimes:pdf|max:2048',
        ]);
        // $upload_sertifikat = null;
        $upload_sertifikat = $getPelatihan->upload_sertifikat;

        if ($request->hasFile('upload_sertifikat')) {
            $file = $request->file('upload_sertifikat');
            $filename = time() . '_sertifikat.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            $upload_sertifikat = $filename;

            if (!empty($getPelatihan->upload_sertifikat)) {
                @unlink(public_path('uploads/' . $getPelatihan->upload_sertifikat));
            }
        } else {
            $upload_sertifikat = $getPelatihan->upload_sertifikat;
        }

        $data = [
            'nama_pelatihan' => $request->nama_pelatihan,
            'tujuan_pelatihan' => $request->tujuan_pelatihan,
            'tahun_pelatihan' => $request->tahun_pelatihan,
            'nomor_sertifikat' => $request->nomor_sertifikat,
            'upload_sertifikat' => $upload_sertifikat,
        ];

        $getPelatihan->update($data);
        return redirect()->back()->with('success', 'Data pelatihan berhasil di update');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $getPelatihan = DataPelatihan::findOrFail($id);
        if ($getPelatihan->upload_sertifikat != null) {
            unlink('uploads/' . $getPelatihan->upload_sertifikat);
        }
        $getPelatihan->delete();

        return redirect()->back()->with('success', 'Data pelatihan berhasil di hapus');
    }
}
