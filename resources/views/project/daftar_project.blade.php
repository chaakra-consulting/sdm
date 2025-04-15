@extends('layouts.main')

@section('content')
    @if (Auth::check() && Auth::user()->role->slug == 'manager')
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="staticBackdropLabel"></h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="" method="POST" id="formProject">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="nama_project">Nama Project</label>
                                <input type="text" name="nama_project" id="nama_project" class="form-control" required>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="nama_perusahaan">Nama Instansi</label>
                                    <select class="form-control" data-trigger name="nama_perusahaan" id="nama_perusahaan" required>
                                        <option selected disabled>Pilih Instansi</option>
                                        @foreach ($perusahaan as $item)
                                            <option value="{{ $item->id }}" required>{{ $item->nama_perusahaan }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="skala_project">Skala</label>
                                    <select name="skala_project" id="skala_project" data-trigger class="form-control" required>
                                        <option selected disabled>Pilih Skala</option>
                                        <option value="kecil">Kecil</option>
                                        <option value="sedang">Sedang</option>
                                        <option value="besar">Besar</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="format-waktu_mulai">Tanggal Mulai</label>
                                    <div class="input-group">
                                        <div class="input-group-text text-muted"> <i class="ri-calendar-line"></i> </div>
                                        <input type="text" class="form-control" name="format-waktu_mulai" id="format-waktu_mulai" placeholder="Tanggal Mulai" required>
                                        <input type="hidden" name="waktu_mulai" id="waktu_mulai">
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
                                <label for="user">Anggota Project</label>
                                <select name="user[]" id="user" multiple class="form-control">
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
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
    @endif
    <div class="container-fluid">
        @if (Auth::check() && Auth::user()->role->slug == 'manager')
            <div class="mb-2">
                <button type="button" class="btn btn-primary tambahDaftarProject" data-bs-toggle="modal"
                    data-bs-target="#staticBackdrop">
                    Tambah Project
                </button>
            </div>
        @endif
        <div class="card custom-card">
            <div class="card-header">
                <div class="card-title">
                    List Project
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatable-basic" class="table table-bordered text-nowrap w-100">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Perusahaan</th>
                                <th>Nama Project</th>
                                <th>Skala Project</th>
                                <th>Deadline</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (Auth::check() && Auth::user()->role->slug == 'manager')
                                @foreach ($project as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->perusahaan->nama_perusahaan ?? '-' }}</td>
                                        <td>{{ $item->nama_project }}</td>
                                        <td>{{ ucwords($item->skala_project) }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->deadline)->translatedFormat('l, d F Y')}}</td>
                                        <td>{{ ucwords($item->status) }}</td>
                                        <td>
                                            <a href="{{ route('manajer.detail.project', $item->id) }}"
                                                class="btn btn-secondary" data-bs-toggle="tooltip"
                                                data-bs-custom-class="tooltip-secondary" data-bs-placement="top"
                                                title="Detail Project!"><i class='bx bx-detail'></i>
                                            </a>
                                            <form action="{{ route('manajer.delete.project', $item->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger delete"
                                                    data-id="{{ $item->id }}"
                                                    data-nama_project="{{ $item->nama_project }}" data-bs-toggle="tooltip"
                                                    data-bs-custom-class="tooltip-danger" data-bs-placement="top"
                                                    title="Hapus Project!">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            @if (Auth::check() && Auth::user()->role->slug == 'karyawan')
                                @foreach ($userProject as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->project_perusahaan->perusahaan->nama_perusahaan ?? '-' }}</td>
                                        <td>{{ $item->project_perusahaan->nama_project }}</td>
                                        <td>{{ ucwords($item->project_perusahaan->skala_project) }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->project_perusahaan->deadline)->translatedFormat('l, d F Y') }}</td>
                                        <td>{{ ucwords($item->project_perusahaan->status) }}</td>
                                        <td>
                                            <a href="{{ route('karyawan.detail.project', $item->id) }}"
                                                class="btn btn-secondary" data-bs-toggle="tooltip"
                                                data-bs-custom-class="tooltip-secondary" data-bs-placement="top"
                                                title="Detail Project!"><i class='bx bx-detail'></i>
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
    <script>
        $(document).ready(function() {
            $(".tambahDaftarProject").click(function() {
                $(".modal-title").text('Tambah Project');
                $("#nama_perusahaan").change().val('Pilih Perusahaan');
                $("#nama_project").val('');
                $("#skala_project").change().val('Pilih Skala Project');
                $("#deadline").val('');
                $("#formProject").attr('action', '/manajer/project/store');
            });
        })
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function(){
            flatpickr("#format-waktu_mulai", {
                dateFormat: "Y-m-d",
                altInput: true,
                altFormat: "d F Y",
                onChange: function(selectedDates, dateStr, instance){
                    document.getElementById("waktu_mulai").value = dateStr;
                },
                appendTo: document.getElementById("staticBackdrop")
            });
            flatpickr("#format-deadline", {
                dateFormat: "Y-m-d",
                altInput: true,
                altFormat: "d F Y",
                onChange: function(selectedDates, dateStr, instance){
                    document.getElementById("deadline").value = dateStr;
                },
                appendTo: document.getElementById("staticBackdrop")
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            new Choices('#user', {
                removeItemButton: true,
                searchEnabled: true,
                noResultsText: "Tidak ada hasil yang cocok",
                noChoicesText: "Tidak ada pilihan tersedia"
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $(".delete").click(function(e) {
                e.preventDefault();

                let projectId = $(this).data("id");
                let projectName = $(this).data("nama_project");

                Swal.fire({
                    title: "Konfirmasi Hapus Project",
                    text: "Apakah kamu yakin ingin menghapus project '" + projectName + "'?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Ya, Hapus!",
                    cancelButtonText: "Batal"
                }).then((result) => {
                    if (result.isConfirmed) {
                        let form = $('<form action="/manajer/project/delete/' + projectId +
                            '" method="POST">' +
                            '@csrf' +
                            '@method('DELETE')' +
                            '</form>');

                        $('body').append(form);
                        form.submit();
                    }
                });
            });
            $(".confirm-project").click(function(e) {
                e.preventDefault();

                let projectId = $(this).data("id");
                let projectName = $(this).data("nama_project");
                let projectStatus = $(this).data("status");

                Swal.fire({
                    title: "Konfirmasi Hapus Project",
                    text: "Apakah kamu yakin ingin mengambil project '" + projectName + "'?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Ya, Ambil!",
                    cancelButtonText: "Batal"
                }).then((result) => {
                    if (result.isConfirmed) {
                        let form = $(
                            '<form action="{{ route('karyawan.project.store') }}" method="POST">' +
                            '@csrf' +
                            '<input type="hidden" name="project_perusahaan_id" value="' +
                            projectId + '">' +
                            '<input type="hidden" name="user" value="{{ Auth::user()->id }}">' +
                            '<input type="hidden" name="status" value="' + projectStatus +
                            '">' +
                            '</form>');

                        $('body').append(form);
                        form.submit();
                    }
                });
            });
            $(".delete-project").click(function(e) {
                e.preventDefault();

                let projectId = $(this).data("id");
                let projectName = $(this).data("nama_project");

                Swal.fire({
                    title: "Konfirmasi Delete Project",
                    text: "Apakah kamu yakin ingin menghapus project '" + projectName + "'?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Ya, Hapus!",
                    cancelButtonText: "Batal"
                }).then((result) => {
                    if (result.isConfirmed) {
                        let form = $(
                            '<form action="/karyawan/project/delete/' + projectId +
                            '" method="POST">' +
                            '@csrf' +
                            '@method('DELETE')' +
                            '</form>');

                        $('body').append(form);
                        form.submit();
                    }
                });
            });
        });
    </script>
@endsection
