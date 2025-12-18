@extends('layouts.main')

@section('content')
    <div class="modal fade" id="staticBackdropAnggota" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="staticBackdropLabel">Tambah Anggota</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ 
                    (Auth::user()->role->slug == 'manager') ? route('manajer.update.anggota.task') :
                    ((Auth::user()->role->slug == 'admin-sdm') ? route('admin_sdm.update.anggota.task') : 
                    route('karyawan.update.anggota.task'))
                }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2" for="user">Anggota Task</label>
                                <div class="col-sm-10">
                                    <select name="user[]" id="user2" multiple class="form-control">
                                        @foreach($users as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="task_id" id="task_id" value="{{ $task->id }}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kembali</button>
                        <button type="submit" class="btn btn-primary" id="btnSubmit">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @if (Auth::check() && Auth::user()->role->slug == 'karyawan' || Auth::check() && Auth::user()->role->slug == 'admin-sdm')
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="staticBackdropLabel"></h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="" method="POST" id="formSubTask" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="nama_subtask">Nama Sub Task</label>
                                    <input type="text" class="form-control" name="nama_subtask" id="nama_subtask">
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="format-tanggal">Tanggal Mulai</label>
                                    <div class="input-group">
                                        <div class="input-group-text text-muted"> <i class="ri-calendar-line"></i> </div>
                                        <input type="text" class="form-control" name="format-tanggal" id="format-tanggal" placeholder="Tanggal Mulai" required>
                                        <input type="hidden" name="tanggal" id="tanggal">
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="format-deadline">Deadline</label>
                                    <div class="input-group">
                                        <div class="input-group-text text-muted"> <i class="ri-calendar-line"></i> </div>
                                        <input type="text" class="form-control" name="format-deadline" id="format-deadline" placeholder="Deadline" required>
                                        <input type="hidden" name="deadline" id="deadline">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="upload">Lampiran</label>
                                    <input type="file" class="form-control" name="upload[]" id="upload" multiple>
                                    <div id="preview-area" class="row mt-3"></div>
                                    <p class="text-center" id="detail_upload"></p>
                            </div>
                            <input type="hidden" name="task_id" id="task_id" value="{{ $task->id }}">
                            <input type="hidden" name="user_id" id="user_id" value="{{ auth()->user()->id }}">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kembali</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>        
    @endif
    @foreach ($subTask as $item)
        @if ($item->lampiran->count())
        <div class="modal fade" id="lampiranModal{{ $item->id }}" tabindex="-1" aria-labelledby="lampiranModalLabel{{ $item->id }}" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Lampiran Sub Task - {{ $loop->iteration }}</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  @if ($item->lampiran->count())
                  <div id="carouselLampiran{{ $item->id }}" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                      @foreach ($item->lampiran as $key => $lampiran)
                        @php
                          $file = $lampiran->lampiran;
                          $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                          $isImage = in_array($extension, ['jpg', 'jpeg', 'png']);
                          $isPDF = $extension === 'pdf';
                        @endphp
                        <div class="carousel-item {{ $key === 0 ? 'active' : '' }}">
                          @if ($isImage)
                            <img src="{{ asset('uploads/' . $file) }}" class="d-block mx-auto img-fluid" style="max-height: 500px;">
                          @elseif ($isPDF)
                            <iframe src="{{ asset('uploads/' . $file) }}" class="d-block mx-auto" width="100%" height="500px"></iframe>
                          @else
                            <div class="text-center text-muted">File tidak bisa dipreview: <a href="{{ asset('uploads/' . $file) }}" target="_blank">Download</a></div>
                          @endif
                        </div>
                      @endforeach
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselLampiran{{ $item->id }}" data-bs-slide="prev">
                      <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselLampiran{{ $item->id }}" data-bs-slide="next">
                      <span class="carousel-control-next-icon"></span>
                    </button>
                  </div>
                  @else
                    <p class="text-center text-muted">Tidak ada lampiran yang tersedia</p>
                  @endif
                </div>
              </div>
            </div>
          </div>          
        @endif
    @endforeach
    <div class="container-fluid">
        @php
            $actionUrl = '';
            $lampiranActionUrl = '';
            $userRole = Auth::check() ? Auth::user()->role->slug : '';
            $tipeTaskSlug = $task->tipe_task ? $task->tipe_task->slug : '';

            $isDisabled = true;
            $isProjectFieldDisabled = true;
            $showButton = false;

            $canManageMember = false;
            $addMemberUrl = '';
            $deleteMemberPrefix = '';

            if ($userRole == 'manager' && $tipeTaskSlug == 'task-project') {
                $isDisabled = false;
                $isProjectFieldDisabled = false;
                $showButton = true;
                $actionUrl = route('manajer.update.detail.task', $task->id); 
                $lampiranActionUrl = route('manajer.update.lampiran.task', $task->id);
                $canManageMember = true;
                $addMemberUrl = route('manajer.update.anggota.task');
                $deleteMemberPrefix = 'manajer';
            } elseif ($userRole == 'admin-sdm' && ($tipeTaskSlug != 'task-project')) {
                $isDisabled = false;
                $isProjectFieldDisabled = true; 
                $showButton = true;
                $actionUrl = route('admin_sdm.update.detail.task', $task->id); 
                $lampiranActionUrl = route('admin_sdm.update.lampiran.task', $task->id);
                $canManageMember = true;
                $addMemberUrl = route('admin_sdm.update.anggota.task');
                $deleteMemberPrefix = 'admin_sdm';
            } elseif ($userRole == 'karyawan' && $tipeTaskSlug == 'task-tambahan') {
                $isDisabled = false;
                $isProjectFieldDisabled = true;
                $showButton = true;
                $actionUrl = route('karyawan.update.detail.task', $task->id); 
                $lampiranActionUrl = route('karyawan.update.lampiran.task', $task->id);
                $canManageMember = true;
                $addMemberUrl = route('karyawan.update.anggota.task');
                $deleteMemberPrefix = 'karyawan';
            }
        @endphp
        <div class="mb-3">
            @if ($userRole == 'manager')
                <a href="/manajer/task" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
                @if ($task->project_perusahaan != null)
                    <a href="/manajer/project/detail/{{ $task->project_perusahaan->id }}" class="btn btn-secondary">Kembali Ke Project</a>
                @endif
            @elseif ($userRole == 'karyawan')
                <a href="/karyawan/task" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
                @if ($task->project_perusahaan != null)
                    <a href="/karyawan/project/detail/{{ $task->project_perusahaan->id }}" class="btn btn-secondary">Kembali Ke Project</a>
                @endif
            @elseif ($userRole == 'admin-sdm')
                <a href="/admin_sdm/task" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
                @if ($task->project_perusahaan != null)
                    <a href="/admin_sdm/project/detail/{{ $task->project_perusahaan->id }}" class="btn btn-secondary">Kembali Ke Project</a>
                @endif
            @endif
        </div>
        <div class="row row-sm">
            <div class="col-xl-4 col-lg-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="ps-0">
                            <div class="main-profile-overview">
                                <div class="d-flex justify-content-between mb-4">
                                    <div>
                                        <h5 class="main-profile-name" style="text-transform: capitalize;">
                                            {{ $task->nama_task }}</h5>
                                            @if ($task->status == 'selesai')
                                                @if ($task->tgl_selesai && \Carbon\Carbon::parse($task->tgl_selesai)->gt(\Carbon\Carbon::parse($task->deadline)))
                                                    <span class="badge bg-warning fs-6 px-2 py-1 rounded-pill">Selesai (Telat)</span>
                                                @else
                                                    <span class="badge bg-success fs-6 px-2 py-1 rounded-pill">Selesai</span>
                                                @endif
                                            @else
                                                @if ($progressPercentage === 100)
                                                    <span class="badge bg-success fs-6 px-2 py-1 rounded-pill">Selesai (100%)</span>
                                                @else
                                                    @if ($task->deadline && \Carbon\Carbon::parse($task->deadline)->isPast())
                                                        <span class="badge bg-danger fs-6 px-2 py-1 rounded-pill">Telat</span>
                                                    @else
                                                        @if ($task->status == 'proses')
                                                            <span class="badge bg-info fs-6 px-2 py-1 rounded-pill">Proses ({{ $progressPercentage }}%)</span>
                                                        @elseif ($task->status == 'belum')
                                                            <span class="badge bg-secondary fs-6 px-2 py-1 rounded-pill">Belum</span>
                                                        @endif
                                                    @endif
                                                @endif
                                            @endif
                                    </div>
                                </div>
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <div class="modal fade" id="infoModal" tabindex="-1"
                                    aria-labelledby="exampleModalScrollable2" data-bs-keyboard="false" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h6 class="modal-title" id="eventTitle"></h6>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <ul id="eventBody"></ul>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Kembali</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="container-project">
                                    <form action="{{ $actionUrl }}" method="post" enctype="multipart/form-data">
                                        @csrf
                                        @method('put')
                                        <div class="form-group">
                                            <label for="tipe_task">Tipe Task</label>
                                            <select name="tipe_task" id="tipe_task" class="form-control" 
                                                {{ $task->tipe_task == null ? '' : 'disabled' }} required
                                                {{ $isDisabled ? 'disabled' : '' }}>
                                                @if ($task->tipe_task == null)
                                                    <option value="" selected disabled>Pilih Tipe Task</option>
                                                    @foreach ($tipeTask as $item)
                                                        <option value="{{ $item->slug }}">{{ $item->nama_tipe }}</option>
                                                    @endforeach
                                                @else
                                                    <option value="{{ $task->tipe_task->slug }}" selected>{{ $task->tipe_task->nama_tipe }}</option>
                                                    @foreach ($tipeTask as $item)
                                                        <option value="{{ $item->slug }}">{{ $item->nama_tipe }}</option>
                                                    @endforeach      
                                                @endif
                                            </select>
                                            @if ($task->tipe_task != null)
                                                <input type="hidden" name="tipe_task" value="{{ $task->tipe_task->slug }}">
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label for="nama_project" class="form-label">Nama Project</label>
                                            @if ($task->project_perusahaan == null)
                                                <select name="nama_project" id="nama_project" data-trigger class="form-control"
                                                    {{ $isProjectFieldDisabled ? 'disabled' : '' }}>
                                                    <option value="" selected disabled>Pilih Project</option>
                                                    @foreach ($project as $item)
                                                        <option value="{{ $item->id }}">{{ $item->nama_project }} ({{ $item->perusahaan->nama_perusahaan }})</option>
                                                    @endforeach
                                                </select>
                                            @else
                                                <select name="nama_project" id="nama_project" data-trigger class="form-control"
                                                    {{ $isProjectFieldDisabled ? 'disabled' : '' }}>
                                                    <option value="{{ $task->project_perusahaan->id }}" selected>{{ $task->project_perusahaan->nama_project }} ({{ $task->project_perusahaan->perusahaan->nama_perusahaan }})</option>
                                                    @foreach ($project as $item)
                                                        <option value="{{ $item->id }}">{{ $item->nama_project }} ({{ $item->perusahaan->nama_perusahaan }})</option>
                                                    @endforeach
                                                </select>
                                            @endif
                                            
                                            @if ($isProjectFieldDisabled && $task->project_perusahaan != null)
                                                <input type="hidden" name="nama_project" value="{{ $task->project_perusahaan->id }}">
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label for="nama_task" class="form-label">Nama Task</label>
                                            <input type="text" name="nama_task" id="nama_task" class="form-control"
                                                value="{{ $task->nama_task }}"
                                                {{ $isDisabled ? 'disabled' : '' }}>
                                        </div>
                                        <div class="form-group">
                                            <label for="tgl_task">Tanggal Mulai</label>
                                            <div class="input-group">
                                                <div class="input-group-text text-muted"><i class="ri-calendar-line"></i></div>
                                                <input type="text" class="form-control" name="tgl_task"
                                                    value="{{ $task->tgl_task != null ? $task->tgl_task : '' }}"
                                                    id="tgl_task" placeholder="Tanggal Mulai"
                                                    {{ $isDisabled ? 'disabled' : '' }}>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="tgl_selesai">Tanggal Selesai</label>
                                            <div class="input-group">
                                                <div class="input-group-text text-muted"><i class="ri-calendar-line"></i></div>
                                                <input type="text" class="form-control" name="tgl_selesai"
                                                    value="{{ $task->tgl_selesai != null ? $task->tgl_selesai : '' }}"
                                                    id="tgl_selesai" placeholder="Tanggal Selesai"
                                                    {{ $isDisabled ? 'disabled' : '' }}>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="deadline">Deadline</label>
                                            <div class="input-group">
                                                <div class="input-group-text text-muted"><i class="ri-calendar-line"></i></div>
                                                <input type="text" class="form-control" name="deadline"
                                                    value="{{ $task->deadline != null ? $task->deadline : '' }}"
                                                    id="deadline" placeholder="Deadline"
                                                    {{ $isDisabled ? 'disabled' : '' }}>
                                            </div>
                                        </div>
                                        <input type="hidden" name="user_id" id="user_id" value="{{ auth()->user()->id }}">
                                        @if ($showButton)
                                            @if ($task != null)
                                                <button type="button" class="btn btn-danger btn-batal-edit" hidden>Batal</button>
                                                <button type="button" class="btn btn-warning btn-edit-task">Edit</button>
                                            @endif
                                            <button type="submit" class="btn btn-primary btn-submit-task"
                                                {{ $task != null ? 'hidden' : '' }}>
                                                {{ $task != null ? 'Update' : 'Simpan' }}
                                            </button>
                                        @endif                       
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-8 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="tabs-menu ">
                            <ul class="nav nav-tabs profile navtab-custom panel-tabs">
                                <li class="">
                                    <a href="#home" data-bs-toggle="tab" class="active" aria-expanded="true">
                                        <i class="bi bi-calendar-check"></i>
                                        <span class="hidden-xs">SUB TASK</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a href="#lampiran" data-bs-toggle="tab" aria-expanded="false">
                                        <i class="bi bi-paperclip"></i>
                                        <span class="hidden-xs">LAMPIRAN</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a href="#anggota" data-bs-toggle="tab" aria-expanded="false">
                                        <span class="visible-xs"><i class="las la-user-circle fs-16 me-1"></i></span>
                                        <span class="visible-xs"></span>
                                        <span class="hidden-xs">ANGGOTA</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="tab-content border border-top-0 p-4 br-dark">
                            <div class="tab-pane border-0 p-0 active" id="home">
                                <div class="card-body p-0">
                                    <h4>Progres {{ $task->nama_task }}</h4>
                                    <div class="progress progress-xl mb-3 progress-animate custom-progress-4" role="progressbar" 
                                        aria-valuenow="{{ $progressPercentage }}" 
                                        aria-valuemin="0" 
                                        aria-valuemax="100">
                                        
                                        <div class="progress-bar bg-primary-gradient" 
                                            style="width: {{ $progressPercentage }}%"></div>
                                        
                                        <div class="progress-bar-label">
                                            {{ $progressPercentage }}%
                                        </div>
                                    </div>
                                </div>
                                @if (Auth::check() && Auth::user()->role->slug == 'manager')
                                    <div class="table-responsive">
                                        <table id="datatable-basic" class="table table-bordered w-100">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Sub Task</th>
                                                    <th>Deadline</th>
                                                    <th>Status</th>
                                                    <th>e</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($subTask as $item)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $item->nama_subtask ?? '-' }}</td>
                                                        <td>{{ $item->deadline ? \Carbon\Carbon::parse($item->deadline)->translatedFormat('l, d F Y') : '-' }}</td>
                                                        <td class="text-center">
                                                            @if($item->detail_sub_task->isEmpty())
                                                                <span class="badge bg-info">Belum ada laporan kinerja</span>
                                                            @elseif($item->status === 'revise')
                                                                <span class="badge bg-warning"
                                                                    data-bs-toggle="tooltip" 
                                                                    data-bs-custom-class="tooltip-secondary"
                                                                    data-bs-placement="top" 
                                                                    title="Pesan Revisi: {{ $item->revisi->pesan ?? '-' }}">
                                                                    Revisi
                                                                    <i class="fas fa-info-circle ms-1"></i>
                                                                </span>
                                                            @elseif($item->status === 'approve')
                                                                <span class="badge bg-success">Approve</span>
                                                            @else
                                                                <span class="badge bg-secondary">Belum Dicek</span>
                                                            @endif
                                                        </td>
                                                        <td class="text-center">
                                                            <a href="{{ route('manajer.subtask.detail', $item->id) }}"
                                                                class="btn btn-secondary btn-sm" data-bs-toggle="tooltip"
                                                                data-bs-custom-class="tooltip-secondary"
                                                                data-bs-placement="top" title="Detail Task!"><i
                                                                    class='bx bx-detail'></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                                @if (Auth::check() && Auth::user()->role->slug == 'karyawan' || Auth::user()->role->slug == 'admin-sdm')
                                    <button type="button" class="btn btn-outline-primary tambahSubTask mb-3" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                        Tambah Sub Task
                                    </button>
                                    <div class="table-responsive">
                                        <table id="datatable-basic" class="table table-bordered w-100">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Sub Task</th>
                                                    <th>Deadline</th>
                                                    <th>Status</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($subTaskUser as $item)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $item->nama_subtask ?? '-' }}</td>
                                                        <td>{{ $item->deadline ? \Carbon\Carbon::parse($item->deadline)->translatedFormat('l, d F Y') : '-' }}</td>
                                                        <td class="text-center">
                                                            @if($item->detail_sub_task->isEmpty())
                                                                <span class="badge bg-info">Belum ada laporan kinerja</span>
                                                            @elseif($item->status === 'revise')
                                                                <span class="badge bg-warning"
                                                                    data-bs-toggle="tooltip" 
                                                                    data-bs-custom-class="tooltip-secondary"
                                                                    data-bs-placement="top" 
                                                                    title="Pesan Revisi: {{ $item->revisi->pesan ?? '-' }}">
                                                                    Revisi
                                                                    <i class="fas fa-info-circle ms-1"></i>
                                                                </span>
                                                            @elseif($item->status === 'approve')
                                                                <span class="badge bg-success">Approve</span>
                                                            @else
                                                                <span class="badge bg-secondary">Belum Dicek</span>
                                                            @endif
                                                        </td>
                                                        <td class="text-center">
                                                             <a href="javascript:void(0);"
                                                                class="btn btn-warning btn-sm updateSubTask"
                                                                data-id="{{ $item->id }}"
                                                                data-task_id="{{ $item->task_id }}"
                                                                data-user_id="{{ $item->user_id }}"
                                                                data-nama_subtask="{{ $item->nama_subtask }}"  
                                                                data-tgl_sub_task="{{ $item->tgl_sub_task }}"
                                                                data-tgl_selesai="{{ $item->tgl_selesai }}"
                                                                data-deadline="{{ $item->deadline }}"
                                                                data-lampiran='@JSON($item->lampiran)'
                                                                data-bs-target="#staticBackdrop">
                                                                <i data-bs-toggle="tooltip"
                                                                    data-bs-custom-class="tooltip-secondary"
                                                                    data-bs-placement="top" title="Update Sub Task!"
                                                                    class="bi bi-pencil-square"></i>
                                                            </a>
                                                            <a 
                                                            @if (Auth::check() && Auth::user()->role->slug == 'karyawan')
                                                                href="{{ route('karyawan.subtask.detail', $item->id) }}"
                                                                @elseif (Auth::check() && Auth::user()->role->slug == 'admin-sdm')
                                                                    href="{{ route('admin_sdm.subtask.detail', $item->id) }}"                                                            
                                                            @endif
                                                                class="btn btn-secondary btn-sm" data-bs-toggle="tooltip"
                                                                data-bs-custom-class="tooltip-secondary"
                                                                data-bs-placement="top" title="Detail Task!"><i
                                                                    class='bx bx-detail'></i>
                                                            </a>
                                                            <form 
                                                            @if (Auth::check() && Auth::user()->role->slug == 'karyawan')
                                                                action="{{ route('karyawan.subtask.delete', $item->id) }}" 
                                                                @elseif (Auth::check() && Auth::user()->role->slug == 'admin-sdm')
                                                                    action="{{ route('admin_sdm.subtask.delete', $item->id) }}"
                                                            @endif
                                                                method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="btn btn-danger btn-sm delete-sub-task"
                                                                    data-id="{{ $item->id }}"
                                                                    data-nama_subtask="{{ $item->nama_subtask }}"
                                                                    data-bs-toggle="tooltip"
                                                                    data-bs-custom-class="tooltip-danger"
                                                                    data-bs-placement="top" title="Hapus Task!">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>                                    
                                @endif
                            </div>
                            <div class="tab-pane border-0 p-0" id="lampiran" role="tabpanel">
                                <form action="{{ $lampiranActionUrl }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('put')
                                    <div class="form-group">
                                        @if (!$isDisabled)
                                            <label for="upload" class="form-label">Upload Lampiran Baru</label>
                                            <input type="file" class="form-control" name="upload" id="upload">
                                        @endif
                                        @php
                                            $file = $task->upload ?? null;
                                            $extension = $file ? strtolower(pathinfo($file, PATHINFO_EXTENSION)) : null;
                                            $isImage = $extension && in_array($extension, ['jpg', 'jpeg', 'png']);
                                            $isPDF = $extension === 'pdf';
                                        @endphp
                                        @if ($file)
                                            @if ($isImage)
                                                <img src="{{ asset('uploads/' . $file) }}" alt="Preview Gambar"
                                                    class="img-fluid mt-2 d-block mx-auto" style="max-width: 300px;">
                                                <p class="text-center mt-2" id="detail_upload">
                                                    <strong>Preview Gambar:</strong> 
                                                    <a href="{{ asset('uploads/' . $file) }}" target="_blank">Lihat Gambar</a>
                                                </p>
                                            @elseif ($isPDF)
                                                <iframe src="{{ asset('uploads/' . $file) }}" width="100%" height="400px"></iframe>
                                                <p class="text-center mt-2" id="detail_upload">
                                                    <strong>Preview PDF:</strong> 
                                                    <a href="{{ asset('uploads/' . $file) }}" target="_blank">Lihat PDF</a>
                                                </p>
                                            @else
                                                <p class="text-center mt-2 text-muted" id="detail_upload">
                                                    <strong>File Terpilih:</strong> .{{ $extension }}
                                                </p>
                                                <p class="text-center mt-2 text-muted" id="detail_upload">
                                                    <a href="{{ asset('uploads/' . $file) }}" download="{{ $file }}" class="btn btn-primary btn-sm">
                                                        <i class="fas fa-download me-1"></i>Download File
                                                    </a>
                                                </p>
                                            @endif
                                        @else
                                            <p class="text-center text-muted mt-2" id="detail_upload">
                                                Tidak ada file lampiran yang tersedia
                                            </p>
                                        @endif
                                    </div> 
                                    <div class="form-group">
                                        <label for="keterangan">Keterangan</label>
                                        <textarea class="form-control" name="keterangan" id="keterangan" cols="10" rows="5" 
                                            {{ $isDisabled ? 'disabled' : '' }}>{{ old('keterangan', $task->keterangan) }}</textarea>
                                        
                                        @if (!$isDisabled)
                                            <span class="text-xs text-danger">Jika tidak ada keterangan, maka harap isi dengan tanda (-)</span>
                                        @endif
                                    </div>
                                    @if (!$isDisabled)
                                        <button type="submit" class="btn btn-primary">
                                            Update
                                        </button>
                                    @endif
                                </form>
                            </div>
                            <div class="tab-pane border-0 p-0" id="anggota" role="tabpanel">
                                <div class="row">
                                    @foreach ($user as $item)
											<div class="col-sm-12 col-md-6 col-lg-6 col-xl-6 col-xxl-4">
												<div class="card custom-card border shadow-none">
													<div class="card-body  user-lock text-center">
                                                        <div class="d-flex justify-content-end">
                                                            @if ($canManageMember)
                                                                <form action="{{ route($deleteMemberPrefix . '.delete.anggota.task', $item->id) }}"
                                                                    method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-sm btn-icon btn-outline-danger rounded-circle border-0"
                                                                        data-id="{{ $item->id }}"
                                                                        data-nama="{{ $item->user->name }}"
                                                                        data-bs-toggle="tooltip"
                                                                        data-bs-custom-class="tooltip-danger"
                                                                        data-bs-placement="top" title="Hapus Anggota Task!">
                                                                        <i class="bi bi-x-circle"></i>                                                                
                                                                    </button>
                                                                </form>
                                                            @endif
														</div>
                                                        <div class="">
                                                            <div class="d-flex justify-content-center">
                                                                <img alt="avatar" class="rounded-circle"
                                                                    src="{{ $item->user->dataDiri ? asset('uploads/' . $item->user->dataDiri->foto_user) : asset('/images/default-images.svg') }}">
                                                            </div>                                                                                                                        
                                                            <h5 class="fs-16 mb-0 mt-3 text-dark fw-semibold">{{ $item->user->name }}</h5>
															<span class="text-muted">{{ $item->user->dataDiri->kepegawaian->subJabatan->nama_sub_jabatan ?? '' }}</span>
															<div class="d-flex justify-content-center">
                                                                @foreach ($item->user->socialMedias as $item)
                                                                    <a href="{{ $item->link }}" target="_blank" class="btn btn-icon btn-outline-primary rounded-circle border mt-3"
                                                                        data-bs-toggle="tooltip"
                                                                        data-bs-custom-class="tooltip-primary"
                                                                        data-bs-placement="bottom" title="{{ $item->nama_social_media }}">
                                                                        <i class="bx bxl-{{ $item->nama_social_media }} fs-16 align-middle"></i>
                                                                    </a>
                                                                @endforeach
															</div>
                                                        </div>
													</div>
												</div>
											</div>
                                    @endforeach
                                    @if ($canManageMember)
                                        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6 col-xxl-4">
                                            <a href="javascript:void(0);" data-bs-toggle="modal"
                                                data-bs-target="#staticBackdropAnggota">
                                                <div class="card custom-card border shadow-none bg-info-gradient btn btn-info">
                                                    <div class="card-body user-lock text-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="bi bi-plus-circle-fill mt-3" width="80" height="80" fill="white" viewBox="0 0 80 80">
                                                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z"
                                                            transform="scale(5) translate(0,0)"/>
                                                        </svg>                                                                 
                                                        <h5 class="fs-16 mb-0 mt-3 text-white fw-semibold">Tambah</h5>
                                                        <p class="text-white">Anggota</p>
                                                    </div>
                                                </div>
                                            </a>                                     
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const choiceElements = ['#user', '#user2', '#nama_project', '#tipe_task'];

            choiceElements.forEach(function(selector){
                const element = document.querySelector(selector);
                if (element) {
                    new Choices(element, {
                        removeItemButton: true,
                        searchEnabled: true,
                        noResultsText: "Tidak ada hasil yang cocok",
                        noChoicesText: "Tidak ada pilihan tersedia",
                        shouldSort: false,
                        itemSelectText: 'Tekan untuk memilih'
                    });
                }
            });
        });
        $(document).ready(function () {
            const userRole = "{{ Auth::check() ? Auth::user()->role->slug : '' }}";
            
            const dateConfig = {
                dateFormat: "Y-m-d",
                altInput: true,
                altFormat: "d F Y",
                locale: "id"
            };

            flatpickr("#tgl_task", dateConfig);
            flatpickr("#tgl_selesai", dateConfig);
            flatpickr("#deadline", dateConfig);

            let flatpickrSubtaskStart = flatpickr("#format-tanggal", {
                ...dateConfig,
                onChange: function(selectedDates, dateStr, instance) {
                    document.getElementById("tanggal").value = dateStr;
                },
                appendTo: document.getElementById("staticBackdrop")
            });

            let flatpickrSubtaskDeadline = flatpickr("#format-deadline", {
                ...dateConfig,
                onChange: function(selectedDates, dateStr, instance) {
                    document.getElementById("deadline").value = dateStr;
                },
                appendTo: document.getElementById("staticBackdrop")
            });

            $('.btn-edit-task').click(function() {
                $(this).hide();
                $('.btn-batal-edit').attr('hidden', false);
                $(".btn-submit-task").attr('hidden', false);
            });

            $('.btn-batal-edit').click(function() {
                $('.btn-edit-task').fadeIn(200);
                $(this).attr('hidden', true);
                $(".btn-submit-task").attr('hidden', true);
            });

            $('#staticBackdrop').on('hidden.bs.modal', function () {
                $('#formSubTask')[0].reset();
                $('#preview-area').empty();
                $('#detail_upload').html('Tidak ada file lampiran yang tersedia');

                flatpickrSubtaskStart.clear();
                flatpickrSubtaskDeadline.clear();

                $('#formSubTask input[name="_method"]').remove();
            });

            $(document).on('click', '.tambahSubTask', function(e) {
                e.preventDefault();

                $(".modal-title").text('Tambah Sub Task - {{ $task->nama_task }}');
                $("#tanggal").val('');
                $("#deadline").val('');
                $("#btnSubmit").text("Simpan").show();
                $("#upload").prop("disabled", false);

                let actionUrl = "";
                if (userRole == 'karyawan') {
                    actionUrl = "{{ route('karyawan.subtask.store') }}";
                } else if (userRole == 'admin-sdm') {
                    actionUrl = "{{ route('admin_sdm.subtask.store') }}";     
                }
                $("#formSubTask").attr("action", actionUrl);
                $("#formSubTask input[name='_method']").remove();

                $('#projectWrapper').addClass('d-none');
                $('#project_perusahaan_id').val('').prop('required', false);
                $('#tipeTaskWrapper').removeClass('col-md-6').addClass('col-md-12');
            });
            
            $(document).on("click", ".updateSubTask", function(e) {
                e.preventDefault();

                let id = $(this).data("id");
                let task_id = $(this).data("task_id");
                let user_id = $(this).data("user_id");
                let nama_subtask = $(this).data("nama_subtask");
                let tgl_sub_task = $(this).data("tgl_sub_task");
                let tgl_selesai = $(this).data("tgl_selesai");
                let deadline = $(this).data("deadline");
                let lampiran = $(this).data('lampiran');
                
                $(".modal-title").text("Update Sub Task");

                let actionUrl = "";
                if (userRole == 'karyawan') {
                    actionUrl = "/karyawan/subtask/update/" + id;
                } else if (userRole == 'admin-sdm') {
                    actionUrl = "/admin_sdm/subtask/update/" + id;
                }
                $("#formSubTask").attr("action", actionUrl);
                $("#formSubTask input[name='_method']").remove();
                $("#formSubTask").append('<input type="hidden" name="_method" value="PUT">');

                $("#nama_subtask").val(nama_subtask);
                $("#tanggal").val(tgl_sub_task);
                $("#deadline").val(deadline);

                flatpickrSubtaskStart.setDate(tgl_sub_task, true);
                flatpickrSubtaskDeadline.setDate(deadline, true);
                
                $("#task_id").val(task_id);
                $("#user_id").val(user_id);
                $('#upload').prop("disabled", false);
                
                $('#staticBackdrop').modal('show');
                
                $("#preview-area").html("");
                if (lampiran && lampiran.length > 0) {
                    $("#detail_upload").html("<strong>Lampiran Saat Ini:</strong><br>");
                    lampiran.forEach(item => {
                        const file = item.lampiran;
                        const extension = file.split('.').pop().toLowerCase();
                        let previewHTML = '';

                        if (['jpg', 'jpeg', 'png'].includes(extension)) {
                            previewHTML = `
                                <div class="col-md-4 mb-3 text-center">
                                    <img src="/uploads/${file}" class="img-fluid rounded border shadow-sm" style="max-height: 150px;">
                                    <p class="small mt-2 text-truncate">${file}</p>
                                </div>
                            `;
                        } else if (extension === 'pdf') {
                            previewHTML = `
                                <div class="col-md-6 mb-3 text-center">
                                    <iframe src="/uploads/${file}" class="rounded border" width="100%" height="150px"></iframe>
                                    <p class="small mt-2 text-truncate">${file}</p>
                                </div>
                            `;
                        } else {
                            previewHTML = `
                                <div class="col-md-4 mb-3 text-center">
                                    <div class="alert alert-secondary p-2 mb-1 text-truncate" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        <i class="fa fa-file me-2"></i>${file}
                                    </div>
                                </div>
                            `;
                        }
                        $("#preview-area").append(previewHTML);
                    });
                } else {
                    $("detail_upload").html("Tidak ada file lampiran sebelumnya.");
                }
            });

            $("#upload").change(function() {
                const files = this.files;
                const previewArea = $("#preview-area");
                const detailUpload = $("#detail_upload");

                previewArea.html('');
                detailUpload.html('');

                if (files.length > 0) {
                    detailUpload.html(`<strong>${files.length} file baru dipilih:</strong><br>`);

                    Array.from(files).forEach(file => {
                        let fileUrl = URL.createObjectURL(file);
                        let fileName = file.name;
                        let ext = fileName.split('.').pop().toLowerCase();
                        let previewItem = '';

                        if (['jpg', 'jpeg', 'png', 'gif'].includes(ext)) {
                            previewItem = `
                                <div class="col-md-4 mb-3 text-center">
                                    <img src="${fileUrl}" class="img-fluid rounded border shadow-sm" style="max-height: 150px;">
                                    <p class="small mt-2 text-truncate">${fileName}</p>
                                </div>
                            `;
                        } else if (ext == 'pdf') {
                            previewItem = `
                                <div>
                                    <img src="${fileUrl}" class="img-fluid rounded border shadow-sm" style="max-height: 150px;">
                                    <p class="small mt-2 text-truncate">${fileName}</p>
                                </div>
                            `;
                        } else {
                            previewItem = `
                                <div class="col-md-4 mb-3 text-center">
                                    <div class="alert alert-secondary p-2 mb-1 text-truncate">
                                        <i class="fa fa-file me-2"></i>${fileName}
                                    </div>
                                </div>
                            `;
                        }
                        previewArea.append(previewItem);
                    });
                }
            });

            $(document).on("click", ".delete-sub-task", function(e){
                e.preventDefault();
                let id = $(this).data("id");
                let nama = $(this).data("nama_subtask");
                let form = $(this).closest('form');

                Swal.fire({
                    title: "Konfirmasi Hapus!",
                    text: "Apakah Kamu yakin ingin menghapus sub task '" + nama + "' ?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#cf0202",
                    cancelButtonColor: "3085d6",
                    confirmButtonText: "Ya, Hapus!",
                    cancelButtonText: "Batal"
                }).then((result) => {
                    if (result.isConfirmed){
                        form.submit();
                    }
                });
            });

            document.querySelectorAll('input[type="file"]').forEach(input => {
                input.addEventListener('change', function(e){

                });
            });
        });
    </script>
@endsection
