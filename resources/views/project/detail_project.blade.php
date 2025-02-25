@extends('layouts.main')

@section('content')
    {{-- Modal View Dokumen PDF --}}
    <div class="modal fade" id="staticBackdropViewDokumenPdf" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
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
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kembali</button>
                </div>
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
                                <div class="container-project" {{ $project->waktu_mulai != null ? '' : 'hidden' }}>
                                    <form action="{{ route('manajer.update.project', $project->id) }}" method="post">
                                        @csrf
                                        @method('put')
                                        <div class="form-group">
                                            <label for="perusahaan_id" class="form-label">Nama Perusahaan</label>
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
                                            <div class="form-group col-md-6">
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
                                            <div class="form-group col-md-6">
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
                                    <a href="#gallery" data-bs-toggle="tab" aria-expanded="false"> <span
                                            class="visible-xs"><i class="las la-images fs-15 me-1"></i></span>
                                        <span class="hidden-xs">GALLERY</span> </a>
                                </li>
                                <li class="">
                                    <a href="#friends01" data-bs-toggle="tab" aria-expanded="false">
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
                            <div class="tab-pane border-0 p-0" id="gallery">
                            </div>
                            <div class="tab-pane border-0 p-0" id="friends01" role="tabpanel">
                                <div class="row">
                                    <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6 col-xxl-4">
                                        <div class="card custom-card border shadow-none">
                                            <div class="card-body  user-lock text-center">
                                                <div class="dropdown float-end">
                                                    <a href="javascript:void(0);" class="option-dots"
                                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="true"> <i class="fe fe-more-vertical"></i> </a>
                                                    <div class="dropdown-menu shadow"> <a class="dropdown-item"
                                                            href="javascript:void(0);"><i
                                                                class="fe fe-message-square me-2"></i>
                                                            Message</a> <a class="dropdown-item"
                                                            href="javascript:void(0);"><i class="fe fe-edit-2 me-2"></i>
                                                            Edit</a> <a class="dropdown-item"
                                                            href="javascript:void(0);"><i class="fe fe-eye me-2"></i>
                                                            View</a> <a class="dropdown-item"
                                                            href="javascript:void(0);"><i class="fe fe-trash-2 me-2"></i>
                                                            Delete</a>
                                                    </div>
                                                </div>
                                                <a href="profile.html">
                                                    <img alt="avatar" class="rounded-circle"
                                                        src="../assets/images/faces/1.jpg">
                                                    <h5 class="fs-16 mb-0 mt-3 text-dark fw-semibold">James Thomas</h5>
                                                    <span class="text-muted">Web designer</span>
                                                    <div class="mt-3 d-flex mx-auto text-center justify-content-center">
                                                        <span
                                                            class="btn btn-icon btn-outline-primary rounded-circle border me-3">
                                                            <i class="bx bxl-facebook fs-16 align-middle"></i>
                                                        </span>
                                                        <span
                                                            class="btn btn-icon btn-outline-primary rounded-circle border me-3">
                                                            <i class="ri-twitter-x-fill fs-16 align-middle"></i>
                                                        </span>
                                                        <span
                                                            class="btn btn-icon btn-outline-primary rounded-circle border">
                                                            <i class="bx bxl-linkedin fs-16 align-middle"></i>
                                                        </span>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6 col-xxl-4">
                                        <div class="card custom-card border shadow-none">
                                            <div class="card-body  user-lock text-center">
                                                <div class="dropdown float-end">
                                                    <a href="javascript:void(0);" class="option-dots"
                                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="true"> <i class="fe fe-more-vertical"></i> </a>
                                                    <div class="dropdown-menu shadow"> <a class="dropdown-item"
                                                            href="javascript:void(0);"><i
                                                                class="fe fe-message-square me-2"></i>
                                                            Message</a> <a class="dropdown-item"
                                                            href="javascript:void(0);"><i class="fe fe-edit-2 me-2"></i>
                                                            Edit</a> <a class="dropdown-item"
                                                            href="javascript:void(0);"><i class="fe fe-eye me-2"></i>
                                                            View</a> <a class="dropdown-item"
                                                            href="javascript:void(0);"><i class="fe fe-trash-2 me-2"></i>
                                                            Delete</a>
                                                    </div>
                                                </div>
                                                <a href="profile.html">
                                                    <img alt="avatar" class="rounded-circle"
                                                        src="../assets/images/faces/3.jpg">
                                                    <h5 class="fs-16 mb-0 mt-3 text-dark fw-semibold">Reynante
                                                        Labares</h5>
                                                    <span class="text-muted">Web designer</span>
                                                    <div class="mt-3 d-flex mx-auto text-center justify-content-center">
                                                        <span
                                                            class="btn btn-icon btn-outline-primary rounded-circle border me-3">
                                                            <i class="bx bxl-facebook fs-16 align-middle"></i>
                                                        </span>
                                                        <span
                                                            class="btn btn-icon btn-outline-primary rounded-circle border me-3">
                                                            <i class="ri-twitter-x-fill fs-16 align-middle"></i>
                                                        </span>
                                                        <span
                                                            class="btn btn-icon btn-outline-primary rounded-circle border">
                                                            <i class="bx bxl-linkedin fs-16 align-middle"></i>
                                                        </span>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6 col-xxl-4">
                                        <div class="card custom-card border shadow-none">
                                            <div class="card-body  user-lock text-center">
                                                <div class="dropdown float-end">
                                                    <a href="javascript:void(0);" class="option-dots"
                                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="true"> <i class="fe fe-more-vertical"></i> </a>
                                                    <div class="dropdown-menu shadow"> <a class="dropdown-item"
                                                            href="javascript:void(0);"><i
                                                                class="fe fe-message-square me-2"></i>
                                                            Message</a> <a class="dropdown-item"
                                                            href="javascript:void(0);"><i class="fe fe-edit-2 me-2"></i>
                                                            Edit</a> <a class="dropdown-item"
                                                            href="javascript:void(0);"><i class="fe fe-eye me-2"></i>
                                                            View</a> <a class="dropdown-item"
                                                            href="javascript:void(0);"><i class="fe fe-trash-2 me-2"></i>
                                                            Delete</a>
                                                    </div>
                                                </div>
                                                <a href="profile.html">
                                                    <img alt="avatar" class="rounded-circle"
                                                        src="../assets/images/faces/4.jpg">
                                                    <h5 class="fs-16 mb-0 mt-3 text-dark fw-semibold">Owen
                                                        Bongcaras</h5>
                                                    <span class="text-muted">Web designer</span>
                                                    <div class="mt-3 d-flex mx-auto text-center justify-content-center">
                                                        <span
                                                            class="btn btn-icon btn-outline-primary rounded-circle border me-3">
                                                            <i class="bx bxl-facebook fs-16 align-middle"></i>
                                                        </span>
                                                        <span
                                                            class="btn btn-icon btn-outline-primary rounded-circle border me-3">
                                                            <i class="ri-twitter-x-fill fs-16 align-middle"></i>
                                                        </span>
                                                        <span
                                                            class="btn btn-icon btn-outline-primary rounded-circle border">
                                                            <i class="bx bxl-linkedin fs-16 align-middle"></i>
                                                        </span>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6 col-xxl-4">
                                        <div class="card custom-card border shadow-none">
                                            <div class="card-body  user-lock text-center">
                                                <div class="dropdown float-end">
                                                    <a href="javascript:void(0);" class="option-dots"
                                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="true"> <i class="fe fe-more-vertical"></i> </a>
                                                    <div class="dropdown-menu shadow"> <a class="dropdown-item"
                                                            href="javascript:void(0);"><i
                                                                class="fe fe-message-square me-2"></i>
                                                            Message</a> <a class="dropdown-item"
                                                            href="javascript:void(0);"><i class="fe fe-edit-2 me-2"></i>
                                                            Edit</a> <a class="dropdown-item"
                                                            href="javascript:void(0);"><i class="fe fe-eye me-2"></i>
                                                            View</a> <a class="dropdown-item"
                                                            href="javascript:void(0);"><i class="fe fe-trash-2 me-2"></i>
                                                            Delete</a>
                                                    </div>
                                                </div>
                                                <a href="profile.html">
                                                    <img alt="avatar" class="rounded-circle"
                                                        src="../assets/images/faces/8.jpg">
                                                    <h5 class="fs-16 mb-0 mt-3 text-dark fw-semibold">Stephen
                                                        Metcalfe</h5>
                                                    <span class="text-muted">Administrator</span>
                                                    <div class="mt-3 d-flex mx-auto text-center justify-content-center">
                                                        <span
                                                            class="btn btn-icon btn-outline-primary rounded-circle border me-3">
                                                            <i class="bx bxl-facebook fs-16 align-middle"></i>
                                                        </span>
                                                        <span
                                                            class="btn btn-icon btn-outline-primary rounded-circle border me-3">
                                                            <i class="ri-twitter-x-fill fs-16 align-middle"></i>
                                                        </span>
                                                        <span
                                                            class="btn btn-icon btn-outline-primary rounded-circle border">
                                                            <i class="bx bxl-linkedin fs-16 align-middle"></i>
                                                        </span>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6 col-xxl-4">
                                        <div class="card custom-card border shadow-none">
                                            <div class="card-body  user-lock text-center">
                                                <div class="dropdown float-end">
                                                    <a href="javascript:void(0);" class="option-dots"
                                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="true"> <i class="fe fe-more-vertical"></i> </a>
                                                    <div class="dropdown-menu shadow"> <a class="dropdown-item"
                                                            href="javascript:void(0);"><i
                                                                class="fe fe-message-square me-2"></i>
                                                            Message</a> <a class="dropdown-item"
                                                            href="javascript:void(0);"><i class="fe fe-edit-2 me-2"></i>
                                                            Edit</a> <a class="dropdown-item"
                                                            href="javascript:void(0);"><i class="fe fe-eye me-2"></i>
                                                            View</a> <a class="dropdown-item"
                                                            href="javascript:void(0);"><i class="fe fe-trash-2 me-2"></i>
                                                            Delete</a>
                                                    </div>
                                                </div>
                                                <a href="profile.html">
                                                    <img alt="avatar" class="rounded-circle"
                                                        src="../assets/images/faces/2.jpg">
                                                    <h5 class="fs-16 mb-0 mt-3 text-dark fw-semibold">Socrates
                                                        Itumay</h5>
                                                    <span class="text-muted">Project Manager</span>
                                                    <div class="mt-3 d-flex mx-auto text-center justify-content-center">
                                                        <span
                                                            class="btn btn-icon btn-outline-primary rounded-circle border me-3">
                                                            <i class="bx bxl-facebook fs-16 align-middle"></i>
                                                        </span>
                                                        <span
                                                            class="btn btn-icon btn-outline-primary rounded-circle border me-3">
                                                            <i class="ri-twitter-x-fill fs-16 align-middle"></i>
                                                        </span>
                                                        <span
                                                            class="btn btn-icon btn-outline-primary rounded-circle border">
                                                            <i class="bx bxl-linkedin fs-16 align-middle"></i>
                                                        </span>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6 col-xxl-4">
                                        <div class="card custom-card border shadow-none">
                                            <div class="card-body  user-lock text-center">
                                                <div class="dropdown float-end">
                                                    <a href="javascript:void(0);" class="option-dots"
                                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="true"> <i class="fe fe-more-vertical"></i> </a>
                                                    <div class="dropdown-menu shadow"> <a class="dropdown-item"
                                                            href="javascript:void(0);"><i
                                                                class="fe fe-message-square me-2"></i>
                                                            Message</a> <a class="dropdown-item"
                                                            href="javascript:void(0);"><i class="fe fe-edit-2 me-2"></i>
                                                            Edit</a> <a class="dropdown-item"
                                                            href="javascript:void(0);"><i class="fe fe-eye me-2"></i>
                                                            View</a> <a class="dropdown-item"
                                                            href="javascript:void(0);"><i class="fe fe-trash-2 me-2"></i>
                                                            Delete</a>
                                                    </div>
                                                </div>
                                                <a href="profile.html">
                                                    <img alt="avatar" class="rounded-circle"
                                                        src="../assets/images/faces/3.jpg">
                                                    <h5 class="fs-16 mb-0 mt-3 text-dark fw-semibold">Reynante
                                                        Labares</h5>
                                                    <span class="text-muted">Web Designer</span>
                                                    <div class="mt-3 d-flex mx-auto text-center justify-content-center">
                                                        <span
                                                            class="btn btn-icon btn-outline-primary rounded-circle border me-3">
                                                            <i class="bx bxl-facebook fs-16 align-middle"></i>
                                                        </span>
                                                        <span
                                                            class="btn btn-icon btn-outline-primary rounded-circle border me-3">
                                                            <i class="ri-twitter-x-fill fs-16 align-middle"></i>
                                                        </span>
                                                        <span
                                                            class="btn btn-icon btn-outline-primary rounded-circle border">
                                                            <i class="bx bxl-linkedin fs-16 align-middle"></i>
                                                        </span>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6 col-xxl-4">
                                        <div class="card custom-card border shadow-none mb-xxl-0">
                                            <div class="card-body  user-lock text-center">
                                                <div class="dropdown float-end">
                                                    <a href="javascript:void(0);" class="option-dots"
                                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="true"> <i class="fe fe-more-vertical"></i> </a>
                                                    <div class="dropdown-menu shadow"> <a class="dropdown-item"
                                                            href="javascript:void(0);"><i
                                                                class="fe fe-message-square me-2"></i>
                                                            Message</a> <a class="dropdown-item"
                                                            href="javascript:void(0);"><i class="fe fe-edit-2 me-2"></i>
                                                            Edit</a> <a class="dropdown-item"
                                                            href="javascript:void(0);"><i class="fe fe-eye me-2"></i>
                                                            View</a> <a class="dropdown-item"
                                                            href="javascript:void(0);"><i class="fe fe-trash-2 me-2"></i>
                                                            Delete</a>
                                                    </div>
                                                </div>
                                                <a href="profile.html">
                                                    <img alt="avatar" class="rounded-circle"
                                                        src="../assets/images/faces/4.jpg">
                                                    <h5 class="fs-16 mb-0 mt-3 text-dark fw-semibold">Owen
                                                        Bongcaras</h5>
                                                    <span class="text-muted">App Developer</span>
                                                    <div class="mt-3 d-flex mx-auto text-center justify-content-center">
                                                        <span
                                                            class="btn btn-icon btn-outline-primary rounded-circle border me-3">
                                                            <i class="bx bxl-facebook fs-16 align-middle"></i>
                                                        </span>
                                                        <span
                                                            class="btn btn-icon btn-outline-primary rounded-circle border me-3">
                                                            <i class="ri-twitter-x-fill fs-16 align-middle"></i>
                                                        </span>
                                                        <span
                                                            class="btn btn-icon btn-outline-primary rounded-circle border">
                                                            <i class="bx bxl-linkedin fs-16 align-middle"></i>
                                                        </span>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6 col-xxl-4">
                                        <div class="card custom-card border shadow-none mb-xxl-0">
                                            <div class="card-body  user-lock text-center">
                                                <div class="dropdown float-end">
                                                    <a href="javascript:void(0);" class="option-dots"
                                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="true"> <i class="fe fe-more-vertical"></i> </a>
                                                    <div class="dropdown-menu shadow"> <a class="dropdown-item"
                                                            href="javascript:void(0);"><i
                                                                class="fe fe-message-square me-2"></i>
                                                            Message</a> <a class="dropdown-item"
                                                            href="javascript:void(0);"><i class="fe fe-edit-2 me-2"></i>
                                                            Edit</a> <a class="dropdown-item"
                                                            href="javascript:void(0);"><i class="fe fe-eye me-2"></i>
                                                            View</a> <a class="dropdown-item"
                                                            href="javascript:void(0);"><i class="fe fe-trash-2 me-2"></i>
                                                            Delete</a>
                                                    </div>
                                                </div>
                                                <a href="profile.html">
                                                    <img alt="avatar" class="rounded-circle"
                                                        src="../assets/images/faces/8.jpg">
                                                    <h5 class="fs-16 mb-0 mt-3 text-dark fw-semibold">Stephen
                                                        Metcalfe</h5>
                                                    <span class="text-muted">Administrator</span>
                                                    <div class="mt-3 d-flex mx-auto text-center justify-content-center">
                                                        <span
                                                            class="btn btn-icon btn-outline-primary rounded-circle border me-3">
                                                            <i class="bx bxl-facebook fs-16 align-middle"></i>
                                                        </span>
                                                        <span
                                                            class="btn btn-icon btn-outline-primary rounded-circle border me-3">
                                                            <i class="ri-twitter-x-fill fs-16 align-middle"></i>
                                                        </span>
                                                        <span
                                                            class="btn btn-icon btn-outline-primary rounded-circle border">
                                                            <i class="bx bxl-linkedin fs-16 align-middle"></i>
                                                        </span>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6 col-xxl-4">
                                        <div class="card custom-card border shadow-none mb-0">
                                            <div class="card-body  user-lock text-center">
                                                <div class="dropdown float-end">
                                                    <a href="javascript:void(0);" class="option-dots"
                                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="true"> <i class="fe fe-more-vertical"></i> </a>
                                                    <div class="dropdown-menu shadow"> <a class="dropdown-item"
                                                            href="javascript:void(0);"><i
                                                                class="fe fe-message-square me-2"></i>
                                                            Message</a> <a class="dropdown-item"
                                                            href="javascript:void(0);"><i class="fe fe-edit-2 me-2"></i>
                                                            Edit</a> <a class="dropdown-item"
                                                            href="javascript:void(0);"><i class="fe fe-eye me-2"></i>
                                                            View</a> <a class="dropdown-item"
                                                            href="javascript:void(0);"><i class="fe fe-trash-2 me-2"></i>
                                                            Delete</a>
                                                    </div>
                                                </div>
                                                <a href="profile.html">
                                                    <img alt="avatar" class="rounded-circle"
                                                        src="../assets/images/faces/11.jpg">
                                                    <h5 class="fs-16 mb-0 mt-3 text-dark fw-semibold">Michel
                                                        Mathew</h5>
                                                    <span class="text-muted">Ui Developer</span>
                                                    <div class="mt-3 d-flex mx-auto text-center justify-content-center">
                                                        <span
                                                            class="btn btn-icon btn-outline-primary rounded-circle border me-3">
                                                            <i class="bx bxl-facebook fs-16 align-middle"></i>
                                                        </span>
                                                        <span
                                                            class="btn btn-icon btn-outline-primary rounded-circle border me-3">
                                                            <i class="ri-twitter-x-fill fs-16 align-middle"></i>
                                                        </span>
                                                        <span
                                                            class="btn btn-icon btn-outline-primary rounded-circle border">
                                                            <i class="bx bxl-linkedin fs-16 align-middle"></i>
                                                        </span>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane border-0 p-0" id="settings">
                                <form>
                                    <div class="form-group mb-3">
                                        <label class="form-label" for="FullName">Full Name</label>
                                        <input type="text" value="John Doe" id="FullName" class="form-control">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="form-label" for="Email">Email</label>
                                        <input type="email" value="first.last@example.com" id="Email"
                                            class="form-control">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="form-label" for="Username">Username</label>
                                        <input type="text" value="john" id="Username" class="form-control">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="form-label" for="Password">Password</label>
                                        <input type="password" placeholder="6 - 15 Characters" id="Password"
                                            class="form-control">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="form-label" for="RePassword">Re-Password</label>
                                        <input type="password" placeholder="6 - 15 Characters" id="RePassword"
                                            class="form-control">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="form-label" for="AboutMe">About Me</label>
                                        <textarea id="AboutMe" class="form-control">Loren gypsum dolor sit mate, consecrate disciplining lit, tied diam nonunion nib modernism tincidunt it Loretta dolor manga Amalia erst volute. Ur wise denim ad minim venial, quid nostrum exercise ration perambulator suspicious cortisol nil it applique ex ea commodore consequent.</textarea>
                                    </div>
                                    <button class="btn btn-primary waves-effect waves-light w-md"
                                        type="submit">Save</button>
                                </form>
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
                console.log('test')
                $(".container-peringatan").slideUp(200);
                $(".container-project").prop('hidden', false).slideDown(200);
            })

            $('.btn-edit-project').click(function() {
                console.log('test')
                $('.btn-edit-project').hide();
                $('.btn-batal-edit').prop('hidden', false);
                $(".btn-submit-project").prop('hidden', false);

                $('.btn-batal-edit').click(function() {
                    $('.btn-edit-project').fadeIn(200);
                    $('.btn-batal-edit').prop('hidden', true);
                    $(".btn-submit-project").prop('hidden', true);
                })
            })
        })
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
            });

            calendar.render();
        }
    </script>
@endsection
