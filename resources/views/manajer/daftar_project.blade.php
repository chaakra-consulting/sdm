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
                                    <td>{{ ucwords($item->skala_project) }}</td>
                                    <td>{{ $item->deadline }}</td>
                                    <td>{{ ucwords($item->status) }}</td>
                                    <td>
                                        <a href="{{ route('manajer.detail.project', $item->id) }}" class="btn btn-secondary">Detail</a>
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
        })
    </script>
@endsection
