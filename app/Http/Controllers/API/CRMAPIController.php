<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ProjectPerusahaan;
use App\Models\Task;
use App\Models\TipeTask;
use App\Models\User;
use App\Models\UsersTask;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CRMAPIController extends Controller
{
    public function indexUser(Request $request): JsonResponse
    {
        $bukukasProjectId = $request->bukukas_project_id;

        $project = $bukukasProjectId
            ? ProjectPerusahaan::where('ref_bukukas_id', $bukukasProjectId)->first()
            : null;

        $usersQuery = $project
            ? $project->users()
            : User::query();

        $users = $usersQuery->get()->map(function ($user) {
            return [
                'id'      => $user->id,
                'name'    => $user->name,
                'email'   => $user->email,
                'divisi'  => $user->dataDiri?->kepegawaian?->divisi?->nama_divisi ?? null,
                'jabatan' => $user->dataDiri?->kepegawaian?->subJabatan?->nama_sub_jabatan ?? null,
            ];
        });

        return response()->json($users);
    }

    public function storeTask(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'tipe_task' => 'required|string',
            'project_bukukas_id' => 'required',
            'nama_task' => 'required|string',
            'tgl_task' => 'required',
            'deadline_task' => 'nullable',
            'keterangan' => 'nullable',
        ]);

        $user = User::find($request->user_id);
        $project = ProjectPerusahaan::where('ref_bukukas_id', $request->project_bukukas_id)->first();

        if (! $project) {
            return response()->json(['message' => 'Project tidak ditemukan'], 404);
        }

        $tipeTask = TipeTask::where('slug', $request->tipe_task)->first()
            ?? TipeTask::where('slug', 'task-project')->first();

        $task = Task::create([
            'tipe_tasks_id' => $tipeTask->id ?? null,
            'project_perusahaan_id' => $project->id,
            'tgl_task' => $request->tgl_task,
            'deadline' => $request->deadline_task,
            'status' => 'proses',
            'user_id' => $user->id,
            'nama_task' => $request->nama_task,
            'keterangan' => $request->keterangan,
        ]);

        UsersTask::create([
            'task_id' => $task->id,
            'user_id' => $user->id,
        ]);

        return response()->json([
            'message' => 'Task berhasil ditambahkan',
            'data' => $task,
        ], 201);
    }

}
