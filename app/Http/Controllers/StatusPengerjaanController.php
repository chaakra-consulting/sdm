<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\StatusPengerjaan;

class StatusPengerjaanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Status Pengerjaan';
        $getStatusPengerjaan = StatusPengerjaan::all();
        return view('master.status_pengerjaan', compact('title', 'getStatusPengerjaan'));
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
        $request->validate([
            'nama_status_pengerjaan' => 'required'
        ]);
        $data = [
            'nama_status_pengerjaan' => $request->nama_status_pengerjaan,
            'slug' => Str::slug($request->nama_status_pengerjaan)
        ];
        StatusPengerjaan::create($data);

        return redirect()->back()->with('success', 'Status Pengerjaan Berhasil Ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(StatusPengerjaan $statusPengerjaan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StatusPengerjaan $statusPengerjaan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StatusPengerjaan $statusPengerjaan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StatusPengerjaan $statusPengerjaan)
    {
        //
    }
}
