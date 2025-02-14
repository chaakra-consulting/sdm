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
                                <label for="nama_perusahaan">Nama Perusahaan</label>
                                <select name="nama_perusahaan" id="nama_perusahaan" class="form-control">
                                    <option selected disabled>Pilih Perusahaan</option>
                                    @foreach ($perusahaan as $item)
                                        <option value="{{ $item->id }}">{{ $item->nama_perusahaan }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="nama_project">Nama Project</label>
                                <input type="text" name="nama_project" id="nama_project" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="skala_project">Skala Project</label>
                                <select name="skala_project" id="skala_project" class="form-control" required>
                                    <option selected disabled>Pilih Skala Project</option>
                                    <option value="kecil">Kecil</option>
                                    <option value="sedang">Sedang</option>
                                    <option value="besar">Besar</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="deadline">Deadline</label>
                                <input type="date" name="deadline" id="deadline" class="form-control" required>
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
                                        <td>{{ $item->deadline }}</td>
                                        <td>{{ ucwords($item->status) }}</td>
                                        <td>
                                            <a href="{{ route('manajer.detail.project', $item->id) }}"
                                                class="btn btn-secondary" data-bs-toggle="tooltip"
                                                data-bs-custom-class="tooltip-secondary" data-bs-placement="top"
                                                title="Detail Project!"><i class='bx bx-detail'></i></a>
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
                                @foreach ($project as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->perusahaan->nama_perusahaan ?? '-' }}</td>
                                        <td>{{ $item->nama_project }}</td>
                                        <td>{{ ucwords($item->skala_project) }}</td>
                                        <td>{{ $item->deadline }}</td>
                                        <td>{{ ucwords($item->status) }}</td>
                                        <td style="text-align: center">
                                            @if (in_array($item->id, $userTakenProjects))
                                                <a href="{{ route('karyawan.detail.project', $item->id) }}"
                                                    class="btn btn-secondary" data-bs-toggle="tooltip"
                                                    data-bs-custom-class="tooltip-secondary" data-bs-placement="top"
                                                    title="Detail Project!">
                                                    <i class='bx bx-detail'></i>
                                                </a>
                                                <form action="{{ route('karyawan.delete.project', $item->id) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger delete-project"
                                                        data-id="{{ $item->id }}"
                                                        data-nama_project="{{ $item->nama_project }}"
                                                        data-bs-toggle="tooltip" data-bs-custom-class="tooltip-danger"
                                                        data-bs-placement="top" title="Hapus Project!">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('karyawan.project.store') }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('post')
                                                    <input type="hidden" name="project_perusahaan_id"
                                                        value="{{ $item->id }}">
                                                    <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                                                    <input type="hidden" name="status" value="{{ $item->status }}">
                                                    <button type="submit" class="btn btn-info confirm-project"
                                                        title="Ambil Project" data-id="{{ $item->id }}"
                                                        data-nama_project="{{ $item->nama_project }}"
                                                        data-status="{{ $item->status }}" data-bs-toggle="tooltip"
                                                        data-bs-custom-class="tooltip-info" data-bs-placement="top"
                                                        title="Ambil Project!">
                                                        <i class="bi bi-rocket-takeoff"></i>
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
            $(".tambahDaftarProject").click(function() {
                $(".modal-title").text('Tambah Project');
                $("#nama_perusahaan").change().val('Pilih Perusahaan');
                $("#nama_project").val('');
                $("#skala_project").change().val('Pilih Skala Project');
                $("#deadline").val('');
                $("#formProject").attr('action', '/manajer/project/store');
            })
        })
    </script>
    <script>
        $(document).ready(function() {
            $(".delete").click(function(e) {
                e.preventDefault();

                let projectId = $(this).data("id");
                let projectName = $(this).data("nama_project");

                Swal.fire({
                    title: "Konfirmasi Ambil Project",
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
                    title: "Konfirmasi Ambil Project",
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
                            '<input type="hidden" name="user_id" value="{{ Auth::user()->id }}">' +
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
