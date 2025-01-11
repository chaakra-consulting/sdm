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
            <form action="" method="POST" id="formPelatihan" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label for="nama_pelatihan">Nama Pelatihan</label>
                    <input type="text" name="nama_pelatihan" id="nama_pelatihan" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="tujuan_pelatihan">Tujuan Pelatihan</label>
                    <input type="text" name="tujuan_pelatihan" id="tujuan_pelatihan" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="tahun_pelatihan">Tahun Pelatihan</label>
                    <input type="date" name="tahun_pelatihan" id="tahun_pelatihan" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="nomor_sertifikat">Nomor Sertifikat</label>
                    <input type="text" name="nomor_sertifikat" id="nomor_sertifikat" class="form-control" required></input>
                </div>
                <div class="form-group">
                    <label for="upload_sertifikat">Upload Sertifikat <small class="text-danger">(MAX 2MB) pdf only</small></label>
                    <input type="file" name="upload_sertifikat" id="upload_sertifikat" class="form-control" accept="application/pdf">
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

<div class="modal fade" id="staticBackdropViewDokumenPdf" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="staticBackdropLabel">Modal title
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <iframe src="" width="100%" height="700px" id="viewDokumenPdf" ></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                    data-bs-dismiss="modal">Kembali</button>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="mb-2">
        <button type="button" class="btn btn-primary tambahPelatihan" data-bs-toggle="modal"
    data-bs-target="#staticBackdrop">
    Tambah Pelatihan
</button>
    </div>
    <div class="card custom-card">
        <div class="card-header">
            <div class="card-title">
                Data Pelatihan
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
                            <th>Nama Pelatihan</th>
                            <th>Tujuan Pelatihan</th>
                            <th>Tahun Pelatihan</th>
                            <th>Nomor Sertifikat</th>
                            <th>Dokumen Sertifikat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pelatihan as $row)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $row->nama_pelatihan }}</td>
                                <td>{{ $row->tujuan_pelatihan }}</td>
                                <td>{{ $row->tahun_pelatihan }}</td>
                                <td>{{ $row->nomor_sertifikat }}</td>
                                <td>
                                    @if($row->upload_sertifikat != null)
                                    <a href="" class="btn btn-danger btnViewDokumenPdf" data-nama_pelatihan="{{ $row->nama_pelatihan }}" data-dokumen_pdf="{{ asset('uploads/'. $row->upload_sertifikat) }}">View Dokumen</a>
                                    @else
                                    <p class="text-danger">Tidak ada file</p>
                                    @endif
                                </td>
                                <td>
                                    <a href="" class="btn btn-warning editPelatihan" data-bs-toggle="modal"
                                    data-bs-target="#staticBackdrop" data-id="{{ $row->id }}" data-nama_pelatihan="{{ $row->nama_pelatihan }}" data-tujuan_pelatihan="{{ $row
                                    ->tujuan_pelatihan }}" data-tahun_pelatihan="{{ $row->tahun_pelatihan }}" data-nomor_sertifikat="{{ $row->nomor_sertifikat }}" ><i class="fas fa-edit"></i></a>
                                    <form action="/karyawan/pelatihan/delete/{{ $row->id }}" method="POST" class="d-inline">
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

      $(".tambahPelatihan").click(function(){
        $(".modal-title").text('Tambah Pelatihan');
        $("textarea").val(''); // Mengosongkan textarea jika ada
        $("#formPelatihan").attr('action', '/karyawan/pelatihan/store');
      })

      $('.btnViewDokumenPdf').click(function(e){
            e.preventDefault();
            $('#staticBackdropViewDokumenPdf').modal('show');
            $("#staticBackdropViewDokumenPdf .modal-title").text('Sertifikat : ' + $(this).data('nama_pelatihan'));

            let dokumen_pdf = $(this).data('dokumen_pdf');

            $('#viewDokumenPdf').attr('src', dokumen_pdf);
        })


      $(".editPelatihan").click(function(e){
          e.preventDefault();
        $(".modal-title").text('Edit Pelatihan');
        $("#nama_pelatihan").val($(this).data('nama_pelatihan'));
        $("#tujuan_pelatihan").val($(this).data('tujuan_pelatihan'));
        $("#tahun_pelatihan").val($(this).data('tahun_pelatihan'));
        $("#nomor_sertifikat").val($(this).data('nomor_sertifikat'));

        $("#formPelatihan").append('<input type="hidden" name="_method" value="PUT">');
        $("#formPelatihan").attr('action', '/karyawan/pelatihan/update/' + $(this).data('id'));
      })
    })
</script>
@endsection