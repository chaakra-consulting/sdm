@extends('layouts.main')

@section('content')
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="staticBackdropLabel"></h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="POST" id="formTask" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="row align-items-center">
                                <label class="col-sm-2" for="nama_task">Nama Task</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="nama_task" id="nama_task" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2" for="keterangan">Keterangan</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" name="keterangan" id="keterangan" cols="30" rows="5"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="row">
                                <label class="col-sm-2" for="upload">Upload</label>
                                <div class="col-sm-10">
                                    <input type="file" class="form-control" name="upload" id="upload">
                                    <img id="previewImage" src="" alt="" class="img-fluid mt-2 d-block mx-auto" style="max-width: 300px; display: none;">
                                    <iframe id="previewPDF" src="" width="100%" height="400px" style="display: none;"></iframe>
                                    <p class="text-center" id="detail_upload"></p>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="tgl_task" id="tgl_task">
                            <input type="hidden" name="project_perusahaan_id" id="project_perusahaan_id">
                            <input type="hidden" name="user_id" id="user_id">
                            <input type="hidden" name="task_id" id="task_id">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kembali</button>
                        <button type="submit" class="btn btn-primary" id="btnSubmit"></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row row-sm">
            <div class="col-xl-4 col-lg-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="ps-0">
                            <div class="main-profile-overview">
                                <div class="d-flex justify-content-between mb-4">
                                    <div>
                                        <h5 class="main-profile-name" style="text-transform: capitalize;">
                                            {{ $project->nama_project }}</h5>
                                        <p class="main-profile-name-text text-muted fs-16 text-uppercase">
                                            @if ($project->status == 'selesai')
                                                <span class="text-success">
                                                @elseif ($project->status == 'proses')
                                                    <span class="text-warning">
                                                    @elseif ($project->status == 'belum')
                                                        <span class="text-danger">
                                            @endif
                                            {{ $project->status }}</span>
                                        </p>
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
                                <div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="exampleModalScrollable2" 
                                    data-bs-keyboard="false" aria-hidden="true">
                                    <!-- Scrollable modal -->
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
                                                {{-- <button type="button" class="btn btn-primary">Save
                                                    Changes</button> --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="container-project" {{ $project->waktu_mulai != null ? '' : 'hidden' }}>
                                    <form action="{{ route('manajer.update.project', $project->id) }}" method="post">
                                        @csrf
                                        @method('put')
                                        <div class="form-group">
                                            <label for="perusahaan_id" class="form-label">Nama Instansi</label>
                                            <select name="perusahaan_id" data-trigger id="perusahaan_id"
                                                class="form-control">
                                                <option selected disabled>Pilih Perusahaan</option>
                                                @foreach ($perusahaan as $key => $row)
                                                    <option
                                                        {{ old('perusahaan_id', $project == null ? '' : $project->perusahaan_id) == $row->id ? 'selected' : '' }}
                                                        value="{{ $row->id }}">
                                                        {{ $row->nama_perusahaan }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="nama_project" class="form-label">Nama Project</label>
                                            <input type="text" name="nama_project" id="nama_project" class="form-control"
                                                value="{{ old('nama_project', $project == null ? '' : $project->nama_project) }}">
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-sm-6">
                                                <label for="skala_project" class="form-label">Skala</label>
                                                <select class="form-control" id="skala_project" data-trigger name="skala_project"
                                                    required>
                                                    <option value="">Pilih Skala Project</option>
                                                    <option value="kecil"
                                                        {{ $project->skala_project == 'kecil' ? 'selected' : '' }}>
                                                        Kecil
                                                    </option>
                                                    <option value="sedang"
                                                        {{ $project->skala_project == 'sedang' ? 'selected' : '' }}>
                                                        Sedang
                                                    </option>
                                                    <option value="besar"
                                                        {{ $project->skala_project == 'besar' ? 'selected' : '' }}>
                                                        Besar
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <label for="status" class="form-label">Status</label>
                                                <select class="form-control" id="status" data-trigger name="status"
                                                    required>
                                                    <option value="">Pilih Skala Project</option>
                                                    <option value="belum"
                                                        {{ $project->status == 'belum' ? 'selected' : '' }}>
                                                        Belum
                                                    </option>
                                                    <option value="proses"
                                                        {{ $project->status == 'proses' ? 'selected' : '' }}>
                                                        Proses
                                                    </option>
                                                    <option value="selesai"
                                                        {{ $project->status == 'selesai' ? 'selected' : '' }}>
                                                        Selesai
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="waktu_mulai">Tanggal Mulai</label>
                                            <div class="input-group">
                                                <div class="input-group-text text-muted"><i class="ri-calendar-line"></i></div>
                                                <input type="text" class="form-control" name="waktu_mulai"
                                                    value="{{ $project->waktu_mulai != null ? $project->waktu_mulai : '' }}"
                                                    id="humanfrienndlydate" placeholder="Waktu Mulai">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="waktu_berakhir">Tanggal Berakhir</label>
                                            <div class="input-group">
                                                <div class="input-group-text text-muted"><i class="ri-calendar-line"></i></div>
                                                <input type="text" class="form-control" name="waktu_berakhir"
                                                    value="{{ $project->waktu_berakhir != null ? $project->waktu_berakhir : '' }}"
                                                    id="humanfrienndlydate" placeholder="Waktu Berakhir">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="deadline">Deadline</label>
                                            <div class="input-group">
                                                <div class="input-group-text text-muted"><i class="ri-calendar-line"></i></div>
                                                <input type="text" class="form-control" name="deadline"
                                                    value="{{ $project->deadline != null ? $project->deadline : '' }}"
                                                    id="humanfrienndlydate" placeholder="deadline">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="progres" class="form-label">Progres</label>
                                            <div class="progress progress-sm" style="height: 35px">
                                                <div class="progress-bar bg-info-gradient"
                                                    id="bootstrap-progress-bar" role="progressbar"
                                                    style="width:{{ $project->progres ?? 0 }}%;"
                                                    aria-valuemin="0" aria-valuemax="100">
                                                    <strong>
                                                        {{ $project->progres == null ? 0 : $project->progres }}%
                                                    </strong>
                                                </div>
                                            </div>
                                        </div>
                                        @if ($project != null)
                                            <button type="button" class="btn btn-danger btn-batal-edit"
                                                hidden>Batal</button>
                                            <button type="button" class="btn btn-warning btn-edit-project">Edit</button>
                                        @endif
                                        <button type="submit" class="btn btn-primary btn-submit-project"
                                            {{ $project != null ? 'hidden' : '' }}>{{ $project != null ? 'Update' : 'Simpan' }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-1">
                    <a href="/manajer/project" class="btn btn-secondary">Kembali</a>
                </div>
            </div>
            <div class="col-xl-8 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="tabs-menu ">
                            <!-- Tabs -->
                            <ul class="nav nav-tabs profile navtab-custom panel-tabs">
                                <li class="">
                                    <a href="#home" data-bs-toggle="tab" class="active" aria-expanded="true">
                                        <i class="bi bi-calendar-check"></i>
                                        <span class="hidden-xs">TIMELINE</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a href="#task" data-bs-toggle="tab" aria-expanded="false"> 
                                        <span class="visible-xs"><i class="ri-edit-line"></i></span>
                                        <span class="hidden-xs">TASK</span> </a>
                                </li>
                                <li class="">
                                    <a href="#anggota" data-bs-toggle="tab" aria-expanded="false">
                                        <span class="visible-xs"><i class="las la-user-circle fs-16 me-1"></i></span>
                                        <span class="visible-xs"></span>
                                        <span class="hidden-xs">ANGGOTA</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a href="#settings" data-bs-toggle="tab" aria-expanded="false"> <span
                                            class="visible-xs"><i class="las la-cog fs-16 me-1"></i></span>
                                        <span class="hidden-xs">SETTINGS</span> </a>
                                </li>
                            </ul>
                        </div>
                        <div class="tab-content border border-top-0 p-4 br-dark">
                            <div class="tab-pane border-0 p-0 active" id="home">
                                <div id='calendar2'></div>
                            </div>
                            <div class="tab-pane border-0 p-0" id="task">  
                                <div class="main-content-body main-content-body-mail">
                                    <div class="main-mail-header p-0">
                                        <div>
                                            <button type="button" class="btn btn-outline-primary tambahTask" data-bs-toggle="modal"
                                                data-bs-target="#staticBackdrop">
                                                <i class="ri-file-line"></i> Buat Task
                                            </button>
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table id="datatable-basic" class="table table-bordered text-nowrap w-100">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Task</th>
                                                    <th>Tanggal</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            @foreach ($tasks as $task)
                                                <tbody>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td width="50%">
                                                        <strong>{{ $task->nama_task }}</strong>
                                                    </td>
                                                    <td>{{ $task->tgl_task }}</td>
                                                    <td>
                                                        <a href="#" 
                                                            class="btn btn-secondary detailTask" data-id="{{ $task->id }}" data-nama_task="{{ $task->nama_task }}" 
                                                            data-tgl_task="{{ $task->tgl_task }}" data-keterangan="{{ $task->keterangan }}" 
                                                            data-project="{{ $task->project_perusahaan_id }}" data-user="{{ Auth::user()->id }}" 
                                                            data-upload="{{ $task->upload }}" data-bs-toggle="modal"  data-bs-target="#staticBackdrop">
                                                            <i data-bs-toggle="tooltip" data-bs-custom-class="tooltip-secondary" 
                                                                data-bs-placement="top" title="Detail Task!" class='bx bx-detail'></i>
                                                        </a>
                                                        <form action="{{ route('manajer.delete.project', $task->id) }}" method="POST"
                                                            class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger delete-task"
                                                                data-id="{{ $task->id }}" data-nama_task="{{ $task->nama_task }}"  data-bs-toggle="tooltip" 
                                                                data-bs-custom-class="tooltip-danger" data-bs-placement="top" title="Hapus Task!">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tbody>
                                            @endforeach
                                        </table>
                                    </div>
                                    <div class="mg-lg-b-30"></div>
                                </div>
                            </div>
                            <div class="tab-pane border-0 p-0" id="anggota" role="tabpanel">
                                <div class="row">
                                    @foreach ($users as $user)
                                        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6 col-xxl-4">
                                            <div class="card custom-card border shadow-none">
                                                <div class="card-body  user-lock text-center">
                                                    <div class="dropdown float-end">
                                                        <a href="javascript:void(0);" class="option-dots" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true"> <i class="fe fe-more-vertical"></i> </a>
                                                        <div class="dropdown-menu shadow"><a class="dropdown-item" href="javascript:void(0);"><i class="fe fe-eye me-2"></i> View</a> 
                                                            <a class="dropdown-item" href="javascript:void(0);"><i class="fe fe-trash-2 me-2"></i> Delete</a>
                                                        </div>
                                                        
                                                    </div>
                                                    <a href="profile.html">
                                                        <img alt="avatar" class="rounded-circle" src="../assets/images/faces/1.jpg">
                                                        <h5 class="fs-16 mb-0 mt-3 text-dark fw-semibold">{{ $user->user->name }}</h5>
                                                        {{-- <span class="text-muted">{{ $user->user->dataDiri->kepegawaian->subJabatan->nama_sub_jabatan }}</span> --}}
                                                        <div class="mt-3 d-flex mx-auto text-center justify-content-center">
                                                            <span class="btn btn-icon btn-outline-primary rounded-circle border me-3">
                                                                {{-- <a href="{{ $user->user->dataDiri }}"><i class="bx bxl-facebook fs-16 align-middle"></i></a> --}}
                                                            </span>
                                                            <span class="btn btn-icon btn-outline-primary rounded-circle border me-3">
                                                                <i class="ri-twitter-x-fill fs-16 align-middle"></i>
                                                            </span>
                                                            <span class="btn btn-icon btn-outline-primary rounded-circle border">
                                                                <i class="bx bxl-linkedin fs-16 align-middle"></i>
                                                            </span>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="tab-pane border-0 p-0" id="settings">
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
        $(document).ready(function() {
            $(".update-project").click(function() {
                $(".container-peringatan").slideUp(200);
                $(".container-project").prop('hidden', false).slideDown(200);
            })
            $('.btn-edit-project').click(function() {
                $('.btn-edit-project').hide();
                $('.btn-batal-edit').prop('hidden', false);
                $(".btn-submit-project").prop('hidden', false);

                $('.btn-batal-edit').click(function() {
                    $('.btn-edit-project').fadeIn(200);
                    $('.btn-batal-edit').prop('hidden', true);
                    $(".btn-submit-project").prop('hidden', true);
                })
            })
            $(".tambahTask").click(function () {
                $(".modal-title").text("Buat Task");
                $("#formTask").attr("action", "/manajer/project/task/store");
                $("#formTask input[name='_method']").remove();
                $("#formTask").append('<input type="hidden" name="_method" value="POST">');

                $("#nama_task, #keterangan, #tgl_task, #task_id").val('');
                $("#project_perusahaan_id").val('{{ $project->id }}');
                $("#user_id").val('{{ auth()->user()->id }}');

                $("#previewImage, #previewPDF").hide().attr("src", "");
                $("#detail_upload").html("");

                $("#btnSubmit").text("Simpan").show();
                $("#upload").prop("disabled", false);
            });
            $("#upload").change(function () {
                let file = this.files[0];
                if (file) {
                    let fileUrl = URL.createObjectURL(file);
                    let fileExtension = file.name.split('.').pop().toUpperCase();

                    $("#previewImage, #previewPDF").hide().attr("src", "");
                    $("#detail_upload").html("");

                    if (file.name.match(/\.(jpg|jpeg|png|gif)$/i)) {
                        $("#previewImage").attr("src", fileUrl).show();
                        $("#previewPDF").hide();
                        $("#detail_upload").html(`<strong>Preview Gambar:</strong> <a href="${fileUrl}" target="_blank">Lihat Gambar</a>`);
                    } 
                    else if (file.name.match(/\.pdf$/i)) {
                        $("#previewPDF").attr("src", fileUrl).show();
                        $("#previewImage").hide();
                        $("#detail_upload").html(`<strong>Preview PDF:</strong> <a href="${fileUrl}" target="_blank">Lihat PDF</a>`);
                    } 
                    else {
                        $("#previewImage, #previewPDF").hide();
                        $("#detail_upload").html(`<strong>File Terpilih:</strong> ${fileExtension}`);
                    }
                }
            });
            $(".detailTask").click(function (e) {
                e.preventDefault();
                
                let id = $(this).data("id");
                let nama = $(this).data("nama_task");
                let tgl = $(this).data("tgl_task");
                let keterangan = $(this).data("keterangan");
                let project = $(this).data("project");
                let user = $(this).data("user");
                let upload = $(this).data("upload");

                $(".modal-title").text("Detail Task");
                
                $("#formTask").attr("action", "/manajer/project/task/update/" + id);
                $("#formTask input[name='_method']").remove();
                $("#formTask").append('<input type="hidden" name="_method" value="PUT">');
                
                $("#nama_task").val(nama);
                $("#keterangan").val(keterangan);
                $("#tgl_task").val(tgl);
                $("#task_id").val(id);
                $("#project_perusahaan_id").val(project);
                $("#user_id").val(user);
                
                $("#previewImage, #previewPDF").hide().attr("src", "");
                $("#detail_upload").html("");
                
                if (upload) {
                    let fileUrl = "/uploads/" + upload;
                    let fileExtension = upload.split('.').pop().toUpperCase();

                    if (upload.match(/\.(jpg|jpeg|png|gif)$/i)) {
                        $("#previewImage").attr("src", fileUrl).show();
                        $("#detail_upload").html(`<strong>Preview Gambar:</strong> <a href="${fileUrl}" target="_blank">Lihat Gambar</a>`);
                    } 
                    else if (upload.match(/\.pdf$/i)) {
                        $("#previewPDF").attr("src", fileUrl).show();
                        $("#detail_upload").html(`<strong>Preview PDF:</strong> <a href="${fileUrl}" target="_blank">Lihat PDF</a>`);
                    } 
                    else {
                        $("#detail_upload").html(`<a href="${fileUrl}" target="_blank"><strong>Download File</strong> 
                            <span class="text-warning">(File Berupa ${fileExtension})</span></a>`);
                    }
                } else {
                    $("#detail_upload").text("Tidak ada file diunggah");
                }

                $("#upload").prop("disabled", false);
                $("#btnSubmit").text("Update").show();
            });
            $(".delete-task").click(function (e){
                e.preventDefault();

                let taskId = $(this).data("id");
                let nama = $(this).data("nama_task");

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
                        let form = $("<form>", {
                            action: "/manajer/project/task/delete/" + taskId,
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
                })
            })
        });
    </script>
    <script>
        let calendarEl = document.getElementById('calendar2');

        if (calendarEl) {
            let calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: @json($events),
                dateClick: function(info){
                    let selectedDate = info.dateStr;
                    let filteredEvents = calendar.getEvents().filter(event =>
                        event.startStr.startsWith(selectedDate)
                    );

                    let modalTitle = document.getElementById('eventTitle');
                    let modalBody = document.getElementById('eventBody');

                    if (filteredEvents.length > 0) {
                        modalTitle.innerText = "Events pada tanggal " + selectedDate;
                        modalBody.innerHTML = filteredEvents.map(event => `<li>${event.title}</li>`).join('');
                    }else {
                        modalTitle.innerText = `Tidak ada event pada ${selectedDate}`;
                        modalBody.innerHTML = '<li class="text-muted">Tidak ada event.</li>';
                    }
                    let eventModal = new bootstrap.Modal(document.getElementById('infoModal'));
                    eventModal.show();
                }
            });
            calendar.render();
        }
    </script>
@endsection
