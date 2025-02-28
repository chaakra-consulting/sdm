@extends('layouts.main')

@section('content')
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="staticBackdropLabel">Modal title
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="POST" id="formHariLibur">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nama">Nama</label>
                            <input type="text" name="nama" id="nama" class="form-control"
                                placeholder="Nama" required>
                        </div>
                        <div class="form-group">
                            <label for="tanggal">Tanggal</label>
                            <input type="date" name="tanggal" id="tanggal" class="form-control" required>
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
            <button type="button" class="btn btn-primary tambahHariLibur" data-bs-toggle="modal"
                data-bs-target="#staticBackdrop">
                Tambah Hari Libur
            </button>
        </div>
        <div class="card custom-card">
            <div class="card-header">
                <div class="card-title">
                    Data Hari Libur
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatable-basic" class="table table-bordered text-nowrap w-100">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($hari_libur as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->nama }}</td>
                                    <td>{{ $item->tanggal }}</td>
                                    <td><a href="" class="btn btn-warning editHariLibur" data-bs-toggle="modal"
                                            data-bs-target="#staticBackdrop" data-id="{{ $item->id }}"
                                            data-nama="{{ $item->nama }}"
                                            data-tanggal="{{ $item->tanggal }}"><i
                                                class="fas fa-edit"></i></a>
                                        <form action="/admin_sdm/hari_libur/delete/{{ $item->id }}" method="POST"
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

            $(".tambahHariLibur").click(function() {
                $(".modal-title").text('Tambah Hari Libur');
                $("#formHariLibur").attr('action', '/admin_sdm/hari_libur/store');
            })

            $(".editHariLibur").click(function(e) {
                e.preventDefault();
                $(".modal-title").text('Edit Hari Libur');
                $("#nama").val($(this).data('nama'));
                $("#tanggal").val($(this).data('tanggal'));

                $("#formHariLibur").append('<input type="hidden" name="_method" value="PUT">');
                $("#formHariLibur").attr('action', '/admin_sdm/hari_libur/update/' + $(this)
                    .data('id'));
            })
        })
    </script>
@endsection
