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
            <form action="" method="POST" id="formGaji">
            @csrf
            <div class="modal-body">
                <!-- Dropdown untuk Tambah Gaji -->
                <div class="form-group tambahGajiDropdown">
                    <label for="pegawai_id_tambah" class="form-label">Pilih Karyawan</label>
                    <select name="pegawai_id" id="pegawai_id_tambah" class="form-control">
                        <option selected disabled>Pilih Karyawan</option>
                        @foreach ($pegawais as $pegawai)
                            <option value="{{ $pegawai->id }}">
                                {{ $pegawai->nama_lengkap }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <!-- Dropdown untuk Edit Gaji -->
                <div class="form-group editGajiDropdown" style="display: none;">
                    <label for="pegawai_id_edit" class="form-label">Pilih Karyawan</label>
                    <input type="string" name="pegawai_nama" id="pegawai_nama" value= "pegawai_nama"class="form-control" disabled>
                </div>
                <div class="form-group">
                    <label for="gaji_pokok">Gaji Pokok</label>
                    {{-- <input type="number" name="gaji_pokok" id="gaji_pokok" value= "gaji_pokok" class="form-control" required> --}}
                    <input type="number" name="gaji_pokok" id="gaji_pokok" value="{{ old('gaji_pokok') }}" class="form-control">
                </div>
                <div class="form-group">
                    <label for="uang_makan">Uang Makan</label>
                    {{-- <input type="number" name="uang_makan" id="uang_makan" value= "uang_makan"class="form-control" required> --}}
                    <input type="number" name="uang_makan" id="uang_makan" value="{{ old('uang_makan') }}"class="form-control">
                </div>
                <div class="form-group">
                    <label for="uang_bensin">Uang Bensin</label>
                    {{-- <input type="number" name="uang_bensin" id="uang_bensin" value= "uang_bensin"class="form-control" required> --}}
                    <input type="number" name="uang_bensin" id="uang_bensin" value="{{ old('uang_bensin') }}"class="form-control">
                </div>
                <div class="form-group">
                    <label for="bpjs_ketenagakerjaan">BPJS Ketenagakerjaan</label>
                    {{-- <input type="number" name="bpjs_ketenagakerjaan" id="bpjs_ketenagakerjaan" value= "bpjs_ketenagakerjaan"class="form-control" required> --}}
                    <input type="number" name="bpjs_ketenagakerjaan" id="bpjs_ketenagakerjaan" value="{{ old('bpjs_ketenagakerjaan') }}"class="form-control">
                </div>
                <div class="form-group">
                    <label for="bpjs_kesehatan">BPJS Kesehatan</label>
                    {{-- <input type="number" name="bpjs_kesehatan" id="bpjs_kesehatan" value= "bpjs_kesehatan"class="form-control" required> --}}
                    <input type="number" name="bpjs_kesehatan" id="bpjs_kesehatan" value="{{ old('bpjs_kesehatan') }}"class="form-control">
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
        <button type="button" class="btn btn-primary tambahGaji" data-bs-toggle="modal"
        data-bs-target="#staticBackdrop">
        Tambah Data Gaji
        </button>
    </div>
    <div class="card custom-card">
        <div class="card-header">
            <div class="card-title">
                Gaji Karyawan
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
                            <th>Nama Karyawan</th>
                            <th>Gaji Pokok</th>
                            <th>Uang Makan</th>
                            <th>Uang Bensin</th>
                            <th>BPJS Ketenagakerjaan</th>
                            <th>BPJS Kesehatan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($gajis as $row)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $row->pegawai && $row->pegawai->nama_lengkap ?  $row->pegawai->nama_lengkap : '-'}}</td>
                                <td>{{ $row->gaji_pokok ?? '-'}}</td>
                                <td>{{ $row->uang_makan ?? '-' }}</td>
                                <td>{{ $row->uang_bensin ?? '-' }}</td>
                                <td>{{ $row->bpjs_ketenagakerjaan ?? '-' }}</td>
                                <td>{{ $row->bpjs_kesehatan ?? '-' }}</td>
                                <td>
                                    <a href="" class="btn btn-warning editGaji" data-bs-toggle="modal"
                                        data-bs-target="#staticBackdrop" data-id="{{ $row->id }}" data-pegawai_id="{{ $row->pegawai_id }}" data-pegawai_nama="{{ $row->pegawai && $row->pegawai->nama_lengkap ?  $row->pegawai->nama_lengkap : '-'}}" data-gaji_pokok="{{ $row->gaji_pokok }}" data-uang_makan="{{ $row->uang_makan }}" data-uang_bensin="{{ $row->uang_bensin }}" data-bpjs_ketenagakerjaan="{{ $row->bpjs_ketenagakerjaan }}" data-bpjs_kesehatan="{{ $row->bpjs_kesehatan }}"><i class="fas fa-edit"></i>
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
    $(document).ready(function(){
        $(".tambahGaji").click(function(){
            $(".modal-title").text('Tambah Gaji Karyawan');
            $(".tambahGajiDropdown").show();
            $(".editGajiDropdown").hide(); 
            $("#formGaji").attr('action', '/admin_sdm/gaji/store');
        })

        $(".editGaji").click(function(e){
            e.preventDefault();
            $(".modal-title").text('Edit Sub Jabatan');
            $(".editGajiDropdown").show();
            $(".tambahGajiDropdown").hide();
            $("#pegawai_id_edit").val($(this).data('pegawai_id'));
            $("#pegawai_nama").val($(this).data('pegawai_nama'));
            $("#gaji_pokok").val($(this).data('gaji_pokok'));
            $("#uang_makan").val($(this).data('uang_makan'));
            $("#uang_bensin").val($(this).data('uang_bensin'));
            $("#bpjs_ketenagakerjaan").val($(this).data('bpjs_ketenagakerjaan'));
            $("#bpjs_kesehatan").val($(this).data('bpjs_kesehatan'));
            $("#pegawai_id").prop('disabled', true);

            $("#formGaji").append('<input type="hidden" name="_method" value="PUT">');
            $("#formGaji").attr('action', '/admin_sdm/gaji/update/' + $(this).data('id'));
        })
    })
</script>
@endsection