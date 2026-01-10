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
                                <select name="tipe_task" id="tipe_task" class="form-control" required>
                                    <option selected disabled>Pilih Tipe Task</option>
                                    @foreach ($tipeTask as $item)
                                        @php
                                            $userRole = Auth::user()->role->slug;
                                            $taskSlug = $item->slug;
                                            $showOption = false;

                                            if ($userRole == 'manager' && $taskSlug == 'task-project') {
                                                $showOption = true;
                                            } elseif ($userRole == 'admin-sdm' && $taskSlug != 'task-project') {
                                                $showOption = true;
                                            } elseif ($userRole == 'karyawan' && $taskSlug == 'task-tambahan') {
                                                $showOption = true;
                                            }
                                        @endphp
                                        @if ($showOption)
                                            <option value="{{ $item->slug }}"
                                                @if (
                                                    ($userRole == 'manager' && $taskSlug == 'task-project') || 
                                                    ($userRole == 'karyawan' && $taskSlug == 'task-tambahan')
                                                    ) 
                                                    selected
                                                @endif
                                                >
                                                {{ $item->nama_tipe }}
                                            </option>                                        
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 d-none" id="projectWrapper">
                                <label for="project_perusahaan_id">Project</label>
                                <select name="project_perusahaan_id" id="project_perusahaan_id" class="form-control" required>
                                    <option selected disabled>Pilih Project</option>
                                    @foreach ($project as $item)
                                        <option value="{{ $item->id }}">
                                            {{ $item->nama_project }} ({{ $item->perusahaan->nama_perusahaan }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="nama_task">Nama Task</label>
                            <input type="text" name="nama_task" id="nama_task" class="form-control" required>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="format-tgl_task">Tanggal Mulai</label>
                                <div class="input-group date-container">
                                    <div class="input-group-text text-muted"> <i class="ri-calendar-line"></i> </div>
                                    <input type="text" class="form-control" name="format-tgl_task" id="format-tgl_task"
                                        placeholder="Mulai" required>
                                    <input type="hidden" name="tgl_task" id="tgl_task" required>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="format-deadline_task">Deadline</label>
                                <div class="input-group date-container">
                                    <div class="input-group-text text-muted"> <i class="ri-calendar-line"></i> </div>
                                    <input type="text" class="form-control" name="format-deadline_task"
                                        id="format-deadline_task" placeholder="Deadline">
                                    <input type="hidden" name="deadline_task" id="deadline_task">
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
                                <select multiple class="form-select" aria-label="user" name="user[]" id="user"
                                    required>
                                    @foreach ($users as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        <div class="form-group">
                            <label for="upload">Lampiran</label>
                            <input type="file" class="form-control" name="upload" id="upload">
                            <img id="previewImage2" src="" alt="" class="img-fluid mt-2 d-block mx-auto"
                                style="max-width: 500px; display: none;">
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
                    <table id="datatable-basic" class="table table-bordered w-100 text-nowrap">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Task (Project - Instansi)</th>
                                <th>Tipe Task</th>
                                <th>Deadline</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (Auth::check() && Auth::user()->role->slug == 'manager')
                                @foreach ($tasks as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->nama_task }} ({{ $item->project_perusahaan->nama_project ?? '' }} -
                                            {{ $item->project_perusahaan->perusahaan->nama_perusahaan ?? '' }})</td>
                                        <td>{{ $item->tipe_task->nama_tipe ?? '-' }}</td>
                                        <td>
                                            {{ $item->deadline ? Carbon\Carbon::parse($item->deadline)->translatedFormat('l, d F Y') : '-' }}
                                        </td>
                                        <td class="text-center">
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
                                            @if (!empty($item->upload))
                                                <a href="javascript:void(0);" class="btn btn-primary btn-sm btnViewDokumenPdf"
                                                    data-nama_task="{{ $item->nama_task }}"
                                                    data-dokumen="{{ asset('uploads/' . $item->upload) }}"
                                                    data-bs-toggle="tooltip" data-bs-custom-class="tooltip-primary"
                                                    data-bs-placement="top" title="Lihat Lampiran!">
                                                    <i class="ti ti-file-search"></i>
                                                </a>
                                            @else
                                                <span class="text-muted">Tidak ada lampiran</span><br>
                                            @endif
                                            <a href="{{ route('manajer.detail.task', $item->id) }}"
                                                class="btn btn-secondary btn-sm" data-bs-toggle="tooltip"
                                                data-bs-custom-class="tooltip-secondary" data-bs-placement="top"
                                                title="Detail Task!"><i class='bx bx-detail'></i>
                                            </a>
                                            @if ($item->tipe_task->slug == 'task-project')
                                                <form action="{{ route('manajer.delete.task', $item->id) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm delete"
                                                        data-id="{{ $item->id }}"
                                                        data-nama_task="{{ $item->nama_task }}" data-bs-toggle="tooltip"
                                                        data-bs-custom-class="tooltip-danger" data-bs-placement="top"
                                                        title="Hapus Task!">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @elseif (
                                (Auth::check() && Auth::user()->role->slug == 'karyawan') ||
                                    (Auth::check() && Auth::user()->role->slug == 'admin-sdm'))
                                @foreach ($userTasks as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->task->nama_task }}
                                            ({{ $item->task->project_perusahaan->nama_project ?? '' }} -
                                            {{ $item->task->project_perusahaan->perusahaan->nama_perusahaan ?? '' }})</td>
                                        <td>{{ $item->task->tipe_task->nama_tipe ?? '-' }}</td>
                                        <td>
                                            {{ $item->task->deadline ? Carbon\Carbon::parse($item->task->deadline)->translatedFormat('l, d F Y') : '-' }}
                                        </td>
                                        <td class="text-center">
                                            @if ($item->task->status == 'selesai')
                                                @if (
                                                    $item->task->tgl_selesai &&
                                                        \Carbon\Carbon::parse($item->task->tgl_selesai)->gt(\Carbon\Carbon::parse($item->task->deadline)))
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
                                            @if (!empty($item->task->upload))
                                                <a href="javascript:void(0);" class="btn btn-primary btn-sm btnViewDokumenPdf"
                                                    data-nama_task="{{ $item->task->nama_task }}"
                                                    data-dokumen="{{ asset('uploads/' . $item->task->upload) }}"
                                                    data-bs-toggle="tooltip" data-bs-custom-class="tooltip-primary"
                                                    data-bs-placement="top" title="Lihat Lampiran!">
                                                    <i class="ti ti-file-search"></i>
                                                </a>
                                            @else
                                                <span class="text-muted">Tidak ada lampiran</span><br>
                                            @endif
                                            <a 
                                            @if (Auth::check() && Auth::user()->role->slug == 'karyawan')
                                                href="{{ route('karyawan.detail.task', $item->task->id) }}"
                                                @elseif (Auth::check() && Auth::user()->role->slug == 'admin-sdm')
                                                    href="{{ route('admin_sdm.detail.task', $item->task->id) }}"
                                            @endif
                                                class="btn btn-secondary btn-sm" data-bs-toggle="tooltip"
                                                data-bs-custom-class="tooltip-secondary" data-bs-placement="top"
                                                title="Detail Task!"><i class='bx bx-detail'></i>
                                            </a>
                                            @if (Auth::check() && Auth::user()->role->slug == 'karyawan')
                                                @if ($item->task->tipe_task->slug != 'task-project' && $item->task->tipe_task->slug != 'task-wajib')
                                                    <form action="{{ route('karyawan.delete.task', $item->task->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm delete"
                                                            data-id="{{ $item->task->id }}"
                                                            data-nama_task="{{ $item->task->nama_task }}"
                                                            data-bs-toggle="tooltip" 
                                                            data-bs-custom-class="tooltip-danger"
                                                            data-bs-placement="top" 
                                                            title="Hapus Task!">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            @elseif (Auth::check() && Auth::user()->role->slug == 'admin-sdm')
                                                @if ($item->task->tipe_task->slug != 'task-project')
                                                    <form action="{{ route('admin_sdm.delete.task', $item->task->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm delete"
                                                            data-id="{{ $item->task->id }}"
                                                            data-nama_task="{{ $item->task->nama_task }}"
                                                            data-bs-toggle="tooltip" 
                                                            data-bs-custom-class="tooltip-danger"
                                                            data-bs-placement="top" 
                                                            title="Hapus Task!">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
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
    <style>
        .flatpickr-calendar.open {
            z-index: 9999999 !important;
            position: absolute !important;
        }
    </style>
    
    <script>
        $(document).ready(function() {
            setTimeout(function() {
                const userRole = "{{ auth()->user()->role->slug }}";
                const projectsData = {!! json_encode($project) !!}; 
                
                let choicesTipeTask = null; 
                let choicesProject = null;
                let currentProjectStart = null;
                let currentProjectEnd = null;
                
                function cleanDate(dateString) {
                    if (!dateString) return null;
                    return dateString.split('T')[0];
                }
                
                let fpStartDate = flatpickr("#format-tgl_task", {
                    dateFormat: "Y-m-d",
                    altInput: true,
                    altFormat: "d F Y",
                    locale: 'id',
                    disableMobile: true,
                    static: false, 
                    appendTo: document.body,
                    onChange: function(selectedDates, dateStr, instance) {
                        $('#tgl_task').val(dateStr);
                        if (dateStr) {
                            fpDeadline.set('minDate', dateStr);
                        } else {
                            fpDeadline.set('minDate', currentProjectStart);
                        }
                    }
                });

                let fpDeadline = flatpickr("#format-deadline_task", {
                    dateFormat: "Y-m-d",
                    altInput: true,
                    altFormat: "d F Y",
                    locale: 'id',
                    disableMobile: true,
                    static: false,
                    appendTo: document.body,
                    onChange: function(selectedDates, dateStr, instance) {
                        $('#deadline_task').val(dateStr);
                    }
                });
                
                function updateDateLimitsFromProject(projectId) {
                    const selectedProject = projectsData.find(p => p.id == projectId);
                    
                    if (selectedProject) {
                        let start = cleanDate(selectedProject.waktu_mulai);
                        let end = cleanDate(selectedProject.deadline);

                        currentProjectStart = start;
                        currentProjectEnd = end;
                        
                        $('#tgl_task').val('');
                        $('#format-tgl_task').val('');
                        $('#deadline_task').val('');
                        $('#format-deadline_task').val('');
                        fpStartDate.clear();
                        fpDeadline.clear();
                        
                        if (start) fpStartDate.set('minDate', start);
                        else fpStartDate.set('minDate', null);
                        if (end) fpStartDate.set('maxDate', end);
                        else fpStartDate.set('maxDate', null);

                        if (start) fpDeadline.set('minDate', start);
                        else fpDeadline.set('minDate', null);

                        if (end) fpDeadline.set('maxDate', end);
                        else fpDeadline.set('maxDate', null);
                    } else {
                        currentProjectStart = null;
                        currentProjectEnd = null;
                        fpStartDate.set('minDate', null); fpStartDate.set('maxDate',null);
                        fpDeadline.set('minDate', null); fpDeadline.set('maxDate', null);
                    }
                }

                function checkTipeTaskLogika(selectedValue) {
                    if (selectedValue == 'task-project') {
                        $('#projectWrapper').removeClass('d-none');
                        $('#project_perusahaan_id').prop('required', true);
                        $('#tipeTaskWrapper').removeClass('col-md-12').addClass('col-md-6');
                    } else {
                        $('#projectWrapper').addClass('d-none');
                        $('#project_perusahaan_id').prop('required', false);
                        
                        if (choicesProject) choicesProject.removeActiveItems();
                        
                        currentProjectStart = null;
                        fpStartDate.set('minDate', null); fpStartDate.set('maxDate', null);
                        fpDeadline.set('minDate', null); fpDeadline.set('maxDate', null);

                        $('#tipeTaskWrapper').removeClass('col-md-6').addClass('col-md-12');
                    }
                }
                
                try {
                    const elementTipeTask = document.getElementById('tipe_task');
                    if (elementTipeTask) {
                        if (elementTipeTask.choices) { elementTipeTask.choices.destroy(); }

                        choicesTipeTask = new Choices(elementTipeTask, {
                            searchEnabled: false,
                            itemSelectText: '',
                            shouldSort: false,
                        });
                        elementTipeTask.addEventListener('change', function(event) {
                            checkTipeTaskLogika($('#tipe_task').val());
                        });
                        checkTipeTaskLogika($('#tipe_task').val());
                    }

                    const elementProject = document.getElementById('project_perusahaan_id');
                    if (elementProject) {
                        if (elementProject.choices) { elementProject.choices.destroy(); }

                        choicesProject = new Choices(elementProject, {
                            searchEnabled: true,
                            itemSelectText: '',
                            shouldSort: false,
                            placeholder: true,
                            placeholderValue: 'Pilih Project',
                        });
                        elementProject.addEventListener('change', function(event) {
                            let pid = $('#project_perusahaan_id').val();
                            if(pid) updateDateLimitsFromProject(pid);
                        });
                    }
                } catch (error) {
                    console.error("Choices JS Error:", error);
                }
                
                $(document).on('click', '.tambahTask', function(e) {
                    e.preventDefault();
                    $(".modal-title").text('Tambah Task');
                    
                    $("#nama_task").val('');
                    $("#keterangan").val('');
                    $("#tgl_task").val('');
                    $("#deadline_task").val('');
                    $("#user").val('').trigger('change'); 
                    $("#upload").val('');
                    $("#previewImage2, #previewPDF").hide().attr("src", "");
                    $("#detail_upload").html("");

                    fpStartDate.clear();
                    fpDeadline.clear();
                    fpStartDate.set('minDate', null); fpStartDate.set('maxDate', null);
                    fpDeadline.set('minDate', null); fpDeadline.set('maxDate', null);

                    if(choicesProject) choicesProject.removeActiveItems();

                    if (choicesTipeTask) {
                        if (userRole === 'manager') {
                            choicesTipeTask.setChoiceByValue('task-project');
                            checkTipeTaskLogika('task-project');
                        } else {
                            choicesTipeTask.removeActiveItems();
                            checkTipeTaskLogika('');
                        }
                    }
                    
                    let actionUrl = '';
                    if (userRole == "manager") actionUrl = '/manajer/task/store';
                    else if (userRole == "karyawan") actionUrl = '/karyawan/task/store';
                    else if (userRole == "admin-sdm") actionUrl = '/admin_sdm/task/store';
                    
                    $("#formDaftarTask").attr('action', actionUrl);
                    $("#formDaftarTask input[name='_method']").remove();
                    $("#formDaftarTask").append('<input type="hidden" name="_method" value="POST">');
                });

                $(".updateTask").click(function(e) {
                    e.preventDefault();
                    let id = $(this).data("id");
                    let nama = $(this).data("nama_task");
                    let tgl = $(this).data("tgl_task");
                    let deadline_task = $(this).data("deadline_task");
                    let keterangan = $(this).data("keterangan");
                    let project = $(this).data("project");
                    let user = $(this).data("user");

                    $(".modal-title").text("Update Task");

                    let updateUrl = '';
                    if (userRole == 'manager') updateUrl = "/manajer/task/update/" + id;
                    else if (userRole == 'karyawan') updateUrl = "/karyawan/task/update/" + id;
                    else if (userRole == 'admin-sdm') updateUrl = "/admin_sdm/task/update/" + id;

                    $("#formDaftarTask").attr("action", updateUrl);
                    $("#formDaftarTask input[name='_method']").remove();
                    $("#formDaftarTask").append('<input type="hidden" name="_method" value="PUT">');

                    $("#nama_task").val(nama);
                    $("#keterangan").val(keterangan);
                    $("#task_id").val(id);
                    $("#user_id").val(user);

                    if (project) {
                        if (choicesTipeTask) choicesTipeTask.setChoiceByValue('task-project');
                        checkTipeTaskLogika('task-project');
                        
                        if (choicesProject) {
                            choicesProject.setChoiceByValue(project.toString());
                            updateDateLimitsFromProject(project);
                        }
                    } 

                    if (tgl) fpStartDate.setDate(tgl, true);
                    if (deadline_task) fpDeadline.setDate(deadline_task, true);

                    $("#upload").prop("disabled", false);
                });
                
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
                            $("#detail_upload").html(`<strong>Preview Gambar:</strong> <a href="${fileUrl}" target="_blank">Lihat Gambar</a>`);
                        } else if (file.name.match(/\.pdf$/i)) {
                            $("#previewPDF").attr("src", fileUrl).show();
                            $("#previewImage2").hide();
                            $("#detail_upload").html(`<strong>Preview PDF:</strong> <a href="${fileUrl}" target="_blank">Lihat PDF</a>`);
                        } else {
                            $("#previewImage2, #previewPDF").hide();
                            $("#detail_upload").html(`<strong>File Terpilih:</strong> ${fileExtension}`);
                        }
                    }
                });

                $(".delete").click(function(e) {
                    e.preventDefault();
                    let taskId = $(this).data("id");
                    let nama = $(this).data("nama_task");
                    let actionUrl = '';
                    if (userRole == 'manager') actionUrl = '/manajer/task/delete/' + taskId;
                    else if (userRole == 'karyawan') actionUrl = '/karyawan/task/delete/' + taskId;
                    else if (userRole == 'admin-sdm') actionUrl = '/admin_sdm/task/delete/' + taskId;

                    Swal.fire({
                        title: "Konfirmasi Hapus Task!",
                        text: "Apakah Kamu yakin ingin menghapus task '" + nama + "' ?",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#cf0202",
                        cancelButtonColor: "#3085d6",
                        confirmButtonText: "Ya, Hapus!",
                        cancelButtonText: "Batal"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            let form = $("<form>", { action: actionUrl, method: "POST" })
                                .append($("<input>", { type: "hidden", name: "_token", value: "{{ csrf_token() }}" }))
                                .append($("<input>", { type: "hidden", name: "_method", value: "DELETE" }));
                            $("body").append(form);
                            form.submit();
                        }
                    })
                });

                $(document).on('click', '.btnViewDokumenPdf', function(e) {
                    e.preventDefault();
                    $('#staticBackdropViewDokumen').modal('show');
                    $('#staticBackdropViewDokumen .modal-title').text('Lampiran : ' + $(this).data('nama_task'));
                    let dokumen = $(this).data('dokumen');
                    let fileExtension = dokumen ? dokumen.split('.').pop().toUpperCase() : "";
                    $("#previewImage, #viewDokumenPdf").hide().attr("src", "");
                    $("#detail_upload").html("");
                    if (!dokumen || dokumen == "null" || dokumen.trim() == "") {
                        $("#detail_upload").html('<strong class="text-danger">Tidak ada lampiran tersedia</strong>');
                    } else if (dokumen.match(/\.(jpg|jpeg|png)$/i)) {
                        $("#previewImage").attr("src", dokumen).show();
                        $("#viewDokumenPdf").hide();
                        $("#detail_upload").html(`<strong>Preview Gambar:</strong> <a href="${dokumen}" target="_blank">Lihat Gambar</a>`);
                    } else if (dokumen.match(/\.pdf$/i)) {
                        $("#viewDokumenPdf").attr("src", dokumen).show();
                        $("#previewImage").hide();
                        $("#detail_upload").html(`<strong>Preview PDF:</strong> <a href="${dokumen}" target="_blank">Lihat PDF</a>`);
                    } else {
                        $("#previewImage, #viewDokumenPdf").hide();
                        $("#detail_upload").html(`
                            <strong>File Terpilih:</strong> ${fileExtension} <br>
                            <a href="${dokumen}" download class="btn btn-sm btn-success mt-2"><i class="bi bi-download"></i> Unduh File</a>
                        `);
                    }
                });

            }, 500);
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            if(document.getElementById('user')) {
                try {
                    new Choices('#user', {
                        removeItemButton: true,
                        searchEnabled: true,
                        noResultsText: "Tidak ada hasil yang cocok",
                        noChoicesText: "Tidak ada pilihan tersedia"
                    });
                } catch(e) { console.log('User select init error', e); }
            }
        });
    </script>
@endsection