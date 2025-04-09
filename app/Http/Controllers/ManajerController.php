<?php

namespace App\Http\Controllers;

use App\Models\Perusahaan;
use Illuminate\Http\Request;
use App\Models\ProjectPerusahaan;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ManajerController extends Controller
{
    public function index()
    {
        $title = 'Dashboard';

        return view('manajer.index', compact('title'));
    }

    // manajemen perusahaan : data perusahaan
    public function show()
    {
        $perusahaan = Perusahaan::all();
        $title = 'List Instansi';

        return view('master.daftar_perusahaan', compact('title', 'perusahaan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_perusahaan' => 'required',
            'alamat' => 'nullable',
            'nama_pimpinan' => 'nullable',
            'kontak' => 'nullable',
            'gender' => 'nullable'
        ]);

        $data = [
            'nama_perusahaan' => $request->nama_perusahaan,
            'alamat' => $request->alamat,
            'nama_pimpinan' => $request->nama_pimpinan,
            'kontak' => $request->kontak,
            'gender' => $request->gender
        ];

        Perusahaan::create($data);

        return redirect()->back()->with('success', 'Instansi berhasil di tambahkan');
    }

    public function update(Request $request, $id)
    {
        $getDataPerusahaan = Perusahaan::findOrFail($id);
        $request->validate([
            'nama_perusahaan' => 'required',
            'alamat' => 'nullable',
            'nama_pimpinan' => 'nullable',
            'kontak' => 'nullable',
            'gender' => 'nullable'
        ]);
        $data = [
            'nama_perusahaan' => $request->nama_perusahaan,
            'alamat' => $request->alamat,
            'nama_pimpinan' => $request->nama_pimpinan,
            'kontak' => $request->kontak,
            'gender' => $request->gender
        ];
        $getDataPerusahaan->update($data);

        return redirect()->back()->with('success', 'Instansi berhasil di update');
    }

    public function destroy($id)
    {
        $getDataPerusahaan = Perusahaan::findOrFail($id);
        foreach ($getDataPerusahaan->projects as $project) {
            $project->project_users()->delete();
            $project->tasks()->delete();
            $project->delete();
        }

        $getDataPerusahaan->delete();

        return redirect()->back()->with('success', 'Instansi berhasil di hapus');
    }

    public function dataTransfer()
    {
        $getDataPerusahaan = DB::connection('db_bukukas')->table('master_customers')->select('id', 'name', 'address', 'email', 'contact', 'gender_contact')->get();

        $insert = 0;
        $update = 0;
        foreach ($getDataPerusahaan as $perusahaan) {
            $exists = DB::connection('mysql')->table('tb_m_perusahaans')->where('nama_perusahaan', $perusahaan->name)->first();

            if ($exists) {
                if (
                    $exists->nama_perusahaan !== $perusahaan->name ||
                    $exists->alamat !== $perusahaan->address ||
                    $exists->nama_pimpinan !== $perusahaan->email ||
                    $exists->kontak !== $perusahaan->contact ||
                    $exists->gender !== $perusahaan->gender_contact
                ) {
                    DB::connection('mysql')->table('tb_m_perusahaans')->where('nama_perusahaan', $perusahaan->name)->update([
                            'nama_perusahaan' => $perusahaan->name,
                            'alamat' => $perusahaan->address,
                            'nama_pimpinan' => $perusahaan->email,
                            'kontak' => $perusahaan->contact,
                            'gender' => $perusahaan->gender_contact,
                        ]);
                    $update++;
                }
            } else {
                Perusahaan::create([
                    'nama_perusahaan' => $perusahaan->name,
                    'alamat' => $perusahaan->address,
                    'nama_pimpinan' => $perusahaan->email,
                    'kontak' => $perusahaan->contact,
                    'gender' => $perusahaan->gender_contact
                ]);

                $insert++;
            }
        }
        if ($insert == 0 && $update == 0) {
            return redirect()->back()->with('success', 'Tidak ada data yang di transfer');
        }

        return redirect()->back()->with('success', 'Data Instansi berhasil di transfer');
    }

    public function laporanKinerja()
    {
        $title = 'Laporan Kinerja';
        $getDataUser = User::all();

        return view('manajer.laporan_kinerja', compact('title', 'getDataUser'));
    }
    public function listLaporanKinerja($id)
    {
        $title = 'Laporan Kinerja';
        $getDataUser = User::find($id);
        return view('manajer.list_laporan_kinerja', compact('title', 'getDataUser'));
    }
}
