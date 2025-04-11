<?php

namespace App\Http\Controllers;

use App\Models\TipeTask;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class TipeTaskController extends Controller
{
    public function index()
    {
        $title = 'Master Tipe Task';
        $tipe = TipeTask::all();

        return view('master.tipe_task', compact('title', 'tipe'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_tipe' => 'required',
        ]);
        $data = [
            'nama_tipe' => $request->nama_tipe,
            'slug' => Str::slug($request->nama_tipe),
        ];
        TipeTask::create($data);

        return redirect()->back()->with('success', 'Tipe Task berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_tipe' => 'required',
        ]);
        $data = [
            'nama_tipe' => $request->nama_tipe,
            'slug' => Str::slug($request->nama_tipe),
        ];
        $tipe = TipeTask::find($id);
        $tipe->update($data);

        return redirect()->back()->with('success', 'Tipe Task berhasil diubah');
    }

    public function destroy($id)
    {
        $tipe = TipeTask::find($id);
        $tipe->delete();

        return redirect()->back()->with('success', 'Tipe Task berhasil dihapus');
    }
}
