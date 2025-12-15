<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\ProjectPerusahaan;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class BukukasProjectSyncController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'perusahaan_id' => 'required|numeric',
            'bukukas_id'    => 'required|numeric',
            'nama_project'  => 'required|string',
            'waktu_mulai'   => 'nullable|date',
            'deadline'      => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }

        $existingProject = ProjectPerusahaan::where('ref_bukukas_id', $request->bukukas_id)->first();

        if ($existingProject) {
            return response()->json([
                'status' => 'success', 
                'message' => 'Data sudah tersinkronisasi sebelumnya',
                'data' => $existingProject
            ], 200);
        }

        try {
            $project = ProjectPerusahaan::create([
                'perusahaan_id'     => $request->perusahaan_id,
                'ref_bukukas_id'    => $request->bukukas_id,
                'nama_project'      => $request->nama_project,
                'waktu_mulai'       => $request->waktu_mulai,
                'deadline'          => $request->deadline,
                'waktu_berakhir'    => null,
                'status'            => 'belum',
                'progres'           => 0
            ]);

            return response()->json([
                'status'    => 'success',
                'message'   => 'Data berhasil masuk SDM',
                'data'      => $project
            ], 201);
            
        } catch (\Exception $e) {
            return response()->json([
                'status'    => 'error',
                'message'   => $e->getMessage()
            ], 500);
        }
    }
}
