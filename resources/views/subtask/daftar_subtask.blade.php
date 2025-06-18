@extends('layouts.main')
@section('content')
    @foreach ($subtasks as $item)
        @if ($item->lampiran->count())
        <div class="modal fade" id="lampiranModal{{ $item->id }}" tabindex="-1" aria-labelledby="lampiranModalLabel{{ $item->id }}" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Lampiran {{ $loop->iteration }} Sub Task - {{ $item->task->nama_task }}</h5>
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
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="staticBackdropLabel"></h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="POST" id="formSubtask" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="task_id">Task</label>
                            <select name="task_id" id="task_id" data-trigger class="form-control" required>
                                <option selected disabled>Pilih Task</option>
                                @foreach ($tasks as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama_task }} 
                                        ({{ $item->tipe_task->nama_tipe . ' - ' ?? '' }}
                                        {{ $item->project_perusahaan?->nama_project . ' - ' ?? '' }}
                                        {{ $item->project_perusahaan?->perusahaan?->nama_perusahaan ?? '' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
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
        <div class="mb-2">
            <button type="button" class="btn btn-primary tambahSubtask" data-bs-toggle="modal"
                data-bs-target="#staticBackdrop">
                Tambah Sub Task
            </button>
        </div>
        <div class="card custom-card">
            <div class="card-header">
                <div class="card-title">
                    Daftar Task
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatable-basic" class="table table-bordered w-100 text-nowrap">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Sub Task (Task - Tipe Task)</th>
                                <th>Project (Instansi)</th>
                                <th>Deadline</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (Auth::check() && Auth::user()->role->slug == ('karyawan') || Auth::user()->role->slug == ('admin-sdm'))
                                @foreach ($userSubtasks as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->nama_subtask ?? '-' }} 
                                            ({{ $item->task?->nama_task ?? '-' }} - 
                                            {{ $item->task?->tipe_task?->nama_tipe ?? '-' }})</td>
                                        <td>{{ $item->task?->project_perusahaan?->nama_project ?? '-' }} ({{ $item->task?->project_perusahaan?->perusahaan?->nama_perusahaan ?? '-' }})</td>
                                        <td>{{ $item->deadline ? \Carbon\Carbon::parse($item->deadline)->translatedFormat('l, d F Y') : '-' }}</td>
                                        <td class="text-center">
                                            @if($item->detail_sub_task->isEmpty())
                                                <span class="badge bg-info">Belum ada laporan kinerja</span>
                                            @elseif($item->status === 'revise')
                                                <span class="badge bg-warning"
                                                    data-bs-toggle="tooltip" 
                                                    data-bs-custom-class="tooltip-warning"
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
                                        <td class="text-center text-nowrap">
                                            @if ($item->lampiran->count())
                                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#lampiranModal{{ $item->id }}">
                                                    <i class="ti ti-file-search"
                                                    data-bs-toggle="tooltip" 
                                                    data-bs-custom-class="tooltip-primary"
                                                    data-bs-placement="top" title="Lihat Lampiran!"></i>
                                                </button>
                                            @else
                                                <span class="text-muted">Tidak ada lampiran</span> </br>
                                            @endif
                                            <a 
                                            @if (Auth::check() && Auth::user()->role->slug == 'karyawan')
                                                href="{{ route('karyawan.subtask.detail', $item->id) }}" class="btn btn-secondary btn-sm"
                                                @elseif (Auth::check() && Auth::user()->role->slug == 'admin-sdm')
                                                    href="{{ route('admin_sdm.subtask.detail', $item->id) }}" class="btn btn-secondary btn-sm"
                                            @endif
                                                data-bs-toggle="tooltip" data-bs-custom-class="tooltip-secondary"
                                                data-bs-placement="top" title="Detail Task!"><i class='bx bx-detail'></i>
                                            </a>
                                            <form 
                                            @if (Auth::check() && Auth::user()->role->slug == 'karyawan')
                                                action="{{ route('karyawan.subtask.delete', $item->id) }}" method="POST"
                                                @elseif (Auth::check() && Auth::user()->role->slug == 'admin-sdm')
                                                    action="{{ route('admin_sdm.subtask.delete', $item->id) }}" method="POST"
                                            @endif
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                    class="btn btn-danger btn-sm delete"
                                                    data-id="{{ $item->id }}"
                                                    data-nama_subtask="{{ $item->nama_subtask }}" 
                                                    data-bs-toggle="tooltip"
                                                    data-bs-custom-class="tooltip-danger" d
                                                    ata-bs-placement="top"
                                                    title="Hapus Sub Task!">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            @if (Auth::check() && Auth::user()->role->slug == ('manager'))
                                @foreach ($subtasks as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->nama_subtask ?? '-' }} 
                                            ({{ $item->task?->nama_task ?? '-' }} - 
                                            {{ $item->task?->tipe_task?->nama_tipe ?? '-' }})</td>
                                        <td>{{ $item->task?->project_perusahaan?->nama_project ?? '-' }} ({{ $item->task?->project_perusahaan?->perusahaan?->nama_perusahaan ?? '-' }})</td>
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
                                            @if ($item->lampiran->count())
                                                <button type="button" 
                                                    class="btn btn-primary btn-sm" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#lampiranModal{{ $item->id }}">
                                                    <i class="ti ti-file-search"
                                                    data-bs-toggle="tooltip" 
                                                    data-bs-custom-class="tooltip-primary"
                                                    data-bs-placement="top" title="Lihat Lampiran!"></i>
                                                </button>
                                            @else
                                                <span class="text-muted">Tidak ada lampiran</span> </br>
                                            @endif
                                            <a href="{{ route('manajer.subtask.detail', $item->id) }}" class="btn btn-secondary btn-sm"
                                                data-bs-toggle="tooltip" data-bs-custom-class="tooltip-secondary"
                                                data-bs-placement="top" title="Detail Task!"><i class='bx bx-detail'></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <style>
        .badge { cursor: pointer; }
        .fa-info-circle { font-size: 0.8em; }
    </style>
    <script>
        $(document).ready(function(){
            const userRole = "{{ auth()->user()->role->slug }}";
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
            let flatpickrInstance1 = flatpickr("#format-deadline", {
                dateFormat: "Y-m-d",
                altInput: true,
                altFormat: "d F Y",
                locale: 'id',
                onChange: function(selectedDates, dateStr, instance) {
                    document.getElementById("deadline").value = dateStr;
                },
                appendTo: document.getElementById("staticBackdrop")
            });
            $(document).on('click', '.tambahSubtask', function(e) {
                e.preventDefault();
    
                $(".modal-title").text('Tambah Sub Task');
                $("#tanggal").val('');
    
                $("#previewImage, #previewPDF").hide().attr("src", "");
                $("#detail_upload").html("");
    
                $("#btnSubmit").text("Simpan").show();
                $("#upload").prop("disabled", false);
                if (userRole === 'karyawan') {
                    $("#formSubtask").attr("action", "/karyawan/subtask/store");
                } else if (userRole === 'admin-sdm') {
                    $("#formSubtask").attr("action", "/admin_sdm/subtask/store");
                }
                $("#formSubtask input[name='_method']").remove();
            });
            $("#upload").change(function () {
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
            $(".delete").click(function(e) {
                e.preventDefault();

                let id = $(this).data("id");
                let subtask = $(this).data("nama_subtask");
                let actionUrl = '';
                if (userRole === 'karyawan') {
                    actionUrl = '/karyawan/subtask/delete/' + id;
                } else if (userRole === 'admin-sdm') {
                    actionUrl = '/admin_sdm/subtask/delete/' + id;
                }

                Swal.fire({
                    title: "Konfirmasi Hapus Sub Task",
                    text: "Apakah kamu yakin ingin menghapus subtask '" + subtask + "' ?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Ya, Hapus!",
                    cancelButtonText: "Batal"
                }).then((result) => {
                    if (result.isConfirmed) {
                        let form = $("<form>", {
                            action: actionUrl,
                            method: "POST"
                        }).append(
                            $("<input>", {
                                type: "hidden",
                                name: "_token",
                                value: "{{ csrf_token() }}"
                            }),
                            $("<input>", {
                                type: "hidden",
                                name: "_method",
                                value: "DELETE"
                            })
                        );
                        $("body").append(form);
                        form.submit();
                    }
                });
            });

        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })
        })
    </script>
@endsection
