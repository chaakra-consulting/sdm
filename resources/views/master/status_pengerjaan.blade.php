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
                <form action="" method="POST" id="formStatusPengerjaan">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nama_status_pengerjaan">Nama Status Pengerjaan</label>
                            <input type="text" name="nama_status_pengerjaan" id="nama_status_pengerjaan" class="form-control"
                                placeholder="Status Pengerjaan" required>
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
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="mb-3">
                    <button type="button" class="btn btn-primary tambahStatusPengerjaan" data-bs-toggle="modal"
                        data-bs-target="#staticBackdrop">
                        Tambah Status Pengerjaan
                    </button>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Status Pengerjaan</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="datatable-basic" class="table table-bordered text-nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Status Pengerjaan</th>
                                        <th>aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($getStatusPengerjaan as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->nama_status_pengerjaan }}</td>
                                            <td>
                                                <a href="javascript:void(0);" class="btn btn-warning editStatusPekerjaan" data-bs-toggle="modal"
                                                    data-bs-target="#staticBackdrop" data-id="{{ $item->id }}"
                                                    data-nama_status_pengerjaan="{{ $item->nama_status_pengerjaan }}">
                                                    <i data-bs-toggle="tooltip" data-bs-custom-class="tooltip-secondary" data-bs-placement="top" 
                                                    title="Edit Tipe Task!" class="fas fa-edit"></i>
                                                </a>
                                                <form action="/manajer/status_pengerjaan/delete/{{ $item->id }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger delete" 
                                                    data-id="{{ $item->id }}" data-nama_status_pengerjaan="{{ $item->nama_status_pengerjaan }}" data-bs-toggle="tooltip" 
                                                    data-bs-custom-class="tooltip-danger" data-bs-placement="top" title="Hapus Tipe Task!">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
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
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function(){
            $(".tambahStatusPengerjaan").click(function() {
                $(".modal-title").text('Tambah Status Pengerjaan');
                $("#formStatusPengerjaan").attr('action', '/manajer/status_pengerjaan/store');
            })
            $(".editStatusPekerjaan").click(function(e) {
                e.preventDefault();
                $(".modal-title").text('Edit Nama Status Pengerjaan');
                $("#nama_status_pengerjaan").val($(this).data('nama_status_pengerjaan'));

                $("#formStatusPengerjaan").append('<input type="hidden" name="_method" value="PUT">');
                $("#formStatusPengerjaan").attr('action', '/manajer/status_pengerjaan/update/' + $(this).data('id'));
            })
        });
    </script>
@endsection