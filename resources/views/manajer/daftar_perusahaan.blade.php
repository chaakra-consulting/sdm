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
                <form action="" method="POST" id="formDaftarPerusahaan">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nama_perusahaan">Nama Perusahaan</label>
                            <input type="text" name="nama_perusahaan" id="nama_perusahaan" class="form-control" required>
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
            <button type="button" class="btn btn-primary tambahDaftarPerusahaan" data-bs-toggle="modal"
                data-bs-target="#staticBackdrop">
                Tambah Perusahaan
            </button>
        </div>
        <div class="card custom-card">
            <div class="card-header">
                <div class="card-title">
                    Daftar Nama Nama Perusahaan
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatable-basic" class="table table-bordered text-nowrap w-100">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Perusahaan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($perusahaan as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->nama_perusahaan }}</td>
                                    <td>
                                        <a href="" class="btn btn-warning editDaftarPerusahaan"
                                            data-bs-toggle="modal" data-bs-target="#staticBackdrop"
                                            data-id="{{ $item->id }}"
                                            data-nama_perusahaan="{{ $item->nama_perusahaan }}">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="/manajer/daftar-perusahaan/delete/{{ $item->id }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger"
                                                onclick="return confirm('Hapus Data?')"><i
                                                    class="fas fa-trash"></i></button>
                                        </form>
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
            $(".tambahDaftarPerusahaan").click(function() {
                $(".modal-title").text('Tambah Daftar Perusahaan');
                $("#nama_perusahaan").val('');
                $("#formDaftarPerusahaan").attr('action', '/manajer/daftar-perusahaan/store');
            })

            $(".editDaftarPerusahaan").click(function(e) {
                e.preventDefault();
                $(".modal-title").text('Edit Daftar Perusahaan');
                $("#nama_perusahaan").val($(this).data('nama_perusahaan'));

                $("#formDaftarPerusahaan").append('<input type="hidden" name="_method" value="PUT">');
                $("#formDaftarPerusahaan").attr('action', '/manajer/daftar-perusahaan/update/' + $(this)
                    .data('id'));
            })
        })
    </script>
@endsection
