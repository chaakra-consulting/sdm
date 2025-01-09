<?php

namespace App\Http\Controllers;

use App\Models\DataKesehatan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KesahatanController extends Controller
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
        $request->validate([
            'golongan_darah' => 'required',
            'riwayat_alergi' => 'required',
            'riwayat_penyakit' => 'required',
            'riwayat_penyakit_lain' => 'required',
        ]);

        $data = [
            'user_id' => $this->getUSer()->id,
            'golongan_darah' => $request->golongan_darah,
            'riwayat_alergi' => $request->riwayat_alergi,
            'riwayat_penyakit' =>  $request->riwayat_penyakit,
            'riwayat_penyakit_lain' => $request->riwayat_penyakit_lain
        ];
        

        DataKesehatan::create($data);

        return redirect()->back()->with('success', 'Data berhasil di tambahkan');
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
        $request->validate([
            'golongan_darah' => 'required',
            'riwayat_alergi' => 'required',
            'riwayat_penyakit' => 'required',
            'riwayat_penyakit_lain' => 'required',
        ]);

        $data = [
            'golongan_darah' => $request->golongan_darah,
            'riwayat_alergi' => $request->riwayat_alergi,
            'riwayat_penyakit' =>  $request->riwayat_penyakit,
            'riwayat_penyakit_lain' => $request->riwayat_penyakit_lain
        ];

        $getKesehatan = DataKesehatan::findOrFail($id);

        $getKesehatan->update($data);

        return redirect()->back()->with('success', 'Data berhasil di tambahkan');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
