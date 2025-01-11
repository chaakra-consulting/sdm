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
            <form action="" method="POST" id="formStatusPekerjaan">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label for="nama_status_pekerjaan">Nama Status Pekerjaan</label>
                    <input type="text" name="nama_status_pekerjaan" id="nama_status_pekerjaan" class="form-control" required>
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
        <button type="button" class="btn btn-primary tambahStatusPekerjaan" data-bs-toggle="modal"
    data-bs-target="#staticBackdrop">
    Tambah Nama Status Pekerjaan
</button>
    </div>
    <div class="card custom-card">
        <div class="card-header">
            <div class="card-title">
                Data Pengalaman
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
                        @foreach($status_pekerjaan as $row)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $row->nama_status_pekerjaan }}</td>
                                <td>
                                    <a href="" class="btn btn-warning editStatusPekerjaan" data-bs-toggle="modal"
                                    data-bs-target="#staticBackdrop" data-id="{{ $row->id }}" data-nama_status_pekerjaan="{{ $row->nama_status_pekerjaan }}"><i class="fas fa-edit"></i></a>
                                    <form action="/admin_sdm/status_pekerjaan/delete/{{ $row->id }}" method="POST" class="d-inline">
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

      $(".tambahStatusPekerjaan").click(function(){
        $(".modal-title").text('Tambah Nama Status Pekerjaan');
        $("#formStatusPekerjaan").attr('action', '/admin_sdm/status_pekerjaan/store');
      })

      $(".editStatusPekerjaan").click(function(e){
          e.preventDefault();
        $(".modal-title").text('Edit Nama Status Pekerjaan');
        $("#nama_status_pekerjaan").val($(this).data('nama_status_pekerjaan'));

        $("#formStatusPekerjaan").append('<input type="hidden" name="_method" value="PUT">');
        $("#formStatusPekerjaan").attr('action', '/admin_sdm/status_pekerjaan/update/' + $(this).data('id'));
      })
    })
</script>
@endsection