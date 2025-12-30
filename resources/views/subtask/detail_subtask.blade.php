@extends('layouts.main')

@section('content')
    @php
        $userRole = Auth::check() ? Auth::user()->role->slug : '';
        $uId = auth()->id();
        
        $config = [
            'can_update_pekerjaan' => in_array($userRole, ['karyawan', 'admin-sdm']),
            'back_url' => '#',
            'task_url' => '#'
        ];

        if ($userRole == 'manager') {
            $config['back_url'] = '/manajer/subtask';
            if ($subtask->task) $config['task_url'] = '/manajer/task/' . $subtask->task->id;
        } elseif ($userRole == 'karyawan') {
            $config['back_url'] = '/karyawan/subtask';
            if ($subtask->task) $config['task_url'] = '/karyawan/task/detail/' . $subtask->task->id;
        } elseif ($userRole == 'admin-sdm') {
            $config['back_url'] = '/admin_sdm/subtask';
            if ($subtask->task) $config['task_url'] = '/admin_sdm/task/detail/' . $subtask->task->id;
        }
        
        $statusBadge = '<span class="badge bg-secondary-transparent rounded-pill">Belum Dicek</span>';
        
        if($subtask->status === 'approve') {
            $statusBadge = '<span class="badge bg-success-transparent rounded-pill"><i class="ri-checkbox-circle-line me-1"></i> Approved</span>';
        } elseif($subtask->status === 'revise') {
            $statusBadge = '<span class="badge bg-warning-transparent rounded-pill" data-bs-toggle="tooltip" title="Pesan Revisi: '.($subtask->revisi->pesan ?? '-').'"><i class="ri-alert-line me-1"></i> Revisi</span>';
        } elseif($subtask->detail_sub_task->isEmpty()){
             $statusBadge = '<span class="badge bg-info-transparent rounded-pill">Belum ada laporan</span>';
        }
        
        $inputState = 'disabled';
    @endphp

    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-4 col-lg-5">
                <div class="card custom-card overflow-hidden">
                    <div class="card-header border-bottom border-block-end-dashed justify-content-between align-items-center">
                        <div class="card-title">Informasi Subtask</div>
                        <div class="d-flex gap-2 align-items-center">
                            <a href="{{ $config['back_url'] }}" class="btn btn-light btn-sm btn-wave" data-bs-toggle="tooltip" title="Kembali">
                                <i class="ri-arrow-left-line align-middle"></i>
                            </a>
                            @if ($subtask->task)
                                <a href="{{ $config['task_url'] }}" class="btn btn-primary-light btn-sm btn-wave" data-bs-toggle="tooltip" title="Lihat Task Induk">
                                    <i class="ri-file-list-line align-middle"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                    
                    <div class="card-body pt-4">
                        <div class="text-center mb-3">
                            {!! $statusBadge !!}
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-danger mb-3">
                                <ul class="mb-0 ps-3">@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
                            </div>
                        @endif

                        <form action="{{ route(($userRole == 'karyawan' ? 'karyawan' : 'admin_sdm') . '.subtask.update.detail', $subtask->id) }}" method="post" enctype="multipart/form-data">
                            @csrf @method('put')

                            <div class="mb-3">
                                <label class="form-label fs-13 text-muted">Nama Subtask</label>
                                <input type="text" name="nama_subtask" id="nama_subtask_info" class="form-control fw-bold" value="{{ $subtask->nama_subtask }}" disabled>
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label fs-13 text-muted">Mulai</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="ri-calendar-line text-muted"></i></span>
                                        <input type="text" name="tgl_sub_task" id="tgl_sub_task_info" class="form-control border-start-0 ps-0" 
                                               value="{{ \Carbon\Carbon::parse($subtask->tgl_sub_task)->translatedFormat('Y-m-d') }}" 
                                               disabled>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label class="form-label fs-13 text-muted">Deadline</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="ri-calendar-event-line text-muted"></i></span>
                                        <input type="text" name="deadline" id="deadline_sub_info" class="form-control border-start-0 ps-0" 
                                               value="{{ \Carbon\Carbon::parse($subtask->deadline)->translatedFormat('Y-m-d') }}" 
                                               disabled>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fs-13 text-muted">Tanggal Selesai (Aktual)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="ri-checkbox-circle-line text-success"></i></span>
                                    <input type="text" name="tgl_selesai" id="tgl_selesai_info" class="form-control border-start-0 ps-0" 
                                           value="{{ $subtask->tgl_selesai ? \Carbon\Carbon::parse($subtask->tgl_selesai)->translatedFormat('Y-m-d') : '' }}" 
                                           placeholder="Belum Selesai"
                                           disabled>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fs-13 text-muted">Tambah Lampiran</label>
                                <input type="file" class="form-control" name="upload[]" id="upload_info" multiple disabled>
                                <div class="form-text fs-11">Upload file bukti pengerjaan di sini.</div>
                            </div>

                            <input type="hidden" name="task_id" value="{{ $subtask->task_id }}">
                            <input type="hidden" name="user_id" value="{{ $subtask->user_id }}">

                            @if ($config['can_update_pekerjaan'])
                                <div class="d-grid gap-2 mt-4">
                                    <button type="button" class="btn btn-warning-light btn-wave btn-edit-subtask">
                                        <i class="ri-pencil-line me-1"></i> Edit Info & Upload
                                    </button>
                                    
                                    <button type="button" class="btn btn-danger-light btn-wave btn-batal-edit" hidden>
                                        <i class="ri-close-line me-1"></i> Batal Edit
                                    </button>

                                    <button type="submit" class="btn btn-primary btn-wave btn-submit-subtask" hidden>
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
                            <a class="nav-link active py-3" data-bs-toggle="tab" href="#laporan" role="tab">
                                <i class="ri-file-list-3-line me-1 align-middle fs-16"></i> Laporan Kinerja
                            </a>
                            <a class="nav-link py-3" data-bs-toggle="tab" href="#lampiran" role="tab">
                                <i class="ri-attachment-2 me-1 align-middle fs-16"></i> Lampiran ({{ $subtask->lampiran->count() }})
                            </a>
                        </nav>

                        <div class="tab-content p-4">
                            <div class="tab-pane active" id="laporan" role="tabpanel">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="fw-semibold mb-0">Riwayat Laporan</h6>
                                    <div class="btn-list">
                                        @if ($config['can_update_pekerjaan'])
                                            <form action="{{ route(($userRole == 'karyawan' ? 'karyawan' : 'admin_sdm') . '.subtask.detail.kirim', ['id' => $subtask->id]) }}" 
                                                  method="POST" class="d-inline" id="formKirim">
                                                @csrf @method('PUT')
                                                <button type="submit" class="btn btn-outline-success btn-sm btn-wave" data-bs-toggle="tooltip" title="Kirim Laporan (Selesai)">
                                                    <i class="bi bi-send me-1"></i> Kirim
                                                </button>
                                            </form>
                                            
                                            <form action="{{ route(($userRole == 'karyawan' ? 'karyawan' : 'admin_sdm') . '.subtask.detail.batal', ['id' => $subtask->id]) }}" 
                                                  method="POST" class="d-inline" id="formBatal">
                                                @csrf @method('PUT')
                                                <button type="submit" class="btn btn-outline-danger btn-sm btn-wave" data-bs-toggle="tooltip" title="Batalkan Pengiriman">
                                                    <i class="bi bi-x-circle me-1"></i> Batal
                                                </button>
                                            </form>
                                            
                                            <button class="btn btn-primary btn-sm btn-wave tambahLaporanKinerja" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                                <i class="bi bi-plus-lg me-1"></i> Update Progress
                                            </button>
                                        @endif
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table id="datatable-basic" class="table table-hover text-nowrap w-100">
                                        <thead class="bg-light">
                                            <tr>
                                                <th>No</th>
                                                <th>Tanggal</th>
                                                <th>Anggota</th>
                                                <th>Durasi</th>
                                                <th>Keterangan</th>
                                                <th class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($subtask->detail_sub_task as $item)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}</td>
                                                    <td>{{ $item->user->name }}</td>
                                                    <td>
                                                        {{ $item->durasi ? floor($item->durasi / 60) . ' Jam ' . ($item->durasi % 60) . ' Menit' : '-' }}
                                                    </td>
                                                    <td><span class="text-wrap">{{ $item->keterangan ?? '-' }}</span></td>
                                                    <td class="text-center">
                                                        @if ($item->is_active == 0 && $config['can_update_pekerjaan'])
                                                            <div class="d-flex justify-content-center gap-2">
                                                                <button class="btn btn-sm btn-icon btn-warning updateLaporanKinerja"
                                                                    data-id="{{ $item->id }}"
                                                                    data-tanggal="{{ $item->tanggal }}"
                                                                    data-keterangan="{{ $item->keterangan }}"
                                                                    data-durasi="{{ $item->durasi }}"
                                                                    data-bs-toggle="modal" data-bs-target="#staticBackdrop"
                                                                    title="Edit">
                                                                    <i class="ri-pencil-line"></i>
                                                                </button>
                                                                
                                                                <form action="{{ route(($userRole == 'karyawan' ? 'karyawan' : 'admin_sdm') . '.laporan_kinerja.delete', $item->id) }}" method="POST" class="d-inline">
                                                                    @csrf @method('DELETE')
                                                                    <button type="submit" class="btn btn-sm btn-icon btn-danger delete-laporan" title="Hapus">
                                                                        <i class="ri-delete-bin-line"></i>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        @elseif($item->is_active == 1)
                                                            <span class="badge bg-success-transparent">Terkirim</span>
                                                        @else
                                                             <span class="badge bg-light text-muted">Locked</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr><td colspan="6" class="text-center text-muted py-4">Belum ada laporan kinerja.</td></tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <div class="tab-pane" id="lampiran" role="tabpanel">
                                <div class="row g-3">
                                    @forelse($subtask->lampiran as $index => $lampiran)
                                        <div class="col-md-4 col-sm-6">
                                            <div class="card shadow-sm border h-100 lampiran-item position-relative">
                                                @if ($config['can_update_pekerjaan'])
                                                    @php
                                                        $routePrefix = ($userRole == 'karyawan') ? 'karyawan' : (($userRole == 'admin-sdm') ? 'admin_sdm' : 'manajer');
                                                        $routeDelete = route($routePrefix . '.subtask.detail.lampiran', $lampiran->id);
                                                    @endphp
                                                    <button class="btn btn-sm btn-icon btn-danger position-absolute top-0 end-0 m-2 delete-lampiran-btn" 
                                                            data-url="{{ $routeDelete }}" style="z-index: 10;">
                                                        <i class="ri-close-line"></i>
                                                    </button>
                                                @endif
                                                
                                                <div class="card-body text-center p-3 cursor-pointer" data-bs-toggle="modal" data-bs-target="#lampiranModal" data-index="{{ $index }}">
                                                    @php $ext = strtolower(pathinfo($lampiran->lampiran, PATHINFO_EXTENSION)); @endphp
                                                    
                                                    @if(in_array($ext, ['jpg', 'jpeg', 'png', 'gif']))
                                                        <img src="{{ asset('uploads/' . $lampiran->lampiran) }}" class="img-fluid rounded" style="height: 100px; object-fit: cover;">
                                                    @elseif($ext == 'pdf')
                                                        <i class="bi bi-file-earmark-pdf text-danger display-4"></i>
                                                    @else
                                                        <i class="bi bi-file-earmark-text text-primary display-4"></i>
                                                    @endif
                                                    <div class="mt-2 text-truncate small text-muted">{{ $lampiran->lampiran }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-12 text-center py-5">
                                            <div class="avatar avatar-xxl bg-light rounded-circle mb-3 border border-dashed">
                                                <i class="ri-attachment-line fs-30 text-muted"></i>
                                            </div>
                                            <p class="text-muted">Belum ada lampiran.</p>
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
    
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-bold" id="staticBackdropLabel">Update Pekerjaan</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="POST" id="formLaporanKinerja">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tanggal</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="ri-calendar-line"></i></span>
                                <input type="text" class="form-control" name="format-tanggal" id="format-tanggal" placeholder="Pilih Tanggal" required>
                                <input type="hidden" name="tanggal" id="tanggal">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Durasi Pengerjaan</label>
                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="input-group">
                                        <input type="number" min="0" name="durasi_jam" id="durasi_jam" class="form-control" placeholder="0" required>
                                        <span class="input-group-text">Jam</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="input-group">
                                        <input type="number" min="0" max="59" name="durasi_menit" id="durasi_menit" class="form-control" placeholder="0" required>
                                        <span class="input-group-text">Menit</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Keterangan Aktivitas</label>
                            <textarea class="form-control" name="keterangan" id="keterangan" rows="4" placeholder="Deskripsikan pekerjaan Anda..." required></textarea>
                        </div>
                        
                        <input type="hidden" name="sub_task_id" value="{{ $subtask->id }}">
                        <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="btnSubmit">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="lampiranModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title fw-bold">Preview Lampiran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0 bg-light">
                    <div id="carouselLampiran" class="carousel slide" data-bs-interval="false">
                        <div class="carousel-inner">
                            @foreach($subtask->lampiran as $index => $lampiran)
                                @php $ext = strtolower(pathinfo($lampiran->lampiran, PATHINFO_EXTENSION)); @endphp
                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }} p-4 text-center">
                                    @if(in_array($ext, ['jpg', 'jpeg', 'png', 'gif']))
                                        <img src="{{ asset('uploads/' . $lampiran->lampiran) }}" class="img-fluid rounded shadow-sm" style="max-height: 500px;">
                                    @elseif($ext == 'pdf')
                                        <iframe src="{{ asset('uploads/' . $lampiran->lampiran) }}" class="w-100 rounded shadow-sm" style="height: 500px;"></iframe>
                                    @else
                                        <div class="py-5 bg-white rounded">
                                            <i class="ri-file-download-line display-1 text-primary"></i>
                                            <p class="mt-3">File tidak dapat dipreview.</p>
                                            <a href="{{ asset('uploads/' . $lampiran->lampiran) }}" class="btn btn-primary btn-wave"><i class="ri-download-line me-1"></i> Download File</a>
                                        </div>
                                    @endif
                                    <div class="mt-3 text-muted small bg-white d-inline-block px-3 py-1 rounded shadow-sm">{{ $lampiran->lampiran }}</div>
                                </div>
                            @endforeach
                        </div>
                        @if($subtask->lampiran->count() > 1)
                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselLampiran" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon bg-dark rounded-circle p-3" aria-hidden="true"></span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselLampiran" data-bs-slide="next">
                                <span class="carousel-control-next-icon bg-dark rounded-circle p-3" aria-hidden="true"></span>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <style>
        .flatpickr-calendar.open { z-index: 1060 !important; }
        .lampiran-item { cursor: pointer; transition: transform 0.2s; }
        .lampiran-item:hover { transform: translateY(-5px); border-color: #0d6efd !important; }
    </style>
    
    <script>
        $(document).ready(function() {
            const userRole = "{{ auth()->user()->role->slug }}";
            const dateConfigInfo = {
                dateFormat: 'Y-m-d',
                altInput: true,
                altFormat: "d F Y",
                locale: 'id',
            };
            const fpMulai = flatpickr("#tgl_sub_task_info", dateConfigInfo);
            const fpDeadline = flatpickr("#deadline_sub_info", dateConfigInfo);
            const fpSelesai = flatpickr("#tgl_selesai_info", dateConfigInfo);
            
            $('.btn-edit-subtask').click(function(e) {
                e.preventDefault();
                $(this).attr('hidden', true); 
                $('.btn-batal-edit, .btn-submit-subtask').removeAttr('hidden'); 
                
                $('#nama_subtask_info').prop('disabled', false);
                $('#tgl_sub_task_info, #deadline_sub_info, #tgl_selesai_info').prop('disabled', false);
                $('#upload_info').prop('disabled', false); // Enable upload file
                
                if(fpMulai.altInput) fpMulai.altInput.disabled = false;
                if(fpDeadline.altInput) fpDeadline.altInput.disabled = false;
                if(fpSelesai.altInput) fpSelesai.altInput.disabled = false;
            });

            $('.btn-batal-edit').click(function(e) {
                e.preventDefault();
                $('.btn-edit-subtask').removeAttr('hidden');
                $(this).attr('hidden', true);
                $('.btn-submit-subtask').attr('hidden', true);

                $('#nama_subtask_info').prop('disabled', true);
                $('#tgl_sub_task_info, #deadline_sub_info, #tgl_selesai_info').prop('disabled', true);
                $('#upload_info').prop('disabled', true);
                
                if(fpMulai.altInput) fpMulai.altInput.disabled = true;
                if(fpDeadline.altInput) fpDeadline.altInput.disabled = true;
                if(fpSelesai.altInput) fpSelesai.altInput.disabled = true;
            });

            let flatpickrModal = flatpickr("#format-tanggal", {
                dateFormat: "Y-m-d",
                altInput: true,
                altFormat: "d F Y",
                locale: 'id',
                onChange: (selectedDates, dateStr) => $("#tanggal").val(dateStr)
            });

            $('#staticBackdrop').on('hidden.bs.modal', function () {
                $('#formLaporanKinerja')[0].reset();
                flatpickrModal.clear();
                $('#formLaporanKinerja input[name="_method"]').remove();
            });

            $(document).on('click', '.tambahLaporanKinerja', function() {
                $("#staticBackdropLabel").text('Tambah Laporan Kinerja');
                $("#btnSubmit").text("Simpan");
                
                let actionUrl = (userRole === 'karyawan') ? "/karyawan/laporan_kinerja/store" : "/admin_sdm/laporan_kinerja/store";
                $("#formLaporanKinerja").attr("action", actionUrl);
                $("#formLaporanKinerja input[name='_method']").remove();
            });
            
            $(document).on('click', '.updateLaporanKinerja', function() {
                let id = $(this).data('id');
                let tanggal = $(this).data('tanggal');
                let durasi = $(this).data('durasi');
                let keterangan = $(this).data('keterangan');

                let jam = Math.floor(durasi / 60);
                let menit = durasi % 60;
                
                let actionUrl = (userRole === 'karyawan') 
                    ? `/karyawan/laporan_kinerja/update/${id}` 
                    : `/admin_sdm/laporan_kinerja/update/${id}`;

                $("#staticBackdropLabel").text('Edit Laporan Kinerja');
                
                $("#durasi_jam").val(jam);
                $("#durasi_menit").val(menit);
                $("#keterangan").val(keterangan);
                
                flatpickrModal.setDate(tanggal, true);
                $("#tanggal").val(tanggal); 
                
                $("#formLaporanKinerja").attr("action", actionUrl);
                
                if ($("#formLaporanKinerja input[name='_method']").length === 0) {
                    $("#formLaporanKinerja").append(`<input type="hidden" name="_method" value="PUT">`);
                } else {
                    $("#formLaporanKinerja input[name='_method']").val('PUT');
                }

                $("#btnSubmit").text("Update Perubahan");
            });

            $(document).on("click", ".delete-laporan, .delete-lampiran-btn", function(e){
                e.preventDefault();
                e.stopPropagation(); 
                
                let form;
                if($(this).hasClass('delete-lampiran-btn')) {
                    let url = $(this).data('url');
                    form = $(`<form action="${url}" method="POST">@csrf @method('DELETE')</form>`);
                    $('body').append(form);
                } else {
                    form = $(this).closest('form');
                }

                let type = $(this).hasClass('delete-laporan') ? 'Laporan Kinerja' : 'Lampiran';

                Swal.fire({
                    title: `Hapus ${type}?`,
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    confirmButtonText: "Ya, Hapus!",
                    cancelButtonText: "Batal"
                }).then((result) => {
                    if (result.isConfirmed) form.submit();
                });
            });

            const carousel = document.getElementById('carouselLampiran');
            const myModalEl = document.getElementById('lampiranModal');

            if (myModalEl) {
                myModalEl.addEventListener('show.bs.modal', function (event) {
                    const button = event.relatedTarget;
                    const index = button.getAttribute('data-index');
                    const bsCarousel = new bootstrap.Carousel(carousel);
                    bsCarousel.to(index);
                });
            }
        });
    </script>
@endsection