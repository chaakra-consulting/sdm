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
                                <option value="Kecil">Kecil</option>
                                <option value="Sedang">Sedang</option>
                                <option value="Besar">Besar</option>
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
    <div class="container-fluid">
        <div class="mb-2">
            <button type="button" class="btn btn-primary tambahDaftarProject" data-bs-toggle="modal"
                data-bs-target="#staticBackdrop">
                Tambah Project
            </button>
        </div>
        <div class="card custom-card">
            <div class="card-header">
                <div class="card-title">
                    Daftar Project
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
                            @foreach ($project as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->perusahaan?->nama_perusahaan ?? 'Tidak ada data' }}</td>
                                    <td>{{ $item->nama_project }}</td>
                                    <td>{{ $item->skala_project }}</td>
                                    <td>{{ $item->deadline }}</td>
                                    <td>{{ $item->status }}</td>
                                    <td>
                                        <a href="#" class="btn btn-secondary detailProject" data-bs-toggle="modal"
                                            data-bs-target="#staticBackdrop" data-id="{{ $item->id }}"
                                            data-nama_perusahaan="{{ $item->perusahaan?->nama_perusahaan ?? 'Tidak ada data' }}"
                                            data-nama_project="{{ $item->nama_project }}"
                                            data-skala_project="{{ $item->skala_project }}"
                                            data-deadline="{{ $item->deadline }}">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
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

            $(".editDaftarPerusahaan").click(function(e) {
                e.preventDefault();
                $(".modal-title").text('Edit Daftar Perusahaan');
                $("#nama_perusahaan").val($(this).data('nama_perusahaan'));

                $("#formProject").append('<input type="hidden" name="_method" value="PUT">');
                $("#formProject").attr('action', '/manajer/daftar-perusahaan/update/' + $(this)
                    .data('id'));
            })
            $(".detailProject").click(function(e) {
                e.preventDefault();
                console.log($(this).data('nama_perusahaan'));
                console.log($(this).data('skala_project'));
                $(".modal-title").text('Detail Project');
                $("#nama_perusahaan").val($(this).data('nama_perusahaan')).change('');
                $("#nama_project").val($(this).data('nama_project'));
                $("#skala_project").val($(this).data('skala_project')).change('');
                $("#deadline").val($(this).data('deadline'));
                $("#formProject").append('<input type="hidden" name="_method" value="PUT">');
                $("#formProject").attr('action', '/manajer/project/update/' + $(this).data('id'));
            });

        })
    </script>
@endsection
