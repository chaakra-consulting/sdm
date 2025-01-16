@extends('layouts.main')

@section('content')

<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="staticBackdropLabel">Modal title
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <form action="" method="POST" id="formSubJabatan">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label for="nama_sub_jabatan">Nama Sub Jabatan</label>
                    <input type="text" name="nama_sub_jabatan" id="nama_sub_jabatan" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                    data-bs-dismiss="modal">Kembali</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="mb-2">
        <button type="button" class="btn btn-primary tambahSubJabatan" data-bs-toggle="modal"
    data-bs-target="#staticBackdrop">
    Tambah Jabatan
</button>
    </div>
    <div class="card custom-card">
        <div class="card-header">
            <div class="card-title">
                Data Sub Jabatan
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table
                    id="datatable-basic"
                    class="table table-bordered text-nowrap w-100"
                >
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Sub Jabatan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sub_jabatan as $row)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $row->nama_sub_jabatan }}</td>
                                <td>
                                    <a href="" class="btn btn-warning editSubJabatan" data-bs-toggle="modal"
                                    data-bs-target="#staticBackdrop" data-id="{{ $row->id }}" data-nama_sub_jabatan="{{ $row->nama_sub_jabatan }}"><i class="fas fa-edit"></i></a>
                                    <form action="/admin_sdm/sub_jabatan/delete/{{ $row->id }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Hapus Data?')"><i class="fas fa-trash"></i></button>
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
    $(document).ready(function(){

      $(".tambahSubJabatan").click(function(){
        $(".modal-title").text('Tambah Sub Jabatan');
        $("#formSubJabatan").attr('action', '/admin_sdm/sub_jabatan/store');
      })

      $(".editSubJabatan").click(function(e){
          e.preventDefault();
        $(".modal-title").text('Edit Sub Jabatan');
        $("#nama_sub_jabatan").val($(this).data('nama_sub_jabatan'));

        $("#formSubJabatan").append('<input type="hidden" name="_method" value="PUT">');
        $("#formSubJabatan").attr('action', '/admin_sdm/sub_jabatan/update/' + $(this).data('id'));
      })
    })
</script>
@endsection