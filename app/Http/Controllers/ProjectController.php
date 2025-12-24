<?php

namespace App\Http\Controllers;

use Log;
use Carbon\Carbon;
use App\Models\Task;
use App\Models\User;
use App\Models\Perusahaan;
use App\Models\UsersProject;
use Illuminate\Http\Request;
use App\Models\ProjectPerusahaan;
use App\Models\UsersTask;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use function Symfony\Component\Clock\now;

class ProjectController extends Controller
{
    public function show()
    {
        $title = 'Daftar Project';
        $project = ProjectPerusahaan::with('perusahaan', 'project_users')->get();
        $userProject = UsersProject::where('user_id', Auth::user()->id)->with('project_perusahaan.perusahaan')->get();
        $perusahaan = Perusahaan::all();
        $users = User::all();

        return view('project.daftar_project', compact(
            'title', 
            'project', 
            'perusahaan', 
            'users', 
            'userProject'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_perusahaan' => 'required',
            'nama_project' => 'required',
            'waktu_mulai' => 'required',
            'deadline' => 'nullable|date',
            'user.*' => 'required',
        ]);

        if ($request->waktu_mulai !== null) {
            $waktuMulai = Carbon::parse($request->waktu_mulai);
            $hariIni = Carbon::today();
            if ($waktuMulai->lessThanOrEqualTo($hariIni)) {
                $status = "proses";
            } else {
                $status = "belum";
            }
        } else {
            $status = "belum";
        }

        $data = [
            'perusahaan_id' => $request->nama_perusahaan,
            'status' => $status,
            'nama_project' => $request->nama_project,
            'waktu_mulai' => $request->waktu_mulai,
            'waktu_berakhir' => $request->waktu_berakhir,
            'deadline' => $request->deadline,
            'progres' => $request->progres,
        ];

        $project = ProjectPerusahaan::create($data);

        foreach ($request->user as $user_id) {
            UsersProject::create([
                'project_perusahaan_id' => $project->id,
                'user_id' => $user_id,
            ]);
        }

        return redirect()->back()->with('success', 'Project berhasil di tambahkan');
    }

    public function detail(string $id)
    {
        $title = 'Detail Project';
        $project = ProjectPerusahaan::where('id', $id)->with('tasks', 'tasks.users_task')->first();
        $userProject = UsersProject::where('id', $id)->with('project_perusahaan', 'user')->first();
        $tasks = Task::where('project_perusahaan_id', $id)->with('users_task')->get();
        $userstasks = UsersTask::whereHas('task', function ($query) use ($id) {
            $query->where('project_perusahaan_id', $id);
        })
        ->where('user_id', Auth::user()->id)
        ->with([
            'user.socialMedias',
            'user.dataDiri.kepegawaian.subJabatan'
        ])
        ->get();
        $perusahaan = Perusahaan::all();
        $user = UsersProject::where('project_perusahaan_id', $id)
            ->with([
                'user.socialMedias',
                'user.dataDiri.kepegawaian.subJabatan',
            ])
            ->get();
        $progress = $project->calculateProgress();
        $existingUserIds = UsersProject::where('project_perusahaan_id', $id)->pluck('user_id')->toArray();
        $users = User::whereNotIn('id', $existingUserIds)->get();
        $events = [];
        if ($project->waktu_mulai) {
            $events[] = [
                'title' => 'Mulai: ' . $project->nama_project,
                'start' => $project->waktu_mulai,
                'color' => 'green'
            ];
        }
        if ($project->deadline) {
            $events[] = [
                'title' => 'Deadline: ' . $project->nama_project,
                'start' => $project->deadline,
                'color' => 'red'
            ];
        }
        if ($progress == 100 && $project->status != 'selesai') {
            $project->update([
                'status' => 'selesai',
                'waktu_berakhir' => now()
            ]);
            $project = $project->fresh();
        } elseif ($progress < 100 && $project->status == 'selesai') {
            $project->update([
                'status' => 'proses',
                'waktu_berakhir' => null
            ]);
        }
        return view('project.detail_project', compact(
            'project',
            'title',
            'perusahaan',
            'tasks',
            'events',
            'user',
            'users',
            'userProject',
            'progress',
            'userstasks'
        ));
    }

    public function update(Request $request, $id)
    {
        $project = ProjectPerusahaan::find($id);

        $request->validate([
            'perusahaan_id' => 'required',
            'nama_project' => 'required',
            'waktu_mulai' => 'required',
            'waktu_berakhir' => 'nullable',
            'deadline' => 'nullable|date',
            'status' => 'nullable',
        ]);

        if ($request->waktu_mulai !== null) {
            $waktuMulai = Carbon::parse($request->waktu_mulai);
            $hariIni = Carbon::today();
            if ($waktuMulai->lessThanOrEqualTo($hariIni)) {
                $status = "proses";
            } else {
                $status = "belum";
            }
        } else {
            $status = "belum";
        }

        $data = [
            'perusahaan_id' => $request->perusahaan_id,
            'status' => $status ?? $project->status,
            'nama_project' => $request->nama_project,
            'waktu_mulai' => $request->waktu_mulai,
            'waktu_berakhir' => $request->waktu_berakhir,
            'deadline' => $request->deadline,
            'progres' => $project->progres,
        ];

        $project->update($data);

        return redirect()->back()->with('success', 'Project berhasil di update');
    }

    public function updateUserProject(Request $request)
    {
        $request->validate([
            'user.*' => 'required',
            'project_perusahaan_id' => 'required',
        ]);
        if (empty($request->user)) {
            return redirect()->back()->with('error', 'Tidak.');
        }
        $tambahUser = 0;
        foreach ($request->user as $userId) {
            $existing = UsersProject::where('user_id', $userId)
                ->where('project_perusahaan_id', $request->project_perusahaan_id)
                ->first();

            if (!$existing) {
                UsersProject::create([
                    'user_id' => $userId,
                    'project_perusahaan_id' => $request->project_perusahaan_id,
                ]);
                $tambahUser++;
            }
        }

        $message = $tambahUser > 0
            ? "$tambahUser anggota berhasil ditambahkan."
            : "Semua anggota sudah terdaftar di proyek ini.";

        return redirect()->back()->with('success', $message);
    }

    public function destroy(string $id)
    {
        Task::where('project_perusahaan_id', $id)->delete();
        UsersProject::where('project_perusahaan_id', $id)->delete();
        $project = ProjectPerusahaan::find($id);

        $project->delete();

        return redirect()->back()->with('success', 'Project berhasil dihapus.');
    }

    public function destroyUserProject($id)
    {
        $deleted = UsersProject::where('id', $id)
            ->delete();

        if ($deleted) {
            return back()->with('success', 'Anggota berhasil dihapus dari Project.');
        } else {
            return back()->with('error', 'Gagal menghapus Anggota Project.');
        }
    }
    public function storeFromExternal(Request $request)
    {
        // \Log::info('Data diterima dari CI3:', $request->all());
        $request->validate([
            'nama_perusahaan' => 'required|string',
            'nama_project' => 'required|string',
        ]);

        $perusahaan = Perusahaan::where('nama_perusahaan', $request->nama_perusahaan)->first();
        if (!$perusahaan) {
            return response()->json([
                'success' => false,
                'message' => 'Perusahaan tidak ditemukan'
            ], 404);
        }

        $request->merge(['perusahaan_id' => $perusahaan->id]);

        if ($request->filled('waktu_mulai') && $request->waktu_mulai != null) {
            $waktuMulai = Carbon::parse($request->waktu_mulai);
            $hariIni = Carbon::today();
            if ($waktuMulai->lessThanOrEqualTo($hariIni)) {
                $status = "proses";
            } else {
                $status = "belum";
            }
        } else {
            $status = "belum";
        }

        $validated = $request->validate([
            'perusahaan_id' => 'required|exists:tb_m_perusahaans,id',
            'nama_project' => 'required|string',
            'skala_project' => 'nullable|in:kecil,sedang,besar',
            'waktu_mulai' => 'nullable|date',
            'waktu_berakhir' => 'nullable|date',
            'deadline' => 'nullable|date',
            'progres' => 'nullable|numeric',
            'status' => 'nullable|string|in:belum,proses,selesai,telat',
        ]);

        $project = ProjectPerusahaan::create($validated);

        return response()->json([
            'success' => true,
            'data' => $project
        ], 201);
    }
}
