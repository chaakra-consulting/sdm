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
                <form action="{{ route('manajer.update.anggota.task') }}" method="POST" enctype="multipart/form-data">
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
                                            {{ $task->nama_task }}</h5>
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
                                    <form action="{{ route('manajer.update.task', $task->id) }}" method="post" enctype="multipart/form-data">
                                        @csrf
                                        @method('put')
                                        <div class="form-group">
                                            <label for="tipe_task">Tipe Task</label>
                                            <select name="tipe_task" id="tipe_task" data-trigger class="form-control" required>
                                                @if ($task->tipe_task == null)
                                                    <option value="" selected disabled>Pilih Tipe Task</option>
                                                    @foreach ($tipeTask as $item)
                                                        <option value="{{ $item->id }}">{{ $item->nama_tipe }}</option>
                                                    @endforeach
                                                @else
                                                    <option value="{{ $task->tipe_task->id }}" selected>{{ $task->tipe_task->nama_tipe }}</option>
                                                    @foreach ($tipeTask as $item)
                                                        <option value="{{ $item->id }}">{{ $item->nama_tipe }}</option>
                                                    @endforeach         
                                                @endif
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="nama_project" class="form-label">Nama Project</label>
                                            @if ($task->project_perusahaan == null)
                                                <select name="nama_project" id="nama_project" data-trigger class="form-control" required>
                                                    <option value="" selected disabled>Pilih Project</option>
                                                    @foreach ($project as $item)
                                                        <option value="{{ $item->id }}">{{ $item->nama_project }} ({{ $item->perusahaan->nama_perusahaan }})</option>
                                                    @endforeach
                                                </select>
                                            @else
                                                <select name="nama_project" id="nama_project" data-trigger class="form-control" required>
                                                    <option value="{{ $task->project_perusahaan->id }}" selected>{{ $task->project_perusahaan->nama_project }} ({{ $task->project_perusahaan->perusahaan->nama_perusahaan }})</option>
                                                    @foreach ($project as $item)
                                                        <option value="{{ $item->id }}">{{ $item->nama_project }} ({{ $item->perusahaan->nama_perusahaan }})</option>
                                                    @endforeach
                                                </select>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label for="nama_task" class="form-label">Nama Task</label>
                                            <input type="text" name="nama_task" id="nama_task" class="form-control"
                                                value="{{ $task->nama_task }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="keterangan">Keterangan</label>
                                            <textarea class="form-control" name="keterangan" id="keterangan" cols="10" rows="5">{{ old('keterangan', $task->keterangan) }}</textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="upload">Lampiran</label>
                                            <input type="file" class="form-control" name="upload" id="upload">
                                            @php
                                                $file = $task->upload ?? null;
                                                $isImage = $file && in_array(pathinfo($file, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png']);
                                                $isPDF = $file && pathinfo($file, PATHINFO_EXTENSION) === 'pdf';
                                            @endphp
                                            @if($isImage)
                                                <img id="previewImage" src="{{ asset('uploads/' . $file) }}" alt="Lampiran Gambar"
                                                    class="img-fluid mt-2 d-block mx-auto" style="max-width: 300px;">
                                            @elseif($isPDF)
                                                <iframe id="previewPDF" src="{{ asset('uploads/' . $file) }}" width="100%" height="400px"></iframe>
                                            @else
                                                <p class="text-center text-muted">Tidak ada file lampiran yang tersedia</p>
                                            @endif
                                            <p class="text-center mt-2" id="detail_upload">{{ $file }}</p>
                                        </div>   
                                        <input type="hidden" name="user_id" id="user_id" value="{{ auth()->user()->id }}">                                    
                                        @if ($task != null)
                                            <button type="button" class="btn btn-danger btn-batal-edit"
                                                hidden>Batal</button>
                                            <button type="button" class="btn btn-warning btn-edit-task">Edit</button>
                                        @endif
                                        <button type="submit" class="btn btn-primary btn-submit-task"
                                            {{ $task != null ? 'hidden' : '' }}>{{ $task != null ? 'Update' : 'Simpan' }}
                                        </button>
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
                                <div class="table-responsive">
                                    <table id="datatable-basic" class="table table-bordered w-100">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Tanggal</th>
                                                <th>Nama Anggota</th>
                                                <th>Sub Task</th>
                                                <th>Durasi</th>
                                                <th>Keterangan</th>
                                                <th>Lampiran</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane border-0 p-0" id="anggota" role="tabpanel">
                                <div class="row">
                                    @foreach ($user as $item)
											<div class="col-sm-12 col-md-6 col-lg-6 col-xl-6 col-xxl-4">
												<div class="card custom-card border shadow-none">
													<div class="card-body  user-lock text-center">
                                                        <div class="d-flex justify-content-end">
                                                            <form action="{{ route('manajer.delete.anggota.task', $item->id) }}"
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
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mb-3">
            <a href="/manajer/task" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function () {
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
        });
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
            document.getElementById('upload').addEventListener('change', function (e) {
                const file = e.target.files[0];
                const imagePreview = document.getElementById('previewImage');
                const pdfPreview = document.getElementById('previewPDF');
                const detailUpload = document.getElementById('detail_upload');

                if (file) {
                    const fileURL = URL.createObjectURL(file);
                    const fileName = file.name;
                    const fileExt = fileName.split('.').pop().toLowerCase();

                    imagePreview.style.display = 'none';
                    pdfPreview.style.display = 'none';

                    if (['jpg', 'jpeg', 'png'].includes(fileExt)) {
                        imagePreview.src = fileURL;
                        imagePreview.style.display = 'block';
                    } else if (fileExt === 'pdf') {
                        pdfPreview.src = fileURL;
                        pdfPreview.style.display = 'block';
                    }

                    detailUpload.textContent = fileName;
                }
            });

        });
    </script>
@endsection
