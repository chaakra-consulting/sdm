@extends('layouts.main')

@section('content')
    <div class="modal fade" id="staticBackdropViewDokumen" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="staticBackdropLabel">Modal title
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <iframe src="" width="100%" height="700px" id="viewDokumenPdf"></iframe>
                    <img id="previewImage" src="" alt="" class="img-fluid mt-2 d-block mx-auto"
                        width="100%">
                    <p class="text-center" id="detail_upload"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kembali</button>
                </div>
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
                <form action="" method="POST" id="formDaftarTask" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group row">
                            <div class="col-md-12" id="tipeTaskWrapper">
                                <label for="tipe_task">Tipe Task</label>
                                <select name="tipe_task" id="tipe_task" data-trigger class="form-control" required>
                                    <option selected disabled>Pilih Tipe Task</option>
                                    @foreach ($tipeTask as $item)
                                        <option value="{{ $item->slug }}">{{ $item->nama_tipe }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 d-none" id="projectWrapper">
                                <label for="project_perusahaan_id">Project</label>
                                <select name="project_perusahaan_id" id="project_perusahaan_id" data-trigger class="form-control" required>
                                    <option selected disabled>Pilih Project</option>
                                    @foreach ($project as $item)
                                        <option value="{{ $item->id }}">{{ $item->nama_project }} ({{ $item->perusahaan->nama_perusahaan }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-8">
                                <label for="nama_task">Nama Task</label>
                                <input type="text" name="nama_task" id="nama_task" class="form-control" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="format-tgl_task">Tanggal Mulai</label>
                                <div class="input-group date-container">
                                    <div class="input-group-text text-muted"> <i class="ri-calendar-line"></i> </div>
                                    <input type="text" class="form-control" name="format-tgl_task" id="format-tgl_task" placeholder="Tanggal Mulai" required>
                                    <input type="hidden" name="tgl_task" id="tgl_task" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <textarea class="form-control" name="keterangan" id="keterangan" cols="10" rows="5"></textarea>
                        </div>
                        @if (Auth::check() && Auth::user()->role->slug == 'manager')
                            <div class="form-group row">
                                <label for="user">Anggota Task</label>
                                <select multiple class="form-select" aria-label="user" name="user[]"
                                    id="user" required>
                                    @foreach ($users as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        <div class="form-group">
                            <label for="upload">Lampiran</label>
                                <input type="file" class="form-control" name="upload" id="upload">
                                <img id="previewImage2" src="" alt=""
                                    class="img-fluid mt-2 d-block mx-auto" style="max-width: 500px; display: none;">
                                <iframe id="previewPDF" src="" width="100%" height="400px"
                                    style="display: none;"></iframe>
                                <p class="text-center" id="detail_upload"></p>
                        </div>
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
            <button type="button" class="btn btn-primary tambahTask" data-bs-toggle="modal"
                data-bs-target="#staticBackdrop">
                Tambah Task
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
                    <table id="datatable-basic" class="table table-bordered w-100">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Task (Project - Instansi)</th>
                                <th>Tipe Task</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (Auth::check() && Auth::user()->role->slug == 'manager')
                                @foreach ($tasks as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->nama_task }} ({{ $item->project_perusahaan->nama_project ?? '' }} - {{ $item->project_perusahaan->perusahaan->nama_perusahaan ?? '' }})</td>
                                        <td>{{ $item->tipe_task->nama_tipe ?? '-' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->tgl_task)->translatedFormat('l, d F Y') }}</td>
                                        <td>
                                            @if ($item->status == 'selesai')
                                                @if ($item->tgl_selesai && \Carbon\Carbon::parse($item->tgl_selesai)->gt(\Carbon\Carbon::parse($item->deadline)))
                                                    <span class="badge bg-warning">Selesai (Telat)</span>
                                                @else
                                                    <span class="badge bg-success">Selesai</span>
                                                @endif
                                            @else
                                                @if ($item->deadline && \Carbon\Carbon::parse($item->deadline)->isPast())
                                                    <span class="badge bg-danger">Telat</span>
                                                @else
                                                    @if ($item->status == 'proses')
                                                        <span class="badge bg-info">Proses</span>
                                                    @elseif ($item->status == 'belum')
                                                        <span class="badge bg-secondary">Belum</span>
                                                    @endif
                                                @endif
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="javascript:void(0);" class="btn btn-primary btn-sm btnViewDokumenPdf"
                                                data-nama_task="{{ $item->nama_task }}"
                                                data-dokumen="{{ asset('uploads/' . $item->upload) }}"
                                                data-bs-toggle="tooltip" data-bs-custom-class="tooltip-primary"
                                                data-bs-placement="top" title="Lihat Lampiran!">
                                                <i class="ti ti-file-search"></i>
                                            </a>
                                            <a href="{{ route('manajer.detail.task', $item->id) }}" class="btn btn-secondary btn-sm"
                                                data-bs-toggle="tooltip" data-bs-custom-class="tooltip-secondary"
                                                data-bs-placement="top" title="Detail Task!"><i class='bx bx-detail'></i>
                                            </a>
                                            @if ($item->tipe_task->slug == 'task-project')
                                            <form action="{{ route('manajer.delete.task', $item->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm delete"
                                                    data-id="{{ $item->id }}" data-nama_task="{{ $item->nama_task }}"
                                                    data-bs-toggle="tooltip" data-bs-custom-class="tooltip-danger"
                                                    data-bs-placement="top" title="Hapus Task!">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>                                                
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach                                
                            @endif
                            @if (Auth::check() && Auth::user()->role->slug == 'karyawan')
                                @foreach ($userTasks as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->task->nama_task }} ({{ $item->task->project_perusahaan->nama_project ?? '' }} - {{ $item->task->project_perusahaan->perusahaan->nama_perusahaan ?? '' }})</td>
                                        <td>{{ $item->task->tipe_task->nama_tipe ?? '-' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->task->tgl_task)->translatedFormat('l, d F Y') }}</td>
                                        <td>
                                            @if ($item->task->status == 'selesai')
                                                @if ($item->task->tgl_selesai && \Carbon\Carbon::parse($item->task->tgl_selesai)->gt(\Carbon\Carbon::parse($item->task->deadline)))
                                                    <span class="badge bg-warning">Selesai (Telat)</span>
                                                @else
                                                    <span class="badge bg-success">Selesai</span>
                                                @endif
                                            @else
                                                @if ($item->task->deadline && \Carbon\Carbon::parse($item->task->deadline)->isPast())
                                                    <span class="badge bg-danger">Telat</span>
                                                @else
                                                    @if ($item->task->status == 'proses')
                                                        <span class="badge bg-info">Proses</span>
                                                    @elseif ($item->task->status == 'belum')
                                                        <span class="badge bg-secondary">Belum</span>
                                                    @endif
                                                @endif
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="javascript:void(0);" class="btn btn-primary btn-sm btnViewDokumenPdf"
                                                data-nama_task="{{ $item->task->nama_task }}"
                                                data-dokumen="{{ asset('uploads/' . $item->task->upload) }}"
                                                data-bs-toggle="tooltip" data-bs-custom-class="tooltip-primary"
                                                data-bs-placement="top" title="Lihat Lampiran!">
                                                <i class="ti ti-file-search"></i>
                                            </a>
                                            <a href="{{ route('karyawan.detail.task', $item->task->id) }}" class="btn btn-secondary btn-sm"
                                                data-bs-toggle="tooltip" data-bs-custom-class="tooltip-secondary"
                                                data-bs-placement="top" title="Detail Task!"><i class='bx bx-detail'></i>
                                            </a>
                                            @if ($item->task->tipe_task->slug != 'task-project' && $item->task->tipe_task->slug != 'task-wajib')
                                                <form action="{{ route('karyawan.delete.task', $item->id) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm delete"
                                                        data-id="{{ $item->id }}" data-nama_task="{{ $item->nama_task }}"
                                                        data-bs-toggle="tooltip" data-bs-custom-class="tooltip-danger"
                                                        data-bs-placement="top" title="Hapus Task!">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>                                           
                                            @endif
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
<script>
    $(document).ready(function() {
            $("#upload").change(function() {
                let file = this.files[0];
                if (file) {
                    let fileUrl = URL.createObjectURL(file);
                    let fileExtension = file.name.split('.').pop().toUpperCase();
            
                    $("#previewImage2, #previewPDF").hide().attr("src", "");
                    $("#detail_upload").html("");
            
                    if (file.name.match(/\.(jpg|jpeg|png)$/i)) {
                        $("#previewImage2").attr("src", fileUrl).show();
                        $("#previewPDF").hide();
                        $("#detail_upload").html(
                            `<strong>Preview Gambar:</strong> <a href="${fileUrl}" target="_blank">Lihat Gambar</a>`
                        );
                    } else if (file.name.match(/\.pdf$/i)) {
                        $("#previewPDF").attr("src", fileUrl).show();
                        $("#previewImage2").hide();
                        $("#detail_upload").html(
                            `<strong>Preview PDF:</strong> <a href="${fileUrl}" target="_blank">Lihat PDF</a>`
                        );
                    } else {
                        $("#previewImage2, #previewPDF").hide();
                        $("#detail_upload").html(`<strong>File Terpilih:</strong> ${fileExtension}`);
                    }
                }
            });
            $("#staticBackdrop").on('show.bs.modal', function(){
                flatpickr("#format-tgl_task",{
                    dateFormat: "Y-m-d",
                    altInput: true,
                    altFormat:"d F Y",
                    static: true,
                    locale: 'id',
                    onChange: function(selectedDates, dateStr, instance) {
                    $('#tgl_task').val(dateStr);
                },
                appendTo: this.querySelector('.date-container')
                });
                $("#tipe_task").trigger('change');
            });
            $('#tipe_task').on('change', function () {
                const selectedTipe = $(this).val();

                if (selectedTipe === 'task-project') {
                    $('#projectWrapper').removeClass('d-none');
                    $('#project_perusahaan_id').val('').prop('required', true);

                    $('#tipeTaskWrapper').removeClass('col-md-12').addClass('col-md-6');
                } else {
                    $('#projectWrapper').addClass('d-none');
                    $('#project_perusahaan_id').prop('required', false);

                    $('#tipeTaskWrapper').removeClass('col-md-6').addClass('col-md-12');
                }
            });
            $(document).on('click', '.btnViewDokumenPdf', function(e) {
                e.preventDefault();
                $('#staticBackdropViewDokumen').modal('show');
                $('#staticBackdropViewDokumen .modal-title').text('Lampiran : ' + $(this).data(
                'nama_task'));

                let dokumen = $(this).data('dokumen');
                let fileExtension = dokumen ? dokumen.split('.').pop().toUpperCase() : "";

                $("#previewImage, #viewDokumenPdf").hide().attr("src", "");
                $("#detail_upload").html("");

                if (!dokumen || dokumen == "null" || dokumen.trim() == "") {
                    $("#detail_upload").html(
                        '<strong class="text-danger">Tidak ada lampiran tersedia</strong>');
                } else if (dokumen.match(/\.(jpg|jpeg|png)$/i)) {
                    $("#previewImage").attr("src", dokumen).show();
                    $("#viewDokumenPdf").hide();
                    $("#detail_upload").html(
                        `<strong>Preview Gambar:</strong> <a href="${dokumen}" target="_blank">Lihat Gambar</a>`
                    );
                } else if (dokumen.match(/\.pdf$/i)) {
                    $("#viewDokumenPdf").attr("src", dokumen).show();
                    $("#previewImage").hide();
                    $("#detail_upload").html(
                        `<strong>Preview PDF:</strong> <a href="${dokumen}" target="_blank">Lihat PDF</a>`
                    );
                } else {
                    $("#previewImage, #viewDokumenPdf").hide();
                    $("#detail_upload").html(`
                        <strong>File Terpilih:</strong> ${fileExtension} <br>
                        <a href="${dokumen}" download class="btn btn-sm btn-success mt-2">
                            <i class="bi bi-download"></i> Unduh File
                        </a>
                    `);
                }
            });
            const userRole = "{{ auth()->user()->role->slug }}";
            $(document).on('click', '.tambahTask', function(e) {
                e.preventDefault();

                $(".modal-title").text('Tambah Task');
                $("#tipe_task").val('');
                $("#nama_task").val('');
                $("#keterangan").val('');
                $("#user").val('Pilih Anggota Task').trigger('change');    

                $("#previewImage2, #previewPDF").hide().attr("src", "");
                $("#detail_upload").html("");

                $("#btnSubmit").text("Simpan").show();
                $("#upload").prop("disabled", false);
                if (userRole === "manager") {
                    $("#formDaftarTask").attr('action', '/manajer/task/store');
                } else if (userRole === "karyawan") {
                    $("#formDaftarTask").attr('action', '/karyawan/task/store');
                }
                $("#formDaftarTask input[name='_method']").remove();

                $('#projectWrapper').addClass('d-none');
                $('#project_perusahaan_id').val('').prop('required', false);
                $('#tipeTaskWrapper').removeClass('col-md-6').addClass('col-md-12');
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            new Choices('#user', {
                removeItemButton: true,
                searchEnabled: true,
                noResultsText: "Tidak ada hasil yang cocok",
                noChoicesText: "Tidak ada pilihan tersedia"
            });
            // flatpickr("#format-tgl_task", {
            //     dateFormat: "Y-m-d",
            //     altInput: true,
            //     altFormat: "d F Y",
            //     onChange: function(selectedDates, dateStr, instance) {
            //         document.getElementById("tgl_task").value = dateStr;
            //     },
            //     appendTo: document.getElementById("staticBackdrop")
            // });
        });
    </script>
@endsection
