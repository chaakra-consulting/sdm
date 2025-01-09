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
            <form action="" method="POST" id="formSocialMedia">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label for="nama_social_media">Nama Sosial Media</label>
                    <select name="nama_social_media" id="nama_social_media" class="form-control">
                        <option selected disabled>Pilih Sosial Media</option>
                        <option value="instagram">Instagram</option>
                        <option value="facebook">Facebook</option>
                        <option value="linkedin">LinkedIn</option>
                        <option value="twitter">Twitter</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="link">Link Sosial Media</label>
                    <input type="url" name="link" id="link" class="form-control" required>
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
        <button type="button" class="btn btn-primary tambahSocialMedia" data-bs-toggle="modal"
    data-bs-target="#staticBackdrop">
    Tambah Sosial Media
</button>
    </div>
    <div class="card custom-card">
        <div class="card-header">
            <div class="card-title">
                Data Sosial Media
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
                            <th>Sosial Media</th>
                            <th>Link</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($social_media as $row)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $row->nama_social_media }}</td>
                                <td>{{ $row->link }}</td>
                                <td>
                                    <a href="" class="btn btn-warning editSocialMedia" data-bs-toggle="modal"
                                    data-bs-target="#staticBackdrop" data-id="{{ $row->id }}" data-nama_social_media="{{ $row->nama_social_media }}" data-link="{{ $row->link }}"><i class="fas fa-edit"></i></a>
                                    <form action="/karyawan/social_media/delete/{{ $row->id }}" method="POST" class="d-inline">
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
      $(".tambahSocialMedia").click(function(){
        $(".modal-title").text('Tambah Sosial Media');
        $("#nama_social_media").change().val('Pilih Sosial Media');
        $("#link").val('');
        $("#formSocialMedia").attr('action', '/karyawan/social_media/store');
      })

      $(".editSocialMedia").click(function(e){
          e.preventDefault();
        $(".modal-title").text('Edit Sosial Media');
        $("#nama_social_media").change().val($(this).data('nama_social_media'));
        $("#link").val($(this).data('link'));
        
        $("#formSocialMedia").append('<input type="hidden" name="_method" value="PUT">');
        $("#formSocialMedia").attr('action', '/karyawan/social_media/update/' + $(this).data('id'));
      })
    })
</script>
@endsection