@extends('layouts.main')

@section('content')
    @php
        $userRole = Auth::check() ? Auth::user()->role->slug : '';
        $tipeTaskSlug = $task->tipe_task ? $task->tipe_task->slug : '';
        $uId = auth()->id();
        
        $config = [
            'can_edit' => false, 
            'can_manage_member' => false,
            'show_save_button' => false,
            'delete_member_prefix' => '',
            'action_url' => '',
            'lampiran_url' => '',
            'add_member_url' => '',
            'back_url' => '#'
        ];
        
        if ($userRole == 'manager' && $tipeTaskSlug == 'task-project') {
            $config = [
                'can_edit' => true, 
                'can_manage_member' => true,
                'show_save_button' => true,
                'delete_member_prefix' => 'manajer',
                'action_url' => route('manajer.update.detail.task', $task->id),
                'lampiran_url' => route('manajer.update.lampiran.task', $task->id),
                'add_member_url' => route('manajer.update.anggota.task'),
                'back_url' => '/manajer/task'
            ];
        } elseif ($userRole == 'admin-sdm' && ($tipeTaskSlug != 'task-project')) {
            $config = [
                'can_edit' => true, 
                'can_manage_member' => true,
                'show_save_button' => true,
                'delete_member_prefix' => 'admin_sdm',
                'action_url' => route('admin_sdm.update.detail.task', $task->id),
                'lampiran_url' => route('admin_sdm.update.lampiran.task', $task->id),
                'add_member_url' => route('admin_sdm.update.anggota.task'),
                'back_url' => '/admin_sdm/task'
            ];
        } elseif ($userRole == 'karyawan' && $tipeTaskSlug == 'task-tambahan') {
            $config = [
                'can_edit' => true, 
                'can_manage_member' => true,
                'show_save_button' => true,
                'delete_member_prefix' => 'karyawan',
                'action_url' => route('karyawan.update.detail.task', $task->id),
                'lampiran_url' => route('karyawan.update.lampiran.task', $task->id),
                'add_member_url' => route('karyawan.update.anggota.task'),
                'back_url' => '/karyawan/task'
            ];
        } else {
             if ($userRole == 'manager') $config['back_url'] = '/manajer/task';
             elseif ($userRole == 'karyawan') $config['back_url'] = '/karyawan/task';
             elseif ($userRole == 'admin-sdm') $config['back_url'] = '/admin_sdm/task';
        }
        
        $startDate = \Carbon\Carbon::parse($task->tgl_task);
        $deadlineDate = \Carbon\Carbon::parse($task->deadline);
        $endDate = $task->tgl_selesai ? \Carbon\Carbon::parse($task->tgl_selesai) : null;
        
        $statusBadge = '';
        if($task->status == 'selesai') {
            $statusBadge = '<span class="badge bg-success-transparent rounded-pill"><i class="ri-checkbox-circle-line me-1"></i> Selesai</span>';
        } elseif($task->deadline && \Carbon\Carbon::now()->gt($deadlineDate) && !$task->tgl_selesai) {
            $statusBadge = '<span class="badge bg-danger-transparent rounded-pill"><i class="ri-alarm-warning-line me-1"></i> Overdue</span>';
        } else {
            $statusBadge = '<span class="badge bg-info-transparent rounded-pill"><i class="ri-loader-4-line me-1"></i> Proses</span>';
        }
        
        $inputState = 'disabled';
    @endphp

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card custom-card border-top-card border-top-primary rounded-0 rounded-bottom">
                    <div class="card-header justify-content-between align-items-center">
                        <div class="card-title">
                            <i class="ri-line-chart-line me-2 text-primary"></i> Analisa Target vs Aktual
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ $config['back_url'] }}" class="btn btn-light btn-sm btn-wave" data-bs-toggle="tooltip" title="Kembali ke Daftar">
                                <i class="ri-arrow-left-line me-1 align-middle"></i> Kembali
                            </a>
                            @if ($task->project_perusahaan)
                                @php
                                    $projectUrl = '#';
                                    if ($userRole == 'manager') $projectUrl = "/manajer/project/detail/" . $task->project_perusahaan->id;
                                    elseif ($userRole == 'karyawan') $projectUrl = "/karyawan/project/detail/" . $task->project_perusahaan->id;
                                    elseif ($userRole == 'admin-sdm') $projectUrl = "/admin_sdm/project/detail/" . $task->project_perusahaan->id;
                                @endphp
                                <a href="{{ $projectUrl }}" class="btn btn-primary-light btn-sm btn-wave" data-bs-toggle="tooltip" title="Lihat Project Induk">
                                    <i class="ri-building-2-line me-1 align-middle"></i> Ke Project
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="fw-semibold mb-0 text-muted text-uppercase fs-12">Total Progress Pengerjaan</h6>
                                <span class="fw-bold fs-14 text-primary">{{ $progressPercentage }}%</span>
                            </div>
                            <div class="progress progress-xl rounded-pill bg-light" style="height: 12px;">
                                <div class="progress-bar bg-primary-gradient rounded-pill" role="progressbar" style="width: {{ $progressPercentage }}%" aria-valuenow="{{ $progressPercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-bordered text-nowrap mb-0 align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col" class="text-uppercase fs-12 fw-bold text-center">Indikator</th>
                                        <th scope="col" class="text-uppercase fs-12 fw-bold text-center">Target (Rencana)</th>
                                        <th scope="col" class="text-uppercase fs-12 fw-bold text-center">Aktual (Realisasi)</th>
                                        <th scope="col" class="text-uppercase fs-12 fw-bold text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="fw-semibold">Waktu Mulai</td>
                                        <td class="text-center text-muted">{{ $startDate->translatedFormat('d F Y') }}</td>
                                        <td class="text-center fw-bold text-success">{{ $startDate->translatedFormat('d F Y') }}</td>
                                        <td class="text-center"><span class="badge bg-success-transparent rounded-pill"><i class="ri-check-line"></i> Sesuai Jadwal</span></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">Waktu Selesai</td>
                                        <td class="text-center text-muted">{{ $deadlineDate->translatedFormat('d F Y') }}</td>
                                        <td class="text-center">
                                            @if($endDate)
                                                <span class="fw-bold text-dark">{{ $endDate->translatedFormat('d F Y') }}</span>
                                            @else
                                                <span class="text-muted fst-italic">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($endDate)
                                                @php $diff = $deadlineDate->diffInDays($endDate, false); @endphp
                                                @if($diff > 0) <span class="badge bg-danger-transparent rounded-pill"><i class="ri-arrow-up-line"></i> Terlambat {{ $diff }} Hari</span>
                                                @elseif($diff < 0) <span class="badge bg-success-transparent rounded-pill"><i class="ri-flash-line"></i> Lebih Cepat {{ abs($diff) }} Hari</span>
                                                @else <span class="badge bg-info-transparent rounded-pill">Tepat Waktu</span> @endif
                                            @else
                                                @if(\Carbon\Carbon::now()->gt($deadlineDate)) <span class="badge bg-danger-transparent rounded-pill">Overdue</span>
                                                @else <span class="badge bg-light text-dark border">On Track</span> @endif
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold text-primary">Durasi Pengerjaan</td>
                                        <td class="text-center text-muted">
                                            @php $targetDays = $startDate->diffInDays($deadlineDate); @endphp
                                            {{ round($targetDays) }} Hari
                                        </td>
                                        <td class="text-center fw-bold">
                                            @if($endDate)
                                                {{ round($startDate->diffInDays($endDate)) }} Hari
                                            @else
                                                <span class="text-muted fw-normal fst-italic">
                                                    Berjalan {{ round($startDate->diffInDays(now())) }} Hari
                                                </span> 
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($targetDays > 0)
                                                @php
                                                    $actualDays = $endDate ? $startDate->diffInDays($endDate) : $startDate->diffInDays(now());
                                                    $percentageUsed = ($actualDays / $targetDays) * 100;
                                                    $efficiency = 100 - $percentageUsed;
                                                @endphp

                                                @if($endDate)
                                                    @if($efficiency > 0) <span class="badge bg-success-transparent rounded-pill"><i class="ri-speed-up-line me-1"></i> {{ round($efficiency) }}% Lebih Cepat</span>
                                                    @elseif($efficiency < 0) <span class="badge bg-danger-transparent rounded-pill"><i class="ri-slow-down-line me-1"></i> {{ abs(round($efficiency)) }}% Lebih Lambat</span>
                                                    @else <span class="badge bg-info-transparent rounded-pill">100% Sesuai Target</span> @endif
                                                @else
                                                    <span class="fs-11 {{ $percentageUsed > 100 ? 'text-danger' : 'text-muted' }}">{{ round($percentageUsed) }}% Waktu Terpakai</span>
                                                @endif
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-4 col-lg-5">
                <div class="card custom-card overflow-hidden">
                    <div class="card-header border-bottom border-block-end-dashed">
                        <div class="card-title">Informasi Task</div>
                        <div class="ms-auto">{!! $statusBadge !!}</div>
                    </div>
                    <div class="card-body pt-4">
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show mb-3">
                                <ul class="mb-0 ps-3 fs-12">@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form action="{{ $config['action_url'] }}" method="post" enctype="multipart/form-data">
                            @csrf @method('put')
                            <input type="hidden" name="user_id" value="{{ $uId }}">
                            <input type="hidden" name="tipe_task" value="{{ $task->tipe_task->slug ?? '' }}"> 
                            <input type="hidden" name="nama_project" value="{{ $task->project_perusahaan_id }}">

                            <div class="mb-3">
                                <label class="form-label fs-13 text-muted">Project</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-primary-transparent border-end-0"><i class="ri-building-line text-primary"></i></span>
                                    <input type="text" class="form-control border-start-0 fw-bold bg-transparent" 
                                           value="{{ $task->project_perusahaan->nama_project ?? '-' }}" 
                                           readonly style="pointer-events: none;">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fs-13 text-muted">Nama Task</label>
                                <input type="text" name="nama_task" id="nama_task" class="form-control fw-bold" value="{{ $task->nama_task }}" {{ $inputState }}>
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label fs-13 text-muted">Mulai</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="ri-calendar-line text-muted"></i></span>
                                        <input type="text" class="form-control border-start-0 ps-0" name="tgl_task" id="tgl_task" value="{{ $task->tgl_task }}" {{ $inputState }}>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label class="form-label fs-13 text-muted">Deadline</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="ri-calendar-event-line text-muted"></i></span>
                                        <input type="text" class="form-control border-start-0 ps-0" name="deadline" id="deadline" value="{{ $task->deadline }}" {{ $inputState }}>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fs-13 text-muted">Tanggal Selesai (Aktual)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="ri-checkbox-circle-line text-success"></i></span>
                                    <input type="text" class="form-control border-start-0 ps-0" name="tgl_selesai" id="tgl_selesai" value="{{ $task->tgl_selesai }}" placeholder="Belum Selesai" {{ $inputState }}>
                                </div>
                            </div>

                            @if ($config['show_save_button'])
                                <div class="d-grid gap-2 mt-4">
                                    @if ($config['can_edit'])
                                        <button type="button" class="btn btn-warning-light btn-wave btn-edit-task">
                                            <i class="ri-pencil-line me-1"></i> Edit Data
                                        </button>
                                        <button type="button" class="btn btn-danger-light btn-wave btn-batal-edit" hidden>
                                            <i class="ri-close-line me-1"></i> Batal Edit
                                        </button>
                                    @endif
                                    <button type="submit" class="btn btn-primary btn-wave btn-submit-task" hidden>
                                        <i class="ri-save-line me-1"></i> Simpan Perubahan
                                    </button>
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-8 col-lg-7">
                <div class="card custom-card">
                    <div class="card-body p-0">
                        <nav class="nav nav-tabs nav-justified tab-style-1 d-flex" role="tablist">
                            <a class="nav-link active py-3" data-bs-toggle="tab" href="#home" role="tab"><i class="ri-list-check-2 me-1 align-middle fs-16"></i> Sub Task</a>
                            <a class="nav-link py-3" data-bs-toggle="tab" href="#lampiran" role="tab"><i class="ri-attachment-2 me-1 align-middle fs-16"></i> Lampiran</a>
                            <a class="nav-link py-3" data-bs-toggle="tab" href="#anggota" role="tab"><i class="ri-group-line me-1 align-middle fs-16"></i> Anggota Tim</a>
                        </nav>
                        
                        <div class="tab-content p-4">
                            <div class="tab-pane active" id="home" role="tabpanel">
                                @if (in_array($userRole, ['karyawan', 'admin-sdm']))
                                    <div class="mb-3">
                                        <button class="btn btn-outline-primary btn-wave w-100 btn-dashed tambahSubTask" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                            <i class="ri-add-circle-line me-1"></i> Tambah Sub Task Baru
                                        </button>
                                    </div>
                                @endif
                                <div class="table-responsive">
                                    <table class="table table-hover text-nowrap" id="subtaskTable">
                                        <thead class="bg-light">
                                            <tr>
                                                <th scope="col">No</th>
                                                <th scope="col">Sub Task</th>
                                                <th scope="col">Deadline</th>
                                                <th scope="col" class="text-center">Status</th>
                                                <th scope="col" class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $listSubTask = ($userRole == 'manager') ? $subTask : $subTaskUser; @endphp
                                            @forelse ($listSubTask as $index => $item)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td><span class="fw-semibold text-dark">{{ $item->nama_subtask }}</span></td>
                                                    <td class="text-muted">{{ \Carbon\Carbon::parse($item->deadline)->translatedFormat('l, d F Y') }}</td>
                                                    <td class="text-center">
                                                        @if($item->detail_sub_task->isEmpty()) <span class="badge bg-secondary-transparent">Draft</span>
                                                        @elseif($item->status === 'revise') <span class="badge bg-warning-transparent" title="Revisi: {{ $item->revisi->pesan ?? '' }}">Revisi</span>
                                                        @elseif($item->status === 'approve') <span class="badge bg-success">Approve</span>
                                                        @else <span class="badge bg-info-transparent">Review</span> @endif
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="d-flex justify-content-center gap-2">
                                                            <a href="{{ ($userRole == 'manager') ? route('manajer.subtask.detail', $item->id) : (($userRole == 'admin-sdm') ? route('admin_sdm.subtask.detail', $item->id) : route('karyawan.subtask.detail', $item->id)) }}" class="btn btn-sm btn-icon btn-dark" data-bs-toggle="tooltip" title="Detail"><i class="ri-file-list-line"></i></a>
                                                            @if($userRole != 'manager')
                                                                <button class="btn btn-sm btn-icon btn-warning updateSubTask" 
                                                                    data-id="{{ $item->id }}"
                                                                    data-task_id="{{ $item->task_id }}"
                                                                    data-user_id="{{ $item->user_id }}"
                                                                    data-nama_subtask="{{ $item->nama_subtask }}"  
                                                                    data-tgl_sub_task="{{ $item->tgl_sub_task }}"
                                                                    data-tgl_selesai="{{ $item->tgl_selesai }}"
                                                                    data-deadline="{{ $item->deadline }}"
                                                                    data-lampiran="{{ json_encode($item->lampiran) }}"
                                                                    data-bs-toggle="tooltip" title="Edit">
                                                                    <i class="ri-pencil-line"></i>
                                                                </button>
                                                                <form action="{{ ($userRole == 'admin-sdm') ? route('admin_sdm.subtask.delete', $item->id) : route('karyawan.subtask.delete', $item->id) }}" method="POST" class="d-inline">
                                                                    @csrf @method('DELETE')
                                                                    <button type="submit" class="btn btn-sm btn-icon btn-danger delete-sub-task" data-nama_subtask="{{ $item->nama_subtask }}" data-bs-toggle="tooltip" title="Hapus"><i class="ri-delete-bin-line"></i></button>
                                                                </form>
                                                            @endif
                                                            @if ($item->lampiran->count())
                                                                <button class="btn btn-sm btn-icon btn-primary view-lampiran-subtask" data-files='@json($item->lampiran->pluck("lampiran"))' data-title="{{ $item->nama_subtask }}" data-bs-toggle="tooltip" title="Lihat Lampiran"><i class="ri-attachment-2"></i></button>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr><td colspan="5" class="text-center py-5 text-muted"><i class="ri-inbox-line fs-24 d-block mb-2"></i> Belum ada sub task.</td></tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <div class="tab-pane" id="lampiran" role="tabpanel">
                                <div class="card border border-dashed shadow-none">
                                    <div class="card-body">
                                        <form action="{{ $config['lampiran_url'] }}" method="POST" enctype="multipart/form-data">
                                            @csrf @method('put')
                                            
                                            @php
                                                $file = $task->upload ?? null;
                                                $ext = $file ? strtolower(pathinfo($file, PATHINFO_EXTENSION)) : null;
                                            @endphp

                                            @if($file)
                                                <div class="text-center mb-4 p-3 bg-light rounded">
                                                    @if(in_array($ext, ['jpg', 'jpeg', 'png']))
                                                        <img src="{{ asset('uploads/' . $file) }}" class="img-fluid rounded shadow-sm" style="max-height: 300px;">
                                                    @elseif($ext == 'pdf')
                                                        <div class="ratio ratio-16x9">
                                                            <iframe src="{{ asset('uploads/' . $file) }}" class="rounded shadow-sm"></iframe>
                                                        </div>
                                                    @else
                                                        <div class="py-4">
                                                            <i class="ri-file-text-line display-1 text-primary"></i>
                                                            <p class="mt-2 text-muted fw-semibold">{{ $file }}</p>
                                                        </div>
                                                    @endif
                                                    
                                                    <div class="mt-3">
                                                        <a href="{{ asset('uploads/' . $file) }}" target="_blank" class="btn btn-sm btn-outline-primary me-2"><i class="ri-eye-line me-1"></i> Buka Full</a>
                                                        <a href="{{ asset('uploads/' . $file) }}" download class="btn btn-sm btn-primary"><i class="ri-download-line me-1"></i> Download</a>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="text-center py-5">
                                                    <div class="avatar avatar-xxl bg-light rounded-circle mb-3 text-muted border border-dashed">
                                                        <i class="ri-upload-cloud-2-line fs-30"></i>
                                                    </div>
                                                    <p class="text-muted">Belum ada lampiran utama untuk Task ini.</p>
                                                </div>
                                            @endif

                                            @if ($config['can_edit'])
                                                <hr class="my-4">
                                                <h6 class="fw-semibold mb-3">Update Lampiran & Keterangan</h6>
                                                <div class="mb-3">
                                                    <label class="form-label fs-12 text-muted">Upload File Baru</label>
                                                    <input type="file" class="form-control" name="upload">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fs-12 text-muted">Keterangan</label>
                                                    <textarea class="form-control" name="keterangan" rows="3" placeholder="Tambahkan catatan...">{{ old('keterangan', $task->keterangan) }}</textarea>
                                                </div>
                                                <button type="submit" class="btn btn-primary w-100 btn-wave"><i class="ri-save-line me-1"></i> Simpan Perubahan Lampiran</button>
                                            @else
                                                <div class="alert alert-light border mt-3" role="alert">
                                                    <strong>Keterangan:</strong> {{ $task->keterangan ?: '-' }}
                                                </div>
                                            @endif
                                        </form>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="tab-pane" id="anggota" role="tabpanel">
                                @if($config['can_manage_member'])
                                    <div class="d-flex justify-content-end mb-3">
                                        <button type="button" class="btn btn-primary btn-sm btn-wave" data-bs-toggle="modal" data-bs-target="#staticBackdropAnggota">
                                            <i class="ri-user-add-line me-1"></i> Tambah Anggota
                                        </button>
                                    </div>
                                @endif

                                <div class="row g-3">
                                    @forelse ($user as $item)
                                        <div class="col-xxl-6 col-md-6">
                                            <div class="card custom-card border shadow-none mb-0 h-100">
                                                <div class="card-body">
                                                    <div class="d-flex align-items-center">
                                                        <div class="me-3">
                                                            <span class="avatar avatar-lg rounded-circle">
                                                                <img src="{{ $item->user->dataDiri ? asset('uploads/' . $item->user->dataDiri->foto_user) : asset('/images/default-images.svg') }}" alt="img">
                                                            </span>
                                                        </div>
                                                        <div class="flex-fill">
                                                            <h6 class="mb-1 fw-bold">{{ $item->user->name }}</h6>
                                                            <p class="mb-1 fs-12 text-muted text-uppercase">{{ $item->user->dataDiri->kepegawaian->subJabatan->nama_sub_jabatan ?? 'Anggota Tim' }}</p>
                                                            <div class="d-flex gap-1">
                                                                @foreach ($item->user->socialMedias as $sosmed)
                                                                    <a href="{{ $sosmed->link }}" target="_blank" class="avatar avatar-xs bg-light text-muted rounded-circle">
                                                                        <i class="bx bxl-{{ strtolower($sosmed->nama_social_media) }}"></i>
                                                                    </a>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                        @if ($config['can_manage_member'])
                                                            <div class="ms-2">
                                                                <form action="{{ route($config['delete_member_prefix'] . '.delete.anggota.task', $item->id) }}" method="POST">
                                                                    @csrf @method('DELETE')
                                                                    <button type="submit" class="btn btn-icon btn-sm btn-outline-danger rounded-pill" data-bs-toggle="tooltip" title="Hapus Anggota">
                                                                        <i class="ri-delete-bin-line"></i>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-12 text-center py-4">
                                            <p class="text-muted">Belum ada anggota tim.</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="staticBackdropAnggota" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-bold">Tambah Anggota Tim</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ $config['add_member_url'] }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Pilih Anggota</label>
                            <select name="user[]" id="user2" multiple class="form-control" required>
                                @foreach($users as $u)
                                    <option value="{{ $u->id }}">{{ $u->name }}</option>
                                @endforeach
                            </select>
                            <div class="form-text">Bisa memilih lebih dari satu.</div>
                        </div>
                        <input type="hidden" name="task_id" value="{{ $task->id }}">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    @if (in_array($userRole, ['karyawan', 'admin-sdm']))
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title fw-bold" id="subtaskModalTitle"></h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="" method="POST" id="formSubTask" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Nama Sub Task <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="nama_subtask" id="nama_subtask" required placeholder="Contoh: Membuat Laporan Harian">
                            </div>
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Tanggal Mulai <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="ri-calendar-line"></i></span>
                                        <input type="text" class="form-control" name="format-tanggal" id="format-tanggal" placeholder="Pilih Tanggal" required>
                                        <input type="hidden" name="tanggal" id="tanggal">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Deadline <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="ri-calendar-event-fill"></i></span>
                                        <input type="text" class="form-control" name="format-deadline" id="format-deadline" placeholder="Pilih Deadline" required>
                                        <input type="hidden" name="deadline" id="deadline_sub">
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Lampiran Bukti (Opsional)</label>
                                <input type="file" class="form-control" name="upload[]" id="upload" multiple>
                                <div id="preview-area" class="row mt-3 g-2"></div>
                                <p class="text-center text-muted small mt-2" id="detail_upload"></p>
                            </div>
                            <input type="hidden" name="task_id" value="{{ $task->id }}">
                            <input type="hidden" name="user_id" value="{{ $uId }}">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary" id="btnSubmit">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
    
    <div class="modal fade" id="dynamicLampiranModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title fw-bold" id="lampiranModalTitle">Lampiran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0 bg-light">
                    <div id="dynamicCarousel" class="carousel slide" data-bs-ride="false">
                        <div class="carousel-inner" id="carouselInnerContent">
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#dynamicCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon bg-dark rounded-circle p-3" aria-hidden="true"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#dynamicCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon bg-dark rounded-circle p-3" aria-hidden="true"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <style>
        .flatpickr-calendar.open { z-index: 1060 !important; }
    </style>
    
    <script>
        $(document).ready(function(){
            const userRole = "{{ $userRole }}";
            const appUrl = "{{ asset('uploads/') }}";
            
            const choiceElements = ['#user2'];
            choiceElements.forEach(selector => {
                const element = document.querySelector(selector);
                if (element) new Choices(element, { removeItemButton: true, searchEnabled: true });
            });
            
            const dateConfig = {
                dateFormat: "Y-m-d",
                altInput: true,
                altFormat: "d F Y",
                locale: 'id',
                disableMobile: true
            };
            
            const minDateProject = "{{ $task->project_perusahaan && $task->project_perusahaan->waktu_mulai ? \Carbon\Carbon::parse($task->project_perusahaan->waktu_mulai)->format('Y-m-d') : '' }}";
            const maxDateProject = "{{ $task->project_perusahaan && $task->project_perusahaan->deadline ? \Carbon\Carbon::parse($task->project_perusahaan->deadline)->format('Y-m-d') : '' }}";

            let configMain = { ...dateConfig };
            if(minDateProject) configMain.minDate = minDateProject;
            if(maxDateProject) configMain.maxDate = maxDateProject;
            
            const fpMulai = flatpickr("#tgl_task", configMain);
            const fpDeadline = flatpickr("#deadline", configMain);
            const fpBerakhir = flatpickr("#tgl_selesai", { ...dateConfig, minDate: "{{ $startDate->format('Y-m-d') }}" }); 
            
            $('.btn-edit-task').click(function(e) {
                e.preventDefault();
                
                $(this).attr('hidden', true);
                $('.btn-batal-edit, .btn-submit-task').removeAttr('hidden');
                
                $('#nama_task').prop('disabled', false);
                
                $('#tgl_task, #deadline, #tgl_selesai').prop('disabled', false);
                
                if(fpMulai && fpMulai.altInput) fpMulai.altInput.disabled = false;
                if(fpDeadline && fpDeadline.altInput) fpDeadline.altInput.disabled = false;
                if(fpBerakhir && fpBerakhir.altInput) fpBerakhir.altInput.disabled = false;
            });

            $('.btn-batal-edit').click(function(e) {
                e.preventDefault();
                
                $('.btn-edit-task').removeAttr('hidden');
                $(this).attr('hidden', true);
                $('.btn-submit-task').attr('hidden', true);
                
                $('#nama_task').prop('disabled', true);
                
                $('#tgl_task, #deadline, #tgl_selesai').prop('disabled', true);
                
                if(fpMulai && fpMulai.altInput) fpMulai.altInput.disabled = true;
                if(fpDeadline && fpDeadline.altInput) fpDeadline.altInput.disabled = true;
                if(fpBerakhir && fpBerakhir.altInput) fpBerakhir.altInput.disabled = true;
            });
            
            const minDateTask = "{{ $task->tgl_task ? \Carbon\Carbon::parse($task->tgl_task)->format('Y-m-d') : '' }}";
            const maxDateTask = "{{ $task->deadline ? \Carbon\Carbon::parse($task->deadline)->format('Y-m-d') : '' }}";

            let configSub = { ...dateConfig };
            if(minDateTask) configSub.minDate = minDateTask;
            if(maxDateTask) configSub.maxDate = maxDateTask;
            
            let fpSubTaskStart = flatpickr("#format-tanggal", {
                ...configSub,
                onChange: (selectedDates, dateStr) => {
                    $("#tanggal").val(dateStr);
                    if(dateStr && fpSubTaskDeadline) fpSubTaskDeadline.set('minDate', dateStr);
                }
            });

            let fpSubTaskDeadline = flatpickr("#format-deadline", {
                ...configSub,
                onChange: (selectedDates, dateStr) => $("#deadline_sub").val(dateStr)
            });
            
            $('#staticBackdrop').on('hidden.bs.modal', function () {
                $('#formSubTask')[0].reset();
                $('#preview-area').empty();
                $('#detail_upload').text('');
                
                fpSubTaskStart.clear(); 
                if(minDateTask) fpSubTaskStart.set('minDate', minDateTask);
                
                fpSubTaskDeadline.clear(); 
                if(minDateTask) fpSubTaskDeadline.set('minDate', minDateTask);
                
                $('#formSubTask input[name="_method"]').remove();
            });
            
            $(document).on('click', '.tambahSubTask', function(e) {
                e.preventDefault();
                $("#subtaskModalTitle").text('Tambah Sub Task Baru');
                let actionUrl = (userRole === 'karyawan') ? "/karyawan/subtask/store" : "/admin_sdm/subtask/store";
                $("#formSubTask").attr("action", actionUrl);
                $("#btnSubmit").text("Simpan Sub Task");
            });
            
            $(document).on("click", ".updateSubTask", function(e) {
                e.preventDefault();
                let id = $(this).data("id");
                let actionUrl = (userRole === 'karyawan') ? "/karyawan/subtask/update/" + id : "/admin_sdm/subtask/update/" + id;
                
                $("#subtaskModalTitle").text("Edit Sub Task");
                $("#formSubTask").attr("action", actionUrl);
                $("#formSubTask input[name='_method']").remove();
                $("#formSubTask").append('<input type="hidden" name="_method" value="PUT">');
                $("#btnSubmit").text("Update Sub Task");
                
                $("#nama_subtask").val($(this).data("nama_subtask"));
                let start = $(this).data("tgl_sub_task");
                let end = $(this).data("deadline");
                
                if(start) { fpSubTaskStart.setDate(start, true); $("#tanggal").val(start); }
                if(end) { fpSubTaskDeadline.setDate(end, true); $("#deadline_sub").val(end); }
                
                let lampiran = $(this).data('lampiran');
                if (lampiran && lampiran.length > 0) {
                    $("#detail_upload").html(`<strong class="text-primary">${lampiran.length} file</strong> sudah terupload.`);
                } else {
                    $("#detail_upload").text("Belum ada lampiran.");
                }

                $('#staticBackdrop').modal('show');
            });
            
            $(document).on('click', '.view-lampiran-subtask', function() {
                let files = $(this).data('files');
                let title = $(this).data('title');
                let carouselContent = '';

                $('#lampiranModalTitle').text('Lampiran: ' + title);
                $('#carouselInnerContent').empty();

                if(files.length > 0){
                    files.forEach((file, index) => {
                        let activeClass = index === 0 ? 'active' : '';
                        let ext = file.split('.').pop().toLowerCase();
                        let contentHtml = '';

                        if(['jpg','jpeg','png'].includes(ext)) {
                            contentHtml = `<img src="${appUrl}/${file}" class="img-fluid rounded shadow-sm" style="max-height: 600px;">`;
                        } else if(ext === 'pdf') {
                            contentHtml = `<iframe src="${appUrl}/${file}" class="rounded shadow-sm" width="100%" height="600px"></iframe>`;
                        } else {
                            contentHtml = `
                                <div class="py-5 bg-white rounded">
                                    <i class="ri-file-zip-line display-1 text-muted"></i>
                                    <p class="mt-3">File tidak dapat dipreview.</p>
                                    <a href="${appUrl}/${file}" class="btn btn-primary"><i class="ri-download-line me-1"></i> Download File</a>
                                </div>`;
                        }

                        carouselContent += `
                            <div class="carousel-item ${activeClass} p-4 text-center">
                                ${contentHtml}
                                <div class="mt-3 text-muted small bg-white d-inline-block px-2 rounded">${file}</div>
                            </div>
                        `;
                    });
                    
                    if(files.length === 1) {
                        $('.carousel-control-prev, .carousel-control-next').hide();
                    } else {
                        $('.carousel-control-prev, .carousel-control-next').show();
                    }
                } else {
                    carouselContent = `<div class="text-center p-5">Tidak ada lampiran</div>`;
                }

                $('#carouselInnerContent').html(carouselContent);
                $('#dynamicLampiranModal').modal('show');
            });
            
            $(document).on("click", ".delete-sub-task", function(e){
                e.preventDefault();
                let nama = $(this).data("nama_subtask");
                let form = $(this).closest('form');
                Swal.fire({
                    title: "Hapus Sub Task?",
                    text: "Yakin menghapus '" + nama + "'?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Ya, Hapus!",
                    cancelButtonText: "Batal"
                }).then((result) => {
                    if (result.isConfirmed) form.submit();
                });
            });
            
            $("#upload").change(function () {
                const files = this.files;
                const previewArea = $("#preview-area");
                previewArea.html('');
                if (files.length > 0) {
                    Array.from(files).forEach(file => {
                        let iconClass = file.name.match(/\.(jpg|jpeg|png)$/i) ? 'ri-image-line text-success' : 'ri-file-line text-primary';
                        previewArea.append(`
                            <div class="col-md-6">
                                <div class="p-2 border rounded bg-light d-flex align-items-center">
                                    <i class="${iconClass} fs-18 me-2"></i>
                                    <span class="text-truncate small">${file.name}</span>
                                </div>
                            </div>
                        `);
                    });
                }
            });
            
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
        });
    </script>
@endsection