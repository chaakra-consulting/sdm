<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\Perusahaan;
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
        
        $localPerusahaan = Perusahaan::where('bukukas_id', $request->perusahaan_id)->first();

        if (!$localPerusahaan) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data Instansi/Perusahaan belum disinkronisasi di SDM. Silakan lakukan Transfer Data Instansi terlebih dahulu.'
            ], 404);
        }

        try {
            $project = ProjectPerusahaan::updateOrCreate(
                [
                    'ref_bukukas_id' => $request->bukukas_id
                ],
                [
                    'perusahaan_id'  => $localPerusahaan->id,
                    'nama_project'   => $request->nama_project,
                    'waktu_mulai'    => $request->waktu_mulai,
                    'deadline'       => $request->deadline,
                    // 'status'         => 'belum', 
                    // 'progres'        => 0
                ]
            );

            $statusMsg = $project->wasRecentlyCreated ? 'Data Project berhasil masuk SDM' : 'Data Project berhasil diperbarui';

            return response()->json([
                'status'    => 'success',
                'message'   => $statusMsg,
                'data'      => $project
            ], $project->wasRecentlyCreated ? 201 : 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'status'    => 'error',
                'message'   => 'Gagal menyimpan project: ' . $e->getMessage()
            ], 500);
        }
    }
}