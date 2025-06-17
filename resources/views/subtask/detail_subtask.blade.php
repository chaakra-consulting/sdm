@extends('layouts.main')
@section('content')
    <div class="modal fade" id="revisiModal" tabindex="-1" aria-labelledby="revisiModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="revisiModalLabel">Revisi Subtask</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('manajer.revise.subtask', $subtaskManager->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="pesan_revisi">Pesan Revisi</label>
                            <textarea class="form-control" name="pesan_revisi" id="pesan_revisi" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning">Kirim Revisi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="staticBackdropLabel"></h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="POST" id="formLaporanKinerja" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="format-tanggal">Tanggal</label>
                            <div class="input-group">
                                <div class="input-group-text text-muted"> <i class="ri-calendar-line"></i> </div>
                                <input type="text" class="form-control" name="format-tanggal" id="format-tanggal" placeholder="Tanggal Mulai" required>
                                <input type="hidden" name="tanggal" id="tanggal">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="durasi_jam" class="form-label">Durasi</label>
                            <div class="row">
                                <div class="col-6">
                                    <div class="input-group">
                                        <input type="number" min="0" name="durasi_jam" class="form-control"
                                            placeholder="Jam" value="" required>
                                        <span class="input-group-text">Jam</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="input-group">
                                        <input type="number" min="0" max="59" name="durasi_menit"
                                            class="form-control" placeholder="Menit" value="" required>
                                        <span class="input-group-text">Menit</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <textarea class="form-control" name="keterangan" id="keterangan" cols="10" rows="5"></textarea>
                        </div>
                        <input type="hidden" name="sub_task_id" id="sub_task_id" value="{{ $subtask->id }}">
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
    <div class="container-fluid">
        <div class="row row-sm">
            <div class="col-xl-3 col-lg-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="ps-0">
                            <div class="main-profile-overview">
                                <div class="d-flex justify-content-between mb-4">
                                    <div>
                                        <h5 class="main-profile-name" style="text-transform: capitalize;">
                                            {{ $subtask->nama_subtask ?? '-' }}
                                        </h5>
                                        @if($subtask->detail_sub_task->isEmpty())
                                            <span class="badge bg-info">Belum ada laporan kinerja</span>
                                        @elseif($subtask->status === 'revise')
                                            <span class="badge bg-warning"
                                                data-bs-toggle="tooltip" 
                                                data-bs-custom-class="tooltip-secondary"
                                                data-bs-placement="top" 
                                                title="Pesan Revisi: {{ $subtask->revisi->pesan ?? '-' }}">
                                                Revisi
                                                <i class="fas fa-info-circle ms-1"></i>
                                            </span>
                                        @elseif($subtask->status === 'approve')
                                            <span class="badge bg-success">Approve</span>
                                        @else
                                            <span class="badge bg-secondary">Belum Dicek</span>
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
                                <div class="container-project">
                                    <form action="{{ route('karyawan.subtask.update.detail', $subtask->id) }}"
                                        method="post" enctype="multipart/form-data">
                                        @csrf
                                        @method('put')
                                        <div class="form-group">
                                            <label for="nama_subtask" class="form-label">Sub Task</label>
                                            <input type="text" class="form-control" id="nama_subtask" name="nama_subtask"
                                                placeholder="Nama Sub Task"
                                                value="{{ $subtask->nama_subtask ?? old('nama_subtask') }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="tgl_sub_task" class="form-label">Tanggal Mulai</label>
                                            <div class="input-group">
                                                <div class="input-group-text text-muted" name="tgl_sub_task"><i
                                                        class="ri-calendar-line"></i>
                                                </div>
                                                <input type="text" class="form-control" name="tgl_sub_task"
                                                    value="{{ $subtask->tgl_sub_task != null ? $subtask->tgl_sub_task : '' }}"
                                                    id="tgl_sub_task" placeholder="Tanggal Mulai">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="tgl_selesai" class="form-label">Tanggal Selesai</label>
                                            <div class="input-group">
                                                <div class="input-group-text text-muted" name="tgl_selesai"><i
                                                        class="ri-calendar-line"></i>
                                                </div>
                                                <input type="text" class="form-control" name="tgl_selesai"
                                                    value="{{ $subtask->tgl_selesai != null ? $subtask->tgl_selesai : '' }}"
                                                    id="tgl_sub_task" placeholder="Tanggal Selesai">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="deadline" class="form-label">Deadline</label>
                                            <div class="input-group">
                                                <div class="input-group-text text-muted" name="deadline"><i
                                                        class="ri-calendar-line"></i>
                                                </div>
                                                <input type="text" class="form-control" name="deadline"
                                                    value="{{ $subtask->deadline != null ? $subtask->deadline : '' }}"
                                                    id="deadline" placeholder="Deadline">
                                            </div>
                                        </div>
                                        <input type="hidden" name="task_id" value="{{ $subtask->task_id }}">
                                        <input type="hidden" name="user_id" value="{{ $subtask->user_id }}">
                                        @if (Auth::check() && Auth::user()->role->slug == 'karyawan' || Auth::check() && Auth::user()->role->slug == 'admin-sdm')
                                            @if ($subtask != null)
                                                <button type="button" class="btn btn-danger btn-sm btn-batal-edit"
                                                    hidden>Batal</button>
                                                <button type="button" class="btn btn-warning btn-sm btn-edit-task">Edit</button>
                                            @endif
                                            <button type="submit" class="btn btn-primary btn-sm btn-submit-task"
                                                {{ $subtask != null ? 'hidden' : '' }}>{{ $subtask != null ? 'Update' : 'Simpan' }}
                                            </button>
                                        @endif
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    @php
                        $userRole = auth()->user()->role->slug;
                    @endphp
                    @if ($userRole == 'manager')
                        <a href="/manajer/subtask" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </a>
                        @if ($subtask->task != null)
                            <a href="/manajer/task/{{ $subtask->task->id }}" class="btn btn-secondary">Kembali Ke
                                Task</a>
                        @endif
                    @elseif ($userRole == 'karyawan')
                        <a href="/karyawan/subtask" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </a>
                        @if ($subtask->task != null)
                            <a href="/karyawan/task/detail/{{ $subtask->task->id }}" class="btn btn-secondary">Kembali Ke
                                Task</a>
                        @endif
                    @elseif ($userRole == 'admin-sdm')
                        <a href="/admin_sdm/subtask" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </a>
                        @if ($subtask->task != null)
                            <a href="/admin_sdm/task/detail/{{ $subtask->task->id }}" class="btn btn-secondary">Kembali Ke
                                Task</a>
                        @endif
                    @endif
                </div>
            </div>
            <div class="col-xl-9 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="tabs-menu ">
                            <ul class="nav nav-tabs profile navtab-custom panel-tabs">
                                <li class="">
                                    <a href="#home" data-bs-toggle="tab" class="active" aria-expanded="true">
                                        <i class="bi bi-calendar-check"></i>
                                        <span class="hidden-xs">DETAIL</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a href="#lampiran" data-bs-toggle="tab" aria-expanded="false">
                                        <i class="bi bi-paperclip"></i>
                                        <span class="hidden-xs">LAMPIRAN</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="tab-content border border-top-0 p-4 br-dark">
                            <div class="tab-pane border-0 p-0 active" id="home">
                                <div class="d-flex align-items-start flex-wrap gap-2">
                                    @if (Auth::check() && Auth::user()->role->slug == 'manager')
                                        <div class="d-flex gap-2">
                                            <form action="{{ route('manajer.approve.subtask', $subtaskManager->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-primary mb-1">Approve</button>
                                            </form>
                                            <button class="btn btn-outline-warning mb-1" data-bs-toggle="modal" data-bs-target="#revisiModal">Revise</button>                                        </div>
                                    @endif
                                    @if (Auth::check() && Auth::user()->role->slug == 'karyawan' || Auth::user()->role->slug == 'admin-sdm')
                                        <button type="button" class="btn btn-outline-primary btn-sm tambahLaporanKinerja mb-1" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                            <i class="bi bi-plus"></i> Update Pekerjaan
                                        </button>
                                        <form 
                                        @if (Auth::check() && Auth::user()->role->slug == 'karyawan')
                                            action="{{ route('karyawan.subtask.detail.kirim', ['id' => $subtask->id]) }}"
                                            @elseif (Auth::check() && Auth::user()->role->slug == 'admin-sdm')
                                                action="{{ route('admin_sdm.subtask.detail.kirim', ['id' => $subtask->id]) }}"                                           
                                        @endif
                                        method="POST" id="formKirim">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-outline-warning btn-sm">
                                                <i class="bi bi-send"></i> Kirim
                                            </button>
                                        </form>
                                        <form 
                                        @if (Auth::check() && Auth::user()->role->slug == 'karyawan')
                                            action="{{ route('karyawan.subtask.detail.batal', ['id' => $subtask->id]) }}" 
                                            @elseif (Auth::check() && Auth::user()->role->slug == 'admin-sdm')
                                                action="{{ route('admin_sdm.subtask.detail.batal', ['id' => $subtask->id]) }}"                                        
                                        @endif
                                        method="POST" id="formBatal">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="tanggal" id="tanggal_terpilih_batal">
                                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                                <i class="bi bi-x-circle"></i> Batal
                                            </button>
                                        </form>
                                    @endif
                                </div>
                                <div class="table-responsive">
                                    <table id="datatable-basic" class="table table-bordered w-100">
                                        @if (Auth::check() && Auth::user()->role->slug == 'karyawan' || Auth::user()->role->slug == 'admin-sdm')
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Tanggal</th>
                                                    <th>Anggota</th>
                                                    <th>Durasi</th>
                                                    <th>Keterangan</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($subtask->detail_sub_task as $item)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') ?? '-' }}</td>
                                                    <td>{{ $item->user->name }}</td>
                                                    <td>
                                                        {{ $item->durasi ? floor($item->durasi / 60) . ' Jam' : '-' }},
                                                        {{ $item->durasi ? $item->durasi % 60 . ' Menit' : '-' }}
                                                    </td>
                                                    <td>
                                                        {{ $item->keterangan ?? '-' }}
                                                    </td>
                                                    <td class="text-center">
                                                        @if ($item->is_active == 0)
                                                            <a href="javascript:void(0);" class="btn btn-warning btn-sm updateLaporanKinerja"
                                                                data-bs-toggle="modal"
                                                                data-id="{{ $item->id }}"
                                                                data-tanggal="{{ $item->tanggal }}"
                                                                data-keterangan="{{ $item->keterangan }}"
                                                                data-durasi="{{ $item->durasi }}"
                                                                data-bs-target="#staticBackdrop">
                                                                <i data-bs-toggle="tooltip"
                                                                    data-bs-custom-class="tooltip-warning"
                                                                    data-bs-placement="top" title="Edit Update Pekerjaan!"
                                                                    class="bi bi-pencil-square"></i>
                                                            </a>
                                                            <form 
                                                            @if (Auth::check() && Auth::user()->role->slug == 'karyawan')
                                                                action="{{ route('karyawan.laporan_kinerja.delete', $item->id) }}" 
                                                                @elseif (Auth::check() && Auth::user()->role->slug == 'admin-sdm')
                                                                    action="{{ route('admin_sdm.laporan_kinerja.delete', $item->id) }}"
                                                            @endif
                                                            method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger btn-sm delete"
                                                                    data-bs-toggle="tooltip" data-bs-custom-class="tooltip-danger"
                                                                    data-bs-placement="top" title="Hapus Sub Task!">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        @else
                                                            <span class="badge bg-success">Sudah Dikirim</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        @endif
                                        @if (Auth::check() && Auth::user()->role->slug == 'manager')
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Tanggal</th>
                                                    <th>Anggota</th>
                                                    <th>Durasi</th>
                                                    <th>Keterangan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($subtaskManager->detail_sub_task as $item)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') ?? '-' }}</td>
                                                    <td>{{ $item->user->name }}</td>
                                                    <td>
                                                        {{ $item->durasi ? floor($item->durasi / 60) . ' Jam' : '-' }},
                                                        {{ $item->durasi ? $item->durasi % 60 . ' Menit' : '-' }}
                                                    </td>
                                                    <td>
                                                        {{ $item->keterangan ?? '-' }}
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        @endif
                                    </table>
                                </div>      
                            </div>
                            <div class="tab-pane border-0 p-0" id="lampiran" role="tabpanel">
                                <form 
                                @if (Auth::check() && Auth::user()->role->slug == 'karyawan')
                                    action="{{ route('karyawan.subtask.update.detail.lampiran', $subtask->id) }}" 
                                    @elseif (Auth::check() && Auth::user()->role->slug == 'admin-sdm')
                                        action="{{ route('admin_sdm.subtask.update.detail.lampiran', $subtask->id) }}"                                
                                @endif
                                method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('put')
                                    <div class="form-group">
                                        <input type="file" class="form-control" name="upload[]" id="upload" multiple>
                                        <div id="preview-area" class="row mt-3"></div>
                                        <p class="text-center" id="detail_upload"></p>
                                    </div>
                                    <div class="row mt-4">
                                        @foreach($subtask->lampiran as $lampiran)
                                        <div class="col-md-3 mb-3 lampiran-item">
                                            <div class="card shadow-sm position-relative">
                                                @if (Auth::check() && Auth::user()->role->slug == 'karyawan')
                                                    <button class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 delete-lampiran" 
                                                            data-id="{{ $lampiran->id }}" 
                                                            style="z-index: 2">
                                                        <i class="bi bi-x"></i>
                                                    </button>
                                                @endif
                                                <div class="card-body text-center" data-bs-toggle="modal" data-bs-target="#lampiranModal" data-index="{{ $loop->index }}">
                                                    @if(in_array(pathinfo($lampiran->lampiran, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif']))
                                                        <img src="{{ asset('uploads/' . $lampiran->lampiran) }}" class="img-fluid rounded" style="height: 100px; object-fit: cover">
                                                    @else
                                                        <div class="file-icon">
                                                            <i class="bi bi-file-earmark-richtext display-4 text-muted"></i>
                                                        </div>
                                                    @endif
                                                    <p class="small text-muted mt-2 mb-0 text-truncate">{{ $lampiran->lampiran }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    @if (Auth::check() && Auth::user()->role->slug == 'karyawan' || Auth::user()->role->slug == 'admin-sdm')
                                        <button type="submit" class="btn btn-primary btn-sm btn-submit-task">
                                            Update
                                        </button>
                                    @endif
                                </form>
                            </div>
                        </div>  
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="lampiranModal" tabindex="-1" aria-labelledby="lampiranModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="lampiranModalLabel">Lampiran {{ ucwords($subtask->nama_subtask) }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="carouselLampiran" class="carousel slide">
                        <div class="carousel-inner">
                            @foreach($subtask->lampiran as $index => $lampiran)
                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                @if(in_array(pathinfo($lampiran->lampiran, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif']))
                                    <img src="{{ asset('uploads/' . $lampiran->lampiran) }}" class="d-block w-100" alt="Lampiran {{ $index + 1 }}">
                                @elseif(pathinfo($lampiran->lampiran, PATHINFO_EXTENSION) === 'pdf')
                                    <div class="ratio ratio-16x9">
                                        <iframe src="{{ asset('uploads/' . $lampiran->lampiran) }}" class="w-100"></iframe>
                                    </div>
                                @else
                                    <div class="text-center py-5">
                                        <i class="bi bi-file-earmark-richtext display-1 text-muted"></i>
                                        <p class="mt-3">File tidak dapat ditampilkan</p>
                                        <a href="{{ asset('uploads/' . $lampiran->lampiran) }}" class="btn btn-primary" download>
                                            <i class="bi bi-download me-2"></i>Unduh File
                                        </a>
                                    </div>
                                @endif
                            </div>
                            @endforeach
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselLampiran" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselLampiran" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                    <div class="carousel-indicators position-static mt-3">
                        @foreach($subtask->lampiran as $index => $lampiran)
                        <button type="button" data-bs-target="#carouselLampiran" data-bs-slide-to="{{ $index }}" 
                                class="{{ $index === 0 ? 'active' : '' }}" aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                                aria-label="Slide {{ $index + 1 }}"></button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <style>
        .lampiran-item {
            cursor: pointer;
            transition: transform 0.2s;
        }
        .lampiran-item:hover {
            transform: translateY(-5px);
        }
        .carousel-indicators [data-bs-target] {
            background-color: #666;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin: 0 5px;
        }
        .carousel-indicators .active {
            background-color: #0d6efd;
        }
        .file-icon {
            height: 100px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
    <script>
        $(document).ready(function() {
            let flatpickrInstance = flatpickr("#format-tanggal", {
                dateFormat: "Y-m-d",
                altInput: true,
                altFormat: "d F Y",
                locale: 'id',
                onChange: function(selectedDates, dateStr, instance) {
                    document.getElementById("tanggal").value = dateStr;
                },
                appendTo: document.getElementById("staticBackdrop")
            });
            $('.btn-edit-task').click(function() {
                $('.btn-edit-task').hide();
                $('.btn-batal-edit').prop('hidden', false);
                $(".btn-submit-task").prop('hidden', false);

                $('.btn-batal-edit').click(function() {
                    $('.btn-edit-task').fadeIn(200);
                    $('.btn-batal-edit').prop('hidden', true);
                    $(".btn-submit-task").prop('hidden', true);
                })
            });
            $('#staticBackdrop').on('hidden.bs.modal', function () {
                $('#formLaporanKinerja')[0].reset();
                flatpickrInstance.clear();
                flatpickrInstance1.clear();
                $('#formSubTask input[name="_method"]').remove();
            });
            $(document).on('click', '.tambahLaporanKinerja', function() {
                $(".modal-title").text('Update Pekerjaan');
                $("#nama_subtask").val('');
                $("#durasi_jam").val('');
                $("#durasi_menit").val('');
                $("#tanggal").val('');
                $("#keterangan").val('');

                $("#btnSubmit").text("Simpan").show();
                $("#formLaporanKinerja").attr("action", "/karyawan/laporan_kinerja/store");
                $("#formLaporanKinerja input[name='_method']").remove();

                $("input[name='durasi_jam']").val("");
                $("input[name='durasi_menit']").val("");
            });
            
            $(document).on('click', '.updateLaporanKinerja', function() {
                let id = $(this).data('id');
                let tanggal = $(this).data('tanggal');
                let durasi = $(this).data('durasi');
                let keterangan = $(this).data('keterangan');

                let jam = Math.floor(durasi / 60);
                let menit = durasi % 60;

                $(".modal-title").text('Edit Update Pekerjaan');
                
                $("#durasi_jam").val(jam);
                $("#durasi_menit").val(menit);
                flatpickrInstance.setDate(tanggal, true, "Y-m-d");
                $("#keterangan").val(keterangan);

                $("input[name='durasi_jam']").val(jam);
                $("input[name='durasi_menit']").val(menit);
                $("#keterangan").val(keterangan);

                $("#formLaporanKinerja").attr("action", `/karyawan/laporan_kinerja/update/${id}`);

                if ($("#formLaporanKinerja input[name='_method']").length === 0) {
                    $("#formLaporanKinerja").append(`<input type="hidden" name="_method" value="PUT">`);
                } else {
                    $("#formLaporanKinerja input[name='_method']").val('PUT');
                }

                $("#btnSubmit").text("Update");
            });
        });
    </script>
    <script>
        $("#upload").change(function() {
            const files = this.files;
            const previewArea = $("#preview-area");
            const detailUpload = $("#detail_upload");

            previewArea.html('');
            detailUpload.html('');

            if (files.length > 0) {
                detailUpload.html(`<strong>${files.length} file dipilih:</strong><br>`);

                Array.from(files).forEach(file => {
                    let fileUrl = URL.createObjectURL(file);
                    let fileName = file.name;
                    let ext = fileName.split('.').pop().toLowerCase();

                    detailUpload.append(`${fileName}<br>`);

                    let previewItem = '';

                    if (['jpg', 'jpeg', 'png', 'gif'].includes(ext)) {
                        previewItem = `
                            <div class="col-md-4 mb-3 text-center">
                                <img src="${fileUrl}" class="img-fluid rounded border shadow-sm" style="max-height: 150px;">
                                <p class="small mt-2">${fileName}</p>
                            </div>
                        `;
                    } else if (ext === 'pdf') {
                        previewItem = `
                            <div class="col-md-6 mb-3 text-center">
                                <iframe src="${fileUrl}" class="rounded border" width="100%" height="150px"></iframe>
                                <p class="small mt-2">${fileName}</p>
                            </div>
                        `;
                    } else {
                        previewItem = `
                            <div class="col-md-4 mb-3 text-center">
                                <div class="alert alert-secondary p-2 mb-1" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    <i class="fa fa-file me-2"></i>${fileName}
                                </div>
                            </div>
                        `;
                    }
                    previewArea.append(previewItem);
                });
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dateConfig = {
                dateFormat: 'Y-m-d',
                altInput: true,
                altFormat: "d M Y",
                locale: 'id',
            };
            flatpickr("#tgl_sub_task", dateConfig);
            flatpickr("#tgl_selesai", dateConfig);
            flatpickr("#deadline", dateConfig);

            const lampiranItems = document.querySelectorAll('.lampiran-item');
            
            lampiranItems.forEach(item => {
                item.addEventListener('click', function() {
                    const index = parseInt(this.dataset.index);
                    const carousel = new bootstrap.Carousel(document.getElementById('carouselLampiran'));
                    carousel.to(index);
                });
            });

            const carouselLampiran = document.getElementById('carouselLampiran');
            carouselLampiran.addEventListener('slid.bs.carousel', function(event) {
                const indicators = document.querySelectorAll('.carousel-indicators button');
                indicators.forEach((indicator, i) => {
                    if (i === event.to) {
                        indicator.classList.add('active');
                    } else {
                        indicator.classList.remove('active');
                    }
                });
            });

            const lampiranModal = document.getElementById('lampiranModal');
            lampiranModal.addEventListener('hidden.bs.modal', function() {
                const carousel = bootstrap.Carousel.getInstance(carouselLampiran);
                carousel.to(0);
            });
        })
    </script>
    <script>
        document.querySelectorAll('.delete-lampiran').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault()
                e.stopPropagation()
                
                const lampiranId = this.dataset.id
                const card = this.closest('.col-md-3')
                
                if(confirm('Yakin ingin menghapus lampiran ini?')) {
                    fetch(`/karyawan/subtask/detail/lampiran/${lampiranId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        }
                    })
                    .then(response => {
                        if(response.ok) {
                            card.remove()
                        } else {
                            alert('Gagal menghapus lampiran')
                        }
                    })
                }
            })
        })
    </script>
@endsection
