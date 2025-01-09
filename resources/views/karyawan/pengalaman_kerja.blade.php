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
            <form action="" method="POST" id="formPengalaman" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label for="nama_perusahaan">Nama Perusahaan</label>
                    <input type="text" name="nama_perusahaan" id="nama_perusahaan" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="periode">Periode</label>
                    <input type="text" name="periode" id="periode" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="jabatan_akhir">Jabatan Akhir</label>
                    <input type="text" name="jabatan_akhir" id="jabatan_akhir" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="alasan_keluar">Alasan Keluar</label>
                    <textarea name="alasan_keluar" id="alasan_keluar" cols="30" rows="10" class="form-control" required></textarea>
                </div>
                <div class="form-group">
                    <label for="no_hp_referensi">No Hp Referensi</label>
                    <input type="number" name="no_hp_referensi" id="no_hp_referensi" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="upload_surat_referensi">Surat Referensi <small class="text-danger">(MAX 2MB) pdf only</small></label>
                    <input type="file" name="upload_surat_referensi" id="upload_referensi" class="form-control" accept="application/pdf">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btnClearForm">Clear Form</button>
                <button type="button" class="btn btn-secondary"
                    data-bs-dismiss="modal">Kembali</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
        </div>
    </div>
</div>

{{-- Modal View Sertifikat --}}
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
        <button type="button" class="btn btn-primary tambahPengalaman" data-bs-toggle="modal"
    data-bs-target="#staticBackdrop">
    Tambah Pengalaman Kerja
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
                            <th>Nama Perusahaan</th>
                            <th>Periode</th>
                            <th>Jabatan Akhir</th>
                            <th>Alasan Keluar</th>
                            <th>Kontak Referensi</th>
                            <th>Surat Referensi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pengalaman_kerja as $row)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $row->nama_perusahaan }}</td>
                                <td>{{ $row->periode }}</td>
                                <td>{{ $row->jabatan_akhir }}</td>
                                <td>{{ $row->alasan_keluar }}</td>
                                <td>{{ $row->no_hp_referensi }}</td>
                                <td>
                                    <a href="" class="btn btn-danger btnViewDokumenPdf" data-nama_perusahaan="{{ $row->nama_perusahaan }}" data-dokumen_pdf="{{ asset('uploads/'. $row->upload_surat_referensi) }}">View Dokumen</a>
                                </td>
                                <td>
                                    <a href="" class="btn btn-warning editPengalaman" data-bs-toggle="modal"
                                    data-bs-target="#staticBackdrop" data-id_pengalaman="{{ $row->id }}" data-nama_perusahaan="{{ $row->nama_perusahaan }}" data-periode="{{ $row
                                    ->periode }}" data-jabatan_akhir="{{ $row->jabatan_akhir }}" data-alasan_keluar="{{ $row->alasan_keluar }}" data-no_hp_referensi="{{ $row->no_hp_referensi }}" data-upload_surat_referensi="{{ $row->upload_surat_referensi }}" data-dokumen_pdf="{{ asset('uploads/'. $row->upload_surat_referensi) }}"><i class="fas fa-edit"></i></a>
                                    <form action="/karyawan/pengalaman_kerja/delete/{{ $row->id }}" method="POST" class="d-inline">
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
        
        $('.btnViewDokumenPdf').click(function(e){
            e.preventDefault();
            $('#staticBackdropViewDokumenPdf').modal('show');
            $("#staticBackdropViewDokumenPdf .modal-title").text('Surat Referensi : ' + $(this).data('nama_perusahaan'));

            let dokumen_pdf = $(this).data('dokumen_pdf');
            console.log(dokumen_pdf);
            $('#viewDokumenPdf').attr('src', dokumen_pdf);
        })

        $(".tambahPengalaman").click(function(){
            $(".modal-title").text('Tambah Pengalaman Kerja');
            $("#formPengalaman").attr('action', '/karyawan/pengalaman_kerja/store');
        })

      $(".editPengalaman").click(function(e){
        e.preventDefault();
        $(".modal-title").text('Edit Pengalaman Kerja');
        $("#nama_perusahaan").val($(this).data('nama_perusahaan'));
        $("#periode").val($(this).data('periode'));
        $("#jabatan_akhir").val($(this).data('jabatan_akhir'));
        $("#alasan_keluar").val($(this).data('alasan_keluar'));
        $("#no_hp_referensi").val($(this).data('no_hp_referensi'));

        $("#formPengalaman").append('<input type="hidden" name="_method" value="PUT">');
        $("#formPengalaman").attr('action', '/karyawan/pengalaman_kerja/update/' + $(this).data('id_pengalaman'));
      })

      $(".btnClearForm").click(function(){
        $("#nama_perusahaan").val('');
        $("#periode").val('');
        $("#jabatan_akhir").val('');
        $("#alasan_keluar").val('');
        $("#no_hp_referensi").val('');
        $("#upload_referensi").val('');
        $("textarea").val(''); // Mengosongkan textarea jika ada
      })
    })
</script>
@endsection