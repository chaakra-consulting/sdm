<?php

namespace App\Http\Controllers;

use App\Models\DataKepegawaian;
use App\Models\DataStatusPekerjaan;
use Illuminate\Http\Request;

class StatusPekerjaanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $getStatusPekerjaan = DataStatusPekerjaan::all();

        $data = [
            'title' => 'Status Pekerjaan',
            'status_pekerjaan' => $getStatusPekerjaan
        ];

        return view('admin_sdm.status_pekerjaan', $data);
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
            'nama_status_pekerjaan' => $request->nama_status_pekerjaan
        ];

        DataStatusPekerjaan::create($data);

        return redirect()->back()->with('success', 'Nama status pekerjaan berhasil di tambahkan');
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
        $getStatusPekerjaan = DataStatusPekerjaan::findOrFail($id);

        $data = [
            'nama_status_pekerjaan' => $request->nama_status_pekerjaan
        ];

        $getStatusPekerjaan->update($data);

        return redirect()->back()->with('success', 'Nama status pekerjaan berhasil di update');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $getStatusPekerjaan = DataStatusPekerjaan::findOrFail($id);

        $getKepegawaian = DataKepegawaian::where('status_pekerjaan_id', $id)->first();

        if($getKepegawaian){
            return redirect()->back()->with('error', 'Nama status '.$getStatusPekerjaan->nama_status_pekerjaan.' pekerjaan gagal di hapus. Terdapat data di dalam ini');
        }

        $getStatusPekerjaan->delete();

        return redirect()->back()->with('success', 'Nama status '. $getStatusPekerjaan->nama_status_pekerjaan .' pekerjaan berhasil di update');
    }
}
