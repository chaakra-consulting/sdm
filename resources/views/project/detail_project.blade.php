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
                                    <textarea class="form-control" name="keterangan" id="keterangan" cols="30" rows="5" required></textarea>
                                    <span class="text-xs text-danger">Jika tidak ada keterangan, maka harap isi dengan tanda (-)</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2" for="user">Anggota Task</label>
                                <div class="col-sm-10">
                                    <select name="user[]" id="user" multiple class="form-control">
                                        @foreach($user as $item)
                                            <option value="{{ $item->user_id }}">{{ $item->user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="row">
                                <label class="col-sm-2" for="upload">Lampiran</label>
                                <div class="col-sm-10">
                                    <input type="file" class="form-control" name="upload" id="upload">
                                    <img id="previewImage" src="" alt=""
                                        class="img-fluid mt-2 d-block mx-auto" style="max-width: 300px; display: none;">
                                    <iframe id="previewPDF" src="" width="100%" height="400px"
                                        style="display: none;"></iframe>
                                    <p class="text-center" id="detail_upload"></p>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="tgl_task" id="tgl_task">
                            <input type="hidden" name="project_perusahaan_id" id="project_perusahaan_id">
                            <input type="hidden" name="tipe_task" id="tipe_task" value="task-project">
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
    <div class="modal fade" id="staticBackdropAnggota" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="staticBackdropLabel">Tambah Anggota</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('manajer.update.anggota.project') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2" for="user">Anggota Project</label>
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
                            <input type="hidden" name="project_perusahaan_id" id="project_perusahaan_id" value="{{ $project->id }}">
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
                                            {{ $project->status_pengerjaan->nama_status_pengerjaan }}</span>
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
                                            <input type="text" name="nama_project" id="nama_project"
                                                class="form-control"
                                                value="{{ old('nama_project', $project == null ? '' : $project->nama_project) }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="status" class="form-label">Status</label>
                                            <select class="form-control" id="status" data-trigger name="status"
                                                required>
                                                <option value="">Pilih Status Project</option>
                                                @foreach ($statusPengerjaan as $item)
                                                    <option value="{{ $item->slug }}" {{ $project->status_pengerjaan?->slug == $item->slug ? 'selected' : '' }}>
                                                        {{ $item->nama_status_pengerjaan }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="waktu_mulai">Tanggal Mulai</label>
                                            <div class="input-group">
                                                <div class="input-group-text text-muted"><i class="ri-calendar-line"></i>
                                                </div>
                                                <input type="text" class="form-control" name="waktu_mulai"
                                                    value="{{ $project->waktu_mulai != null ? $project->waktu_mulai : '' }}"
                                                    id="humanfrienndlydate" placeholder="Waktu Mulai">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="waktu_berakhir">Tanggal Berakhir</label>
                                            <div class="input-group">
                                                <div class="input-group-text text-muted"><i class="ri-calendar-line"></i>
                                                </div>
                                                <input type="text" class="form-control" name="waktu_berakhir"
                                                    value="{{ $project->waktu_berakhir != null ? $project->waktu_berakhir : '' }}"
                                                    id="humanfrienndlydate" placeholder="Waktu Berakhir">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="deadline">Deadline</label>
                                            <div class="input-group">
                                                <div class="input-group-text text-muted"><i class="ri-calendar-line"></i>
                                                </div>
                                                <input type="text" class="form-control" name="deadline"
                                                    value="{{ $project->deadline != null ? $project->deadline : '' }}"
                                                    id="humanfrienndlydate" placeholder="deadline">
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
                <div class="mb-3">
                    @php
                        $userRole = auth()->user()->role->slug;
                    @endphp
                    @if ($userRole == 'manager')
                        <a href="/manajer/project" class="btn btn-secondary">Kembali</a>
                    @elseif ($userRole == 'karyawan')
                        <a href="/karyawan/project" class="btn btn-secondary">Kembali</a>
                    @endif
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
                            </ul>
                        </div>
                        <div class="tab-content border border-top-0 p-4 br-dark">
                            <div class="tab-pane border-0 p-0 active" id="home">
                                <div class="row">
                                    <div class="col-md-6" id="progres-bar"></div>
                                    <div class="col-md-6">
                                        <dl class="row mb-0">
                                            <dt class="col-md-4 p-0">Nama Entitas</dt>
                                            <dd class="col-md-8 p-0">: {{ $project->nama_project }}</dd>
                                            <dt class="col-md-4 p-0">Capaian Target</dt>
                                            <dd class="col-md-8 p-0">: - </dd>
                                            <dt class="col-md-4 p-0">Target Task</dt>
                                            <dd class="col-md-8 p-0">: {{ $tasks->count() ? $tasks->count() : 'Belum Ada ' }} Task</dd>
                                            <div class="form-group p-0 ">
                                                <label for="nama_project" class="form-label"><strong>Realisasi -
                                                    {{ $project->nama_project }}</strong></label>
                                                <div class="input-group mb-3">
                                                    <input type="text" class="form-control" placeholder="" aria-label="Example text with button addon" 
                                                    aria-describedby="button-addon1">
                                                    <button class="btn btn-success" type="button" id="button-addon1">Verifikasi</button>
                                                </div>
                                            </div>
                                        </dl>
                                    </div>
                                </div>
                                <div id='calendar2'></div>
                            </div>
                            <div class="tab-pane border-0 p-0" id="task">
                                <div class="main-content-body main-content-body-mail">
                                    @if (Auth::check() && Auth::user()->role->slug == 'manager')
                                        <div class="main-mail-header p-0">
                                            <div>
                                                <button type="button" class="btn btn-outline-primary tambahTask"
                                                    data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                                    <i class="ri-file-line"></i> Buat Task
                                                </button>
                                            </div>
                                        </div>
                                    @endif
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
                                                    <td width="45%">
                                                        <strong>{{ $task->nama_task }}</strong>
                                                    </td>
                                                    <td>{{ Carbon\Carbon::parse($task->tgl_task)->translatedFormat('l, d F Y') }}</td>
                                                    <td class="text-center">
                                                        @if (Auth::check() && Auth::user()->role->slug == 'manager')
                                                        <a href="javascript:void(0);" class="btn btn-warning updateTask"
                                                            data-id="{{ $task->id }}"
                                                            data-nama_task="{{ $task->nama_task }}"
                                                            data-tgl_task="{{ $task->tgl_task }}"
                                                            data-keterangan="{{ $task->keterangan }}"
                                                            data-project="{{ $task->project_perusahaan_id }}"
                                                            data-user="{{ Auth::user()->id }}"
                                                            data-users="{{ json_encode($task->users_task->pluck('id')->toArray()) }}" 
                                                            data-upload="{{ $task->upload }}" data-bs-toggle="modal"
                                                            data-bs-target="#staticBackdrop">
                                                            <i data-bs-toggle="tooltip"
                                                                data-bs-custom-class="tooltip-secondary"
                                                                data-bs-placement="top" title="Update Task!"
                                                                class="bi bi-pencil-square"></i>
                                                        </a>
                                                        <a href="{{ route('manajer.detail.task', $task->id) }}" class="btn btn-secondary"
                                                            data-bs-toggle="tooltip"
                                                            data-bs-custom-class="tooltip-secondary"
                                                            data-bs-placement="top" title="Detail Task!"><i
                                                                class='bx bx-detail'></i>
                                                        </a>
                                                        <form action="{{ route('manajer.delete.task', $task->id) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger delete-task"
                                                                data-id="{{ $task->id }}"
                                                                data-nama_task="{{ $task->nama_task }}"
                                                                data-bs-toggle="tooltip"
                                                                data-bs-custom-class="tooltip-danger"
                                                                data-bs-placement="top" title="Hapus Task!">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                        @elseif (Auth::check() && Auth::user()->role->slug = 'karyawan')
                                                            <a href="{{ route('karyawan.detail.task', $task->id) }}" class="btn btn-secondary"
                                                                data-bs-toggle="tooltip"
                                                                data-bs-custom-class="tooltip-secondary"
                                                                data-bs-placement="top" title="Detail Task!"><i
                                                                    class='bx bx-detail'></i>
                                                            </a>
                                                        @endif
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
                                    @foreach ($user as $item)
											<div class="col-sm-12 col-md-6 col-lg-6 col-xl-6 col-xxl-4">
												<div class="card custom-card border shadow-none">
													<div class="card-body  user-lock text-center">
                                                        <div class="d-flex justify-content-end">
                                                            @if (Auth::check() && Auth::user()->role->slug == 'manager')
                                                                <form action="{{ route('manajer.delete.anggota.project', $item->id) }}"
                                                                    method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-sm btn-icon btn-outline-danger rounded-circle border-0"
                                                                        data-id="{{ $item->id }}"
                                                                        data-nama="{{ $item->user->name }}"
                                                                        data-bs-toggle="tooltip"
                                                                        data-bs-custom-class="tooltip-danger"
                                                                        data-bs-placement="top" title="Hapus Anggota Project!">
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
                                    @if (Auth::check() && Auth::user()->role->slug == 'manager')    
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
            $(".tambahTask").click(function() {
                $(".modal-title").text("Buat Task");
                $("#formTask").attr("action", "/manajer/task/store");
                $("#formTask input[name='_method']").remove();
                $("#formTask").append('<input type="hidden" name="_method" value="POST">');

                $("#nama_task, #keterangan, #tgl_task, #task_id").val('');
                $("#user_id").val('{{ auth()->user()->id }}');
                $("#tipe_task").val('task-project');

                $("#previewImage, #previewPDF").hide().attr("src", "");
                $("#detail_upload").html("");

                $("#btnSubmit").text("Simpan").show();
                $("#upload").prop("disabled", false);
            });
            $("#upload").change(function() {
                let file = this.files[0];
                if (file) {
                    let fileUrl = URL.createObjectURL(file);
                    let fileExtension = file.name.split('.').pop().toUpperCase();

                    $("#previewImage, #previewPDF").hide().attr("src", "");
                    $("#detail_upload").html("");

                    if (file.name.match(/\.(jpg|jpeg|png)$/i)) {
                        $("#previewImage").attr("src", fileUrl).show();
                        $("#previewPDF").hide();
                        $("#detail_upload").html(
                            `<strong>Preview Gambar:</strong> <a href="${fileUrl}" target="_blank">Lihat Gambar</a>`
                        );
                    } else if (file.name.match(/\.pdf$/i)) {
                        $("#previewPDF").attr("src", fileUrl).show();
                        $("#previewImage").hide();
                        $("#detail_upload").html(
                            `<strong>Preview PDF:</strong> <a href="${fileUrl}" target="_blank">Lihat PDF</a>`
                        );
                    } else {
                        $("#previewImage, #previewPDF").hide();
                        $("#detail_upload").html(`<strong>File Terpilih:</strong> ${fileExtension}`);
                    }
                }
            });
            $(".updateTask").click(function(e) {
                e.preventDefault();

                let id = $(this).data("id");
                let nama = $(this).data("nama_task");
                let tgl = $(this).data("tgl_task");
                let keterangan = $(this).data("keterangan");
                let project = $(this).data("project");
                let user = $(this).data("user");
                let upload = $(this).data("upload");

                $(".modal-title").text("Update Task");

                $("#formTask").attr("action", "/manajer/task/update/" + id);
                $("#formTask input[name='_method']").remove();
                $("#formTask").append('<input type="hidden" name="_method" value="PUT">');

                $("#nama_task").val(nama);
                $("#keterangan").val(keterangan);
                $("#tgl_task").val(tgl);
                $("#task_id").val(id);
                $("#project_perusahaan_id").val(project);
                $("#user_id").val(user);
                $(".form-group:has(#user)").hide();

                $("#previewImage, #previewPDF").hide().attr("src", "");
                $("#detail_upload").html("");

                if (upload) {
                    let fileUrl = "/uploads/" + upload;
                    let fileExtension = upload.split('.').pop().toUpperCase();

                    if (upload.match(/\.(jpg|jpeg|png)$/i)) {
                        $("#previewImage").attr("src", fileUrl).show();
                        $("#detail_upload").html(
                            `<strong>Preview Gambar:</strong> <a href="${fileUrl}" target="_blank">Lihat Gambar</a>`
                        );
                    } else if (upload.match(/\.pdf$/i)) {
                        $("#previewPDF").attr("src", fileUrl).show();
                        $("#detail_upload").html(
                            `<strong>Preview PDF:</strong> <a href="${fileUrl}" target="_blank">Lihat PDF</a>`
                        );
                    } else {
                        $("#detail_upload").html(`<a href="${fileUrl}" target="_blank"><strong>Download File</strong> 
                            <span class="text-warning">(File Berupa ${fileExtension})</span></a>`);
                    }
                } else {
                    $("#detail_upload").text("Tidak ada file diunggah");
                }

                $("#upload").prop("disabled", false);
                $("#btnSubmit").text("Update").show();
            });
            $("#staticBackdrop").on("hidden.bs.modal", function () {
                $(".form-group:has(#user)").show();
            });
            $(".delete-task").click(function(e) {
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
                            action: "/manajer/task/delete/" + taskId,
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
            $(".delete-anggota-project").click(function(e) {
                e.preventDefault();

                let id = $(this).data("id");
                let nama = $(this).data("nama");

                Swal.fire({
                    title: "Konfirmasi Hapus Anggota Project!",
                    text: "Apakah Kamu yakin ingin menghapus Anggota Project '" + nama + "' ?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#cf0202",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Ya, Hapus!",
                    cancelButtonText: "Batal"
                }).then((result) => {
                    if (result.isConfirmed) {
                        let form = $("<form>", {
                            action: "/manajer/project/delete/anggota/" + id,
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
                dateClick: function(info) {
                    let selectedDate = info.dateStr;
                    let filteredEvents = calendar.getEvents().filter(event =>
                        event.startStr.startsWith(selectedDate)
                    );

                    let modalTitle = document.getElementById('eventTitle');
                    let modalBody = document.getElementById('eventBody');

                    if (filteredEvents.length > 0) {
                        modalTitle.innerText = "Events pada tanggal " + selectedDate;
                        modalBody.innerHTML = filteredEvents.map(event => `<li>${event.title}</li>`).join('');
                    } else {
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
    <script>
        var options = {
            series: [50],
            chart: {
                type: 'radialBar',
                height: 320,
                offsetY: -20,
                sparkline: {
                    enabled: true
                }
            },
            plotOptions: {
                radialBar: {
                    startAngle: -90,
                    endAngle: 90,
                    track: {
                        background: "#fff",
                        strokeWidth: '97%',
                        margin: 5, // margin is in pixels
                        dropShadow: {
                            enabled: false,
                            top: 2,
                            left: 0,
                            color: '#999',
                            opacity: 1,
                            blur: 2
                        }
                    },
                    dataLabels: {
                        name: {
                            show: false
                        },
                        value: {
                            offsetY: -2,
                            fontSize: '22px'
                        }
                    }
                }
            },
            colors: ["#0162e8"],
            grid: {
                padding: {
                    top: -10
                }
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'light',
                    shadeIntensity: 0.4,
                    inverseColors: false,
                    opacityFrom: 1,
                    opacityTo: 1,
                    stops: [0, 50, 53, 91]
                },
            },
            labels: ['Average Results'],
        };
        var chart = new ApexCharts(document.querySelector("#progres-bar"), options);
        chart.render();
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll("#user, #user2").forEach(function (element) {
                new Choices(element, {
                    removeItemButton: true,
                    searchEnabled: true,
                    noResultsText: "Tidak ada hasil yang cocok",
                    noChoicesText: "Tidak ada pilihan tersedia"
                });
            });
        });
    </script>
@endsection
