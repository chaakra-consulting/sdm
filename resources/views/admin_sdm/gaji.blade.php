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
                    <input type="text" name="gaji_pokok" id="gaji_pokok" value="{{ old('gaji_pokok') }}" class="form-control">
                </div>
                <div class="form-group">
                    <label for="uang_makan">Uang Makan</label>
                    {{-- <input type="number" name="uang_makan" id="uang_makan" value= "uang_makan"class="form-control" required> --}}
                    <input type="text" name="uang_makan" id="uang_makan" value="{{ old('uang_makan') }}"class="form-control">
                </div>
                <div class="form-group">
                    <label for="uang_bensin">Uang Bensin</label>
                    {{-- <input type="number" name="uang_bensin" id="uang_bensin" value= "uang_bensin"class="form-control" required> --}}
                    <input type="text" name="uang_bensin" id="uang_bensin" value="{{ old('uang_bensin') }}"class="form-control">
                </div>
                <div class="form-group">
                    <label for="bpjs_ketenagakerjaan">BPJS Ketenagakerjaan</label>
                    {{-- <input type="number" name="bpjs_ketenagakerjaan" id="bpjs_ketenagakerjaan" value= "bpjs_ketenagakerjaan"class="form-control" required> --}}
                    <input type="text" name="bpjs_ketenagakerjaan" id="bpjs_ketenagakerjaan" value="{{ old('bpjs_ketenagakerjaan') }}"class="form-control">
                </div>
                <div class="form-group">
                    <label for="bpjs_kesehatan">BPJS Kesehatan</label>
                    {{-- <input type="number" name="bpjs_kesehatan" id="bpjs_kesehatan" value= "bpjs_kesehatan"class="form-control" required> --}}
                    <input type="text" name="bpjs_kesehatan" id="bpjs_kesehatan" value="{{ old('bpjs_kesehatan') }}"class="form-control">
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
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="card-title">
                Gaji Karyawan
            </div>
            <ul class="nav nav-pills nav-style-2 mb-3" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" role="tab"
                        href="#aktif" aria-selected="true">Aktif</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" role="tab" href="#tidak-aktif"
                        aria-selected="false">Tidak Aktif</a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content">
                <div class="tab-pane show active text-muted" id="aktif"
                    role="tabpanel">
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
                                        <td>Rp. {{ number_format($row->gaji_pokok ?? 0, 0, ',', '.') }}</td>
                                        <td>Rp. {{ number_format($row->uang_makan ?? 0, 0, ',', '.') }}</td>
                                        <td>Rp. {{ number_format($row->uang_bensin ?? 0, 0, ',', '.') }}</td>
                                        <td>Rp. {{ number_format($row->bpjs_ketenagakerjaan ?? 0, 0, ',', '.') }}</td>
                                        <td>Rp. {{ number_format($row->bpjs_kesehatan ?? 0, 0, ',', '.') }}</td>
                                        
                                        <td>
                                            <a href="" class="btn btn-warning editGaji" data-bs-toggle="modal"
                                                data-bs-target="#staticBackdrop" 
                                                title="Edit"
                                                data-id="{{ $row->id }}"
                                                data-pegawai_id="{{ $row->pegawai_id }}" 
                                                data-pegawai_nama="{{ $row->pegawai && $row->pegawai->nama_lengkap ?  $row->pegawai->nama_lengkap : '-'}}" 
                                                data-gaji_pokok="{{ $row->gaji_pokok }}" 
                                                data-uang_makan="{{ $row->uang_makan }}" 
                                                data-uang_bensin="{{ $row->uang_bensin }}" 
                                                data-bpjs_ketenagakerjaan="{{ $row->bpjs_ketenagakerjaan }}" 
                                                data-bpjs_kesehatan="{{ $row->bpjs_kesehatan }}">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane text-muted" id="tidak-aktif" role="tabpanel">
                    <div class="table-responsive">
                        <table
                            id="datatable-tidak-aktif"
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
                                @foreach($gajis_not_active as $row)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $row->pegawai && $row->pegawai->nama_lengkap ?  $row->pegawai->nama_lengkap : '-'}}</td>
                                        <td>Rp. {{ number_format($row->gaji_pokok ?? 0, 0, ',', '.') }}</td>
                                        <td>Rp. {{ number_format($row->uang_makan ?? 0, 0, ',', '.') }}</td>
                                        <td>Rp. {{ number_format($row->uang_bensin ?? 0, 0, ',', '.') }}</td>
                                        <td>Rp. {{ number_format($row->bpjs_ketenagakerjaan ?? 0, 0, ',', '.') }}</td>
                                        <td>Rp. {{ number_format($row->bpjs_kesehatan ?? 0, 0, ',', '.') }}</td>                                        
                                        <td>
                                            <a href="" class="btn btn-warning editGaji" data-bs-toggle="modal"
                                                data-bs-target="#staticBackdrop" 
                                                title="Edit"
                                                data-id="{{ $row->id }}"
                                                data-pegawai_id="{{ $row->pegawai_id }}" 
                                                data-pegawai_nama="{{ $row->pegawai && $row->pegawai->nama_lengkap ?  $row->pegawai->nama_lengkap : '-'}}" 
                                                data-gaji_pokok="{{ $row->gaji_pokok }}" 
                                                data-uang_makan="{{ $row->uang_makan }}" 
                                                data-uang_bensin="{{ $row->uang_bensin }}" 
                                                data-bpjs_ketenagakerjaan="{{ $row->bpjs_ketenagakerjaan }}" 
                                                data-bpjs_kesehatan="{{ $row->bpjs_kesehatan }}">
                                                <i class="fas fa-edit"></i>
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
            
            $("#formGaji").append('<input type="hidden" name="_method" value="POST">');
            $("#formGaji").attr('action', '/admin_sdm/gaji/store');           
        })
        // <form action="" method="POST" id="formGaji">

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

    $(document).ready(function() {
        $('#datatable-tidak-aktif').DataTable({
            "pageLength": 10,
            "dom": 'lftip', // Menampilkan dropdown "Show entries" (l), filter/search (f), tabel (t), informasi (i), dan pagination (p)
            "language": {
                "paginate": {
                    "first": "First",
                    "last": "Last",
                    "next": "Next",
                    "previous": "Previous"
                },
                "info": "Menampilkan _START_ hingga _END_ dari _TOTAL_ data",
                "search": "" // Kosongkan karena kita akan memasukkan placeholder manual
            },
            "initComplete": function() {
                var input = $('div.dataTables_filter input');
                input.attr('placeholder', 'Cari data...'); // Tambahkan placeholder di kolom search
                input.addClass('form-control'); // Tambahkan class Bootstrap (opsional)
            }
        });
    });
    
    document.addEventListener("DOMContentLoaded", function () {
        const inputs = document.querySelectorAll('input[type="text"]');

        function applyInitialFormat() {
            inputs.forEach(input => {
                if (input.value) {
                    input.value = formatRupiah(input.value.replace(/\D/g, ""));
                }
            });
        }

        inputs.forEach(input => {
            input.addEventListener('input', function () {
                let value = this.value.replace(/\D/g, ""); 
                this.value = value ? formatRupiah(value) : "";
            });

            input.addEventListener("keypress", function (event) {
                let charCode = event.which ? event.which : event.keyCode;
                if (charCode < 48 || charCode > 57) {
                    event.preventDefault();
                }
            });

            input.addEventListener("blur", function () {
                this.value = formatRupiah(this.value.replace(/\D/g, ""));
            });
        });

        document.querySelectorAll('.modal').forEach(modal => {
            modal.addEventListener('shown.bs.modal', function () {
                applyInitialFormat();
            });
        });

        applyInitialFormat();

        document.querySelectorAll("form").forEach(form => {
            form.addEventListener("submit", function () {
                inputs.forEach(input => {
                    input.value = input.value.replace(/\./g, "");
                });
            });
        });

        function formatRupiah(angka) {
            return angka.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }
    });

</script>
@endsection