@extends('layouts.main')

@section('content')
    @php
        $userRole = Auth::check() ? Auth::user()->role->slug : '';
        $uId = auth()->id();

        // 1. Konfigurasi Hak Akses
        $config = [
            'is_manager' => $userRole == 'manager',
            'back_url' => '#',
            'action_update_project' => route('manajer.update.project', $project->id),
            'action_add_member' => route('manajer.update.anggota.project'),
            'action_create_task' => '/manajer/task/store'
        ];

        // Set Back URL
        if ($userRole == 'manager') $config['back_url'] = '/manajer/project';
        elseif ($userRole == 'karyawan') $config['back_url'] = '/karyawan/project';
        elseif ($userRole == 'admin-sdm') $config['back_url'] = '/admin_sdm/project';

        // Helper Dates
        $startDate = \Carbon\Carbon::parse($project->waktu_mulai);
        $deadlineDate = \Carbon\Carbon::parse($project->deadline);
        $endDate = $project->waktu_berakhir ? \Carbon\Carbon::parse($project->waktu_berakhir) : null;

        // [PERBAIKAN] Input State SELALU disabled di awal load, siapapun rolenya.
        // Nanti dibuka pakai tombol "Edit Data" (khusus Manager).
        $inputState = 'disabled';
        
        // Progress Color Logic
        $progressColor = '#00ff00'; // Green
        if($progress < 30) $progressColor = '#ff0000'; // Red
        elseif($progress < 70) $progressColor = '#ffd700'; // Yellow
    @endphp

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card custom-card border-top-card border-top-primary rounded-0 rounded-bottom">
                    {{-- HEADER CARD DENGAN TOMBOL KEMBALI DI KANAN --}}
                    <div class="card-header justify-content-between align-items-center">
                        <div class="card-title">
                            <i class="ri-bar-chart-grouped-line me-2 text-primary"></i> Analisa Performa Project
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ $config['back_url'] }}" class="btn btn-light btn-sm btn-wave" data-bs-toggle="tooltip" title="Kembali ke Daftar Project">
                                <i class="ri-arrow-left-line me-1 align-middle"></i> Kembali
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-xl-3 col-lg-4 text-center border-end">
                                <div id="progres-bar"></div>
                                <h6 class="fw-bold mt-2">Total Progress</h6>
                                <p class="text-muted fs-12 mb-0">Berdasarkan penyelesaian Task</p>
                            </div>

                            <div class="col-xl-9 col-lg-8">
                                <div class="table-responsive">
                                    <table class="table table-bordered text-nowrap mb-0 align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="text-center text-uppercase fs-12 fw-bold">Indikator</th>
                                                <th class="text-center text-uppercase fs-12 fw-bold">Target</th>
                                                <th class="text-center text-uppercase fs-12 fw-bold">Aktual</th>
                                                <th class="text-center text-uppercase fs-12 fw-bold">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="fw-semibold">Waktu Mulai</td>
                                                <td class="text-center text-muted">{{ $startDate->translatedFormat('d F Y') }}</td>
                                                <td class="text-center fw-bold text-success">{{ $startDate->translatedFormat('d F Y') }}</td>
                                                <td class="text-center"><span class="badge bg-success-transparent rounded-pill"><i class="ri-check-line"></i> OK</span></td>
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
                                                        @if($diff > 0) <span class="badge bg-danger-transparent rounded-pill">Telat {{ $diff }} Hari</span>
                                                        @elseif($diff < 0) <span class="badge bg-success-transparent rounded-pill">Cepat {{ abs($diff) }} Hari</span>
                                                        @else <span class="badge bg-info-transparent rounded-pill">Tepat Waktu</span> @endif
                                                    @else
                                                        @if(\Carbon\Carbon::now()->gt($deadlineDate)) <span class="badge bg-danger-transparent rounded-pill">Overdue</span>
                                                        @else <span class="badge bg-light text-dark border">On Track</span> @endif
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="fw-semibold text-primary">Durasi Project</td>
                                                <td class="text-center text-muted">{{ $startDate->diffInDays($deadlineDate) }} Hari</td>
                                                <td class="text-center fw-bold">
                                                    @if($endDate) {{ $startDate->diffInDays($endDate) }} Hari
                                                    @else <span class="text-muted fw-normal fst-italic">Berjalan {{ $startDate->diffInDays(now()) }} Hari</span> @endif
                                                </td>
                                                <td class="text-center">
                                                    @if($endDate && $startDate->diffInDays($deadlineDate) > 0)
                                                        @php
                                                            $percentageUsed = ($startDate->diffInDays($endDate) / $startDate->diffInDays($deadlineDate)) * 100;
                                                            $efficiency = 100 - $percentageUsed;
                                                        @endphp
                                                        @if($efficiency >= 0) <span class="text-success fw-bold">+{{ round($efficiency) }}% Efisien</span>
                                                        @else <span class="text-danger fw-bold">{{ round($efficiency) }}% Inefisien</span> @endif
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
            </div>
        </div>

        <div class="row">
            
            <div class="col-xl-4 col-lg-5">
                <div class="card custom-card overflow-hidden">
                    <div class="card-header border-bottom border-block-end-dashed">
                        <div class="card-title">Informasi Project</div>
                    </div>
                    <div class="card-body pt-4">
                        @if ($errors->any())
                            <div class="alert alert-danger mb-3">
                                <ul class="mb-0">@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
                            </div>
                        @endif

                        <form action="{{ $config['action_update_project'] }}" method="post">
                            @csrf @method('put')
                            
                            <div class="mb-3">
                                <label class="form-label fs-13 text-muted">Nama Instansi</label>
                                <select name="perusahaan_id" class="form-control" id="perusahaan_id" {{ $inputState }}>
                                    @foreach ($perusahaan as $row)
                                        <option value="{{ $row->id }}" {{ $project->perusahaan_id == $row->id ? 'selected' : '' }}>
                                            {{ $row->nama_perusahaan }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fs-13 text-muted">Nama Project</label>
                                <input type="text" name="nama_project" id="nama_project" class="form-control fw-bold" 
                                       value="{{ $project->nama_project }}" {{ $inputState }}>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fs-13 text-muted">Status</label>
                                <select class="form-control" name="status" id="status" {{ $inputState }}>
                                    <option value="belum" {{ $project->status == 'belum' ? 'selected' : '' }}>Belum</option>
                                    <option value="proses" {{ $project->status == 'proses' ? 'selected' : '' }}>Proses</option>
                                    <option value="selesai" {{ $project->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                </select>
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label fs-13 text-muted">Mulai</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="ri-calendar-line text-muted"></i></span>
                                        <input type="text" class="form-control border-start-0 ps-0" name="waktu_mulai" id="waktu_mulai" 
                                               value="{{ $project->waktu_mulai }}" {{ $inputState }}>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label class="form-label fs-13 text-muted">Deadline</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="ri-calendar-event-line text-muted"></i></span>
                                        <input type="text" class="form-control border-start-0 ps-0" name="deadline" id="deadline" 
                                               value="{{ $project->deadline }}" {{ $inputState }}>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fs-13 text-muted">Tanggal Selesai (Aktual)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="ri-checkbox-circle-line text-success"></i></span>
                                    <input type="text" class="form-control border-start-0 ps-0" name="waktu_berakhir" id="waktu_berakhir" 
                                           value="{{ $project->waktu_berakhir }}" placeholder="Belum Selesai" {{ $inputState }}>
                                </div>
                            </div>

                            @if ($config['is_manager'])
                                <div class="d-grid gap-2 mt-4">
                                    <button type="button" class="btn btn-warning-light btn-wave btn-edit-project">
                                        <i class="ri-pencil-line me-1"></i> Edit Data
                                    </button>
                                    <button type="button" class="btn btn-danger-light btn-wave btn-batal-edit" hidden>
                                        <i class="ri-close-line me-1"></i> Batal Edit
                                    </button>
                                    <button type="submit" class="btn btn-primary btn-wave btn-submit-project" hidden>
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
                            <a class="nav-link active py-3" data-bs-toggle="tab" href="#timeline" role="tab">
                                <i class="ri-calendar-check-line me-1 align-middle fs-16"></i> Timeline
                            </a>
                            <a class="nav-link py-3" data-bs-toggle="tab" href="#tasks" role="tab">
                                <i class="ri-list-check-2 me-1 align-middle fs-16"></i> Daftar Task
                            </a>
                            <a class="nav-link py-3" data-bs-toggle="tab" href="#anggota" role="tab">
                                <i class="ri-group-line me-1 align-middle fs-16"></i> Anggota
                            </a>
                        </nav>

                        <div class="tab-content p-4">
                            
                            {{-- TAB 1: TIMELINE (CALENDAR) --}}
                            <div class="tab-pane active" id="timeline" role="tabpanel">
                                <div id='calendar2'></div>
                            </div>

                            {{-- TAB 2: DAFTAR TASK --}}
                            <div class="tab-pane" id="tasks" role="tabpanel">
                                @if ($config['is_manager'])
                                    <div class="mb-3">
                                        <button class="btn btn-outline-primary btn-wave w-100 btn-dashed tambahTask" 
                                                data-bs-toggle="modal" data-bs-target="#modalTask">
                                            <i class="ri-add-circle-line me-1"></i> Buat Task Baru
                                        </button>
                                    </div>
                                @endif

                                <div class="table-responsive">
                                    <table id="datatable-basic" class="table table-hover text-nowrap w-100">
                                        <thead class="bg-light">
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Task</th>
                                                <th>Deadline</th>
                                                <th class="text-center">Status</th>
                                                <th class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php 
                                                // Jika manager lihat semua, jika karyawan lihat userTasks
                                                $taskList = $config['is_manager'] ? $tasks : $userTasks->map(fn($ut) => $ut->task); 
                                            @endphp

                                            @forelse ($taskList as $item)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td><span class="fw-semibold text-dark">{{ $item->nama_task }}</span></td>
                                                    <td class="text-muted">
                                                        {{ $item->deadline ? Carbon\Carbon::parse($item->deadline)->translatedFormat('d M Y') : '-' }}
                                                    </td>
                                                    <td class="text-center">
                                                        @if ($item->status == 'selesai')
                                                            <span class="badge bg-success-transparent">Selesai</span>
                                                        @elseif ($item->deadline && \Carbon\Carbon::parse($item->deadline)->isPast())
                                                            <span class="badge bg-danger-transparent">Overdue</span>
                                                        @elseif ($item->status == 'proses')
                                                            <span class="badge bg-info-transparent">Proses</span>
                                                        @else
                                                            <span class="badge bg-secondary-transparent">Belum</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="d-flex justify-content-center gap-2">
                                                            @php
                                                                $detailRoute = '#';
                                                                if($userRole == 'manager') $detailRoute = route('manajer.detail.task', $item->id);
                                                                elseif($userRole == 'karyawan') $detailRoute = route('karyawan.detail.task', $item->id);
                                                                elseif($userRole == 'admin-sdm') $detailRoute = route('admin_sdm.detail.task', $item->id);
                                                            @endphp
                                                            <a href="{{ $detailRoute }}" class="btn btn-sm btn-icon btn-dark" data-bs-toggle="tooltip" title="Detail Task">
                                                                <i class="ri-file-list-line"></i>
                                                            </a>
                                                            
                                                            @if ($config['is_manager'])
                                                                <button class="btn btn-sm btn-icon btn-warning updateTask" 
                                                                    data-id="{{ $item->id }}"
                                                                    data-nama_task="{{ $item->nama_task }}"
                                                                    data-tgl_task="{{ $item->tgl_task }}"
                                                                    data-deadline_task="{{ $item->deadline }}"
                                                                    data-keterangan="{{ $item->keterangan }}"
                                                                    data-project="{{ $item->project_perusahaan_id }}"
                                                                    data-user="{{ Auth::user()->id }}"
                                                                    data-users="{{ json_encode($item->users_task->pluck('id')->toArray()) }}"
                                                                    data-upload="{{ $item->upload }}" 
                                                                    data-bs-toggle="modal" data-bs-target="#modalTask"
                                                                    title="Edit">
                                                                    <i class="ri-pencil-line"></i>
                                                                </button>

                                                                <form action="{{ route('manajer.delete.task', $item->id) }}" method="POST" class="d-inline">
                                                                    @csrf @method('DELETE')
                                                                    <button type="button" class="btn btn-sm btn-icon btn-danger delete-task" 
                                                                        data-id="{{ $item->id }}" data-nama_task="{{ $item->nama_task }}" title="Hapus">
                                                                        <i class="ri-delete-bin-line"></i>
                                                                    </button>
                                                                </form>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr><td colspan="5" class="text-center text-muted py-4">Belum ada task.</td></tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            {{-- TAB 3: ANGGOTA --}}
                            <div class="tab-pane" id="anggota" role="tabpanel">
                                @if ($config['is_manager'])
                                    <div class="d-flex justify-content-end mb-3">
                                        <button class="btn btn-primary btn-sm btn-wave" data-bs-toggle="modal" data-bs-target="#staticBackdropAnggota">
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
                                                            <p class="mb-1 fs-12 text-muted text-uppercase">{{ $item->user->dataDiri->kepegawaian->subJabatan->nama_sub_jabatan ?? 'Anggota' }}</p>
                                                        </div>
                                                        @if ($config['is_manager'])
                                                            <div class="ms-2">
                                                                <form action="{{ route('manajer.delete.anggota.project', $item->id) }}" method="POST">
                                                                    @csrf @method('DELETE')
                                                                    <button type="button" class="btn btn-icon btn-sm btn-outline-danger rounded-pill delete-anggota-project" 
                                                                        data-id="{{ $item->id }}" data-nama="{{ $item->user->name }}" title="Hapus">
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
                                        <div class="col-12 text-center py-4 text-muted">Belum ada anggota.</div>
                                    @endforelse
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ================= MODALS ================= --}}

    {{-- 1. Modal Task (Create/Edit) --}}
    <div class="modal fade" id="modalTask" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-bold">Buat Task</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="POST" id="formTask" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Task</label>
                            <input type="text" class="form-control" name="nama_task" id="nama_task_modal" required>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Tanggal Mulai</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="ri-calendar-line"></i></span>
                                    <input type="text" class="form-control" id="format-waktu_mulai" placeholder="Pilih Tanggal" required>
                                    <input type="hidden" name="tgl_task" id="tgl_task_modal">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Deadline</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="ri-calendar-event-fill"></i></span>
                                    <input type="text" class="form-control" id="format-deadline_task" placeholder="Pilih Deadline" required>
                                    <input type="hidden" name="deadline_task" id="deadline_task_modal">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Keterangan</label>
                            <textarea class="form-control" name="keterangan" id="keterangan_modal" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Anggota Task</label>
                            <select name="user[]" id="user_modal" multiple class="form-control">
                                @foreach ($user as $item)
                                    <option value="{{ $item->user_id }}">{{ $item->user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Lampiran</label>
                            <input type="file" class="form-control" name="upload" id="upload">
                            <div id="preview-area" class="mt-2 text-center">
                                <img id="previewImage" src="" class="img-fluid rounded" style="max-height: 200px; display:none;">
                                <iframe id="previewPDF" src="" width="100%" height="300px" style="display:none;" class="rounded border"></iframe>
                                <p id="detail_upload" class="text-muted small mt-2"></p>
                            </div>
                        </div>

                        <input type="hidden" name="project_perusahaan_id" value="{{ $project->id }}">
                        <input type="hidden" name="tipe_task" value="task-project">
                        <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                        <input type="hidden" name="task_id" id="task_id_modal">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="btnSubmitTask">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- 2. Modal Tambah Anggota Project --}}
    <div class="modal fade" id="staticBackdropAnggota" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-bold">Tambah Anggota Project</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ $config['action_add_member'] }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Pilih Anggota</label>
                            <select name="user[]" id="user2" multiple class="form-control">
                                @foreach ($users as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <input type="hidden" name="project_perusahaan_id" value="{{ $project->id }}">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- 3. Modal Event Calendar --}}
    <div class="modal fade" id="infoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-bold" id="eventTitle"></h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul id="eventBody" class="list-unstyled mb-0"></ul>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    {{-- Style Flatpickr --}}
    <style>
        .flatpickr-calendar.open { z-index: 1060 !important; }
    </style>

    {{-- APEX CHART --}}
    <script>
        // ... (Kode ApexChart Anda sudah benar, biarkan saja) ...
        var options = {
            series: [{{ $progress }}],
            chart: { type: 'radialBar', height: 300, offsetY: -10, sparkline: { enabled: true } },
            plotOptions: { radialBar: { startAngle: -90, endAngle: 90, track: { background: "#e7e7e7", strokeWidth: '97%', margin: 5 }, dataLabels: { name: { show: false }, value: { offsetY: -2, fontSize: '22px', fontWeight: 'bold' } } } },
            colors: ['{{ $progressColor }}'],
            fill: { type: 'gradient', gradient: { shade: 'light', shadeIntensity: 0.4, inverseColors: false, opacityFrom: 1, opacityTo: 1, stops: [0, 50, 53, 91] } },
            labels: ['Progress'],
        };
        var chart = new ApexCharts(document.querySelector("#progres-bar"), options);
        chart.render();
    </script>

    {{-- FULL CALENDAR --}}
    <script>
         // ... (Kode FullCalendar Anda sudah benar, biarkan saja) ...
        document.addEventListener('DOMContentLoaded', function() {
            let calendarEl = document.getElementById('calendar2');
            if (calendarEl) {
                let calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    headerToolbar: { left: 'prev,next today', center: 'title', right: 'dayGridMonth,timeGridWeek,timeGridDay' },
                    events: @json($events),
                    dateClick: function(info) {
                        let selectedDate = info.dateStr;
                        let filteredEvents = calendar.getEvents().filter(event => event.startStr.startsWith(selectedDate));
                        let title = filteredEvents.length > 0 ? "Events pada " + selectedDate : "Tidak ada event pada " + selectedDate;
                        let body = filteredEvents.length > 0 ? filteredEvents.map(e => `<li><i class='ri-checkbox-blank-circle-fill text-primary me-2 fs-10'></i>${e.title}</li>`).join('') : '<li class="text-muted">Tidak ada event.</li>';
                        $('#eventTitle').text(title);
                        $('#eventBody').html(body);
                        new bootstrap.Modal(document.getElementById('infoModal')).show();
                    }
                });
                $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
                    if(e.target.href.includes("#timeline")) calendar.render();
                });
                calendar.render();
            }
        });
    </script>

    {{-- MAIN SCRIPT (FIXED) --}}
    <script>
        $(document).ready(function() {
            // Choices JS
            const choicesInstances = {};
            ['#perusahaan_id', '#status', '#user_modal', '#user2'].forEach(id => {
                const el = document.querySelector(id);
                if(el) {
                    choicesInstances[id] = new Choices(el, { removeItemButton: true, searchEnabled: true });
                }
            });

            // --- FLATPICKR CONFIGURATION ---
            const dateConfig = { dateFormat: "Y-m-d", altInput: true, altFormat: "d F Y", locale: 'id', disableMobile: true };
            const projectStart = "{{ $project->waktu_mulai }}";
            const projectEnd = "{{ $project->deadline }}";

            // Inisialisasi Flatpickr untuk Form Info Project (Kolom Kiri)
            // Simpan instance ke variabel agar mudah diakses jika perlu (opsional)
            const fpMulai = flatpickr("#waktu_mulai", dateConfig);
            const fpDeadline = flatpickr("#deadline", dateConfig);
            const fpBerakhir = flatpickr("#waktu_berakhir", dateConfig);

            // Inisialisasi Flatpickr untuk Modal Task (Create/Edit)
            let fpTaskStart = flatpickr("#format-waktu_mulai", {
                ...dateConfig,
                minDate: projectStart, maxDate: projectEnd,
                onChange: (selectedDates, dateStr) => {
                    $("#tgl_task_modal").val(dateStr);
                    if(dateStr) fpTaskDeadline.set('minDate', dateStr);
                }
            });

            let fpTaskDeadline = flatpickr("#format-deadline_task", {
                ...dateConfig,
                minDate: projectStart, maxDate: projectEnd,
                onChange: (selectedDates, dateStr) => $("#deadline_task_modal").val(dateStr)
            });


            // --- UI INTERACTION: EDIT PROJECT INFO (FIXED) ---
            $('.btn-edit-project').click(function(e) {
                e.preventDefault(); // Mencegah refresh halaman
                
                $(this).hide();
                $('.btn-batal-edit, .btn-submit-project').removeAttr('hidden');
                
                // [FIX] Enable Standard Inputs
                // Pastikan ID selector sesuai dengan HTML: #nama_project, #waktu_mulai, #deadline, #waktu_berakhir
                $('#nama_project, #waktu_mulai, #deadline, #waktu_berakhir').prop('disabled', false);
                
                // Enable Choices JS Fields
                if(choicesInstances['#perusahaan_id']) choicesInstances['#perusahaan_id'].enable();
                if(choicesInstances['#status']) choicesInstances['#status'].enable();
            });

            $('.btn-batal-edit').click(function(e) {
                e.preventDefault();

                $('.btn-edit-project').show();
                $(this).attr('hidden', true);
                $('.btn-submit-project').attr('hidden', true);
                
                // Disable Inputs
                $('#nama_project, #waktu_mulai, #deadline, #waktu_berakhir').prop('disabled', true);
                
                // Disable Choices JS Fields
                if(choicesInstances['#perusahaan_id']) choicesInstances['#perusahaan_id'].disable();
                if(choicesInstances['#status']) choicesInstances['#status'].disable();
            });


            // --- LOGIC MODAL TASK & DELETE (SAMA SEPERTI SEBELUMNYA) ---
            
            // Create Task
            $(".tambahTask").click(function() {
                $(".modal-title").text("Buat Task Baru");
                $("#formTask").attr("action", "{{ $config['action_create_task'] }}");
                $("#formTask input[name='_method']").remove();
                
                $("#formTask")[0].reset();
                $("#tgl_task_modal, #deadline_task_modal, #task_id_modal").val('');
                fpTaskStart.clear(); fpTaskDeadline.clear();
                
                $("#previewImage, #previewPDF").hide();
                $("#detail_upload").text("");
                
                $(".choices").show();
            });

            // Edit Task
            $(".updateTask").click(function(e) {
                e.preventDefault();
                let id = $(this).data("id");
                
                $(".modal-title").text("Update Task");
                $("#formTask").attr("action", "/manajer/task/update/" + id);
                $("#formTask input[name='_method']").remove();
                $("#formTask").append('<input type="hidden" name="_method" value="PUT">');

                // Fill Data
                $("#nama_task_modal").val($(this).data("nama_task"));
                $("#keterangan_modal").val($(this).data("keterangan"));
                
                let tgl = $(this).data("tgl_task");
                let ddl = $(this).data("deadline_task");

                if(tgl) { fpTaskStart.setDate(tgl, true); $("#tgl_task_modal").val(tgl); }
                if(ddl) { fpTaskDeadline.setDate(ddl, true); $("#deadline_task_modal").val(ddl); }

                // Upload Preview
                let upload = $(this).data("upload");
                $("#previewImage, #previewPDF").hide();
                if (upload) {
                    let fileUrl = "/uploads/" + upload;
                    let ext = upload.split('.').pop().toLowerCase();
                    if (['jpg','jpeg','png'].includes(ext)) {
                        $("#previewImage").attr("src", fileUrl).show();
                    } else if (ext === 'pdf') {
                        $("#previewPDF").attr("src", fileUrl).show();
                    }
                    $("#detail_upload").html(`<a href="${fileUrl}" target="_blank" class="text-primary">Lihat File Lama</a>`);
                } else {
                    $("#detail_upload").text("Tidak ada lampiran sebelumnya.");
                }
            });

            // Delete Confirmations
            $(".delete-task, .delete-anggota-project").click(function(e) {
                e.preventDefault();
                let form = $(this).closest('form');
                let name = $(this).data("nama_task") || $(this).data("nama");
                let type = $(this).hasClass('delete-task') ? 'Task' : 'Anggota';

                Swal.fire({
                    title: `Hapus ${type}?`,
                    text: `Yakin ingin menghapus ${name}?`,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    confirmButtonText: "Ya, Hapus!",
                    cancelButtonText: "Batal"
                }).then((result) => {
                    if (result.isConfirmed) form.submit();
                });
            });

            // Bootstrap Tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
        });
    </script>
@endsection