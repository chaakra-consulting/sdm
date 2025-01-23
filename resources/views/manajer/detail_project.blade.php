@extends('layouts.main')

@section('content')

    <style>
        .form-label {
            font-weight: bold;
        }

        .icon-kedip {
            animation: blinkIcon 1s infinite;
        }

        @keyframes blinkIcon {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0;
            }
        }
    </style>

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
            <div class="col-xl-12">
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
                                @if ($project->waktu_mulai == null)
                                    <div class="container-peringatan">
                                        <div class="card border-1">
                                            <div class="alert custom-alert1 alert-warning">
                                                <div class="text-center px-5 pb-0">
                                                    <svg class="custom-alert-icon svg-warning icon-kedip"
                                                        xmlns="http://www.w3.org/2000/svg" height="1.5rem"
                                                        viewBox="0 0 24 24" width="1.5rem" fill="#000000">
                                                        <path d="M0 0h24v24H0z" fill="none" />
                                                        <path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z" />
                                                    </svg>
                                                    <h5>Peringatan</h5>
                                                    <p class="">Project belum dimulai oleh pegawai, silahkan
                                                        konfirmasi ke pegawai!</p>
                                                    <div class="">
                                                        <button type="button"
                                                            class="btn btn-sm btn-secondary m-1 update-project">Lihat
                                                            Projek</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <div class="container-project" {{ $project->waktu_mulai != null ? '' : 'hidden' }}>
                                    <form action="{{ route('manajer.update.project', $project->id) }}" method="post">
                                        @csrf
                                        @method('put')
                                        {{-- <input type="hidden" name="user_id" value="{{ $project->user_id }}"> --}}
                                        <div class="form-group">
                                            <label for="perusahaan_id" class="form-label">Nama Perusahaan</label>
                                            <select name="perusahaan_id" id="perusahaan_id" class="form-control">
                                                <option selected disabled>Pilih Jabatan</option>
                                                @foreach ($perusahaan as $key => $row)
                                                    <option
                                                        {{ old('perusahaan_id', $project == null ? '' : $project->perusahaan_id) == $row->id ? 'selected' : '' }}
                                                        value="{{ $row->id }}">{{ $row->nama_perusahaan }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="nama_project" class="form-label">Nama Project</label>
                                            <input type="text" name="nama_project" id="nama_project" class="form-control"
                                                value="{{ old('nama_project', $project == null ? '' : $project->nama_project) }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="skala_project" class="form-label">Skala Project</label>
                                            <select class="form-select" id="skala_project" name="skala_project" required>
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
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="waktu_mulai" class="form-label">Waktu Mulai</label>
                                                    <input type="date" name="waktu_mulai" id="waktu_mulai"
                                                        class="form-control border-0"
                                                        value="{{ old('waktu_mulai', $project == null ? '' : $project->waktu_mulai) }}"
                                                        readonly>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="waktu_berakhir" class="form-label">Waktu Berakhir</label>
                                                    <input type="date" name="waktu_berakhir" id="waktu_berakhir"
                                                        class="form-control border-0"
                                                        value="{{ old('waktu_berakhir', $project == null ? '' : $project->waktu_berakhir) }}"
                                                        readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="deadline" class="form-label">Deadline</label>
                                            <input type="date" name="deadline" id="deadline" class="form-control"
                                                value="{{ old('deadline', $project == null ? '' : $project->deadline) }}">
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="status" class="form-label">Status</label>
                                                    <input type="text" name="status" id="status"
                                                        class="form-control border-0"
                                                        value="{{ old('status', $project == null ? '' : $project->status) }}"
                                                        readonly>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="progres" height="50px"
                                                        class="form-label">Progres</label>
                                                    {{-- <input type="text" name="progres" id="progres"
                                                        class="form-control border-0"
                                                        style="boreder: 0; box-shadow: none;"
                                                        value="{{ old('progres', $project == null ? '' : $project->progres) }}"
                                                        readonly> --}}
                                                    <div class="progress" style="height: 35px">
                                                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-info"
                                                            aria-valuemin="0" aria-valuemax="100"
                                                            style="width: {{ $project->progres == null ? 0 : $project->progres }}%">
                                                            <strong>
                                                                {{ $project->progres == null ? 0 : $project->progres }}%
                                                            </strong>
                                                        </div>
                                                    </div>
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
            </div>
        </div>
        <div class="mt-1">
            <a href="/manajer/project" class="btn btn-secondary">Kembali</a>
        </div>
    </div>



@endsection

@section('script')
    <script>
        $(document).ready(function() {

            // $(".container-project").hide();
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
@endsection
