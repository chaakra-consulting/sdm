<?php

namespace App\Http\Controllers;

use App\Models\SubJabatan;
use Illuminate\Http\Request;

class SubJabatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $getSubJabatan = SubJabatan::get();
        
        $data = [
            'title' => 'Sub Jabatan',
            'sub_jabatan' => $getSubJabatan
        ];

        return view('admin.sub_jabatan', $data);
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
        $data = [
            'nama_sub_jabatan' => $request->nama_sub_jabatan,
        ];

        SubJabatan::create($data);

        return redirect()->back()->with('success', 'Sub jabatan berhasil di tambahkan');
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
        $getSubJabatan = SubJabatan::findOrFail($id);

        $data = [
            'nama_sub_jabatan' => $request->nama_sub_jabatan,
        ];

        $getSubJabatan->update($data);
        return redirect()->back()->with('success', 'Sub jabatan berhasil di update');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //

        $getSubJabatan = SubJabatan::findOrFail($id);

        $getSubJabatan->delete();
        return redirect()->back()->with('success', 'Sub jabatan berhasil di hapus');
    }
}
