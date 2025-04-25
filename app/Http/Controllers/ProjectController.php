<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Task;
use App\Models\User;
use App\Models\Perusahaan;
use App\Models\UsersProject;
use Illuminate\Http\Request;
use App\Models\ProjectPerusahaan;
use App\Models\StatusPengerjaan;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function show()
    {
        $title = 'Daftar Project';
        $project = ProjectPerusahaan::with('perusahaan', 'project_users', 'status_pengerjaan')->get();
        $userProject = UsersProject::where('user_id', Auth::user()->id)->with('project_perusahaan.perusahaan')->get();
        $perusahaan = Perusahaan::all();
        $statusPengerjaan = StatusPengerjaan::all();
        $users = User::all();

        return view('project.daftar_project', compact('title', 'project', 'perusahaan', 'users', 'userProject', 'statusPengerjaan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_perusahaan' => 'required',
            'nama_project' => 'required',
            'waktu_mulai' => 'required',
            'deadline' => 'required',
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

        $statusPengerjaan = StatusPengerjaan::where('slug', $status)->first();
        if (!$statusPengerjaan) {
            return redirect()->back()->with('error', 'Status pengerjaan tidak ditemukan.');
        }

        $data = [
            'perusahaan_id' => $request->nama_perusahaan,
            'status_pengerjaans_id' => $statusPengerjaan->id,
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
        $project = ProjectPerusahaan::where('id', $id)->with('status_pengerjaan')->first();
        $userProject = UsersProject::where('id', $id)->with('project_perusahaan', 'user')->first();
        $statusPengerjaan = StatusPengerjaan::all();
        $tasks = Task::where('project_perusahaan_id', $id)->with('users_task')->get();
        $perusahaan = Perusahaan::all();
        $user = UsersProject::where('id', $id)
            ->with([
                'user.socialMedias',
                'user.dataDiri.kepegawaian.subJabatan',
            ])
            ->get();
        $existingUserIds = UsersProject::where('project_perusahaan_id', $id)->pluck('user_id')->toArray();
        $users = User::whereNotIn('id', $existingUserIds)->get();
        $events = [];
        
        if (Auth::check() && Auth::user()->role->slug == 'manager') {
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
        } elseif (Auth::check() && Auth::user()->role->slug == 'karyawan') {
            if ($userProject->project_perusahaan->waktu_mulai) {
                $events[] = [
                    'title' => 'Mulai: ' . $userProject->project_perusahaan->nama_project,
                    'start' => $userProject->project_perusahaan->waktu_mulai,
                    'color' => 'green'
                ];
            }
            if ($userProject->project_perusahaan->deadline) {
                $events[] = [
                    'title' => 'Deadline: ' . $userProject->project_perusahaan->nama_project,
                    'start' => $userProject->project_perusahaan->deadline,
                    'color' => 'red'
                ];
            }
        }

        return view('project.detail_project', compact('project', 'title', 'perusahaan', 'tasks', 'events', 'user', 'users', 'statusPengerjaan', 'userProject'));
    }

    public function update(Request $request, $id)
    {
        $project = ProjectPerusahaan::find($id);

        $request->validate([
            'perusahaan_id' => 'required',
            'nama_project' => 'required',
            'waktu_mulai' => 'required',
            'waktu_berakhir' => 'nullable',
            'deadline' => 'required',
            'status' => 'required',
        ]);
        $status = StatusPengerjaan::where('slug', $request->status)->first();
        if (!$status) {
            return redirect()->back()->with('error', 'Status pengerjaan tidak ditemukan.');
        }

        $data = [
            'perusahaan_id' => $request->perusahaan_id,
            'status_pengerjaans_id' => $status->id,
            'nama_project' => $request->nama_project,
            'waktu_mulai' => $request->waktu_mulai,
            'waktu_berakhir' => $request->waktu_berakhir,
            'deadline' => $request->deadline,
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
}
