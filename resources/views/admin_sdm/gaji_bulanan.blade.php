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
                <div class="row">
                    <!-- Dropdown untuk Tambah Gaji -->
                    <div class="form-group tambahGajiDropdown">
                        <label for="pegawai_id_tambah" class="form-label">Pilih Karyawan</label>
                        <select name="pegawai_id" id="pegawai_id_tambah" class="form-control">
                            <option selected disabled>Pilih Karyawan</option>
                            {{-- @foreach ($pegawais as $pegawai)
                                <option value="{{ $pegawai->id }}">
                                    {{ $pegawai->nama_lengkap }}
                                </option>
                            @endforeach --}}
                        </select>
                    </div>
                    <!-- Dropdown untuk Edit Gaji -->
                    <div class="form-group editGajiDropdown" style="display: none;">
                        <label for="pegawai_id_edit" class="form-label">Nama Karyawan</label>
                        <input type="string" name="pegawai_nama" id="pegawai_nama" value= "pegawai_nama"class="form-control" disabled>
                    </div>
                    {{-- <div class="form-group">
                        <label for="gaji_pokok">Gaji Pokok</label>
                        <input type="text" name="gaji_pokok" id="gaji_pokok" value="{{ old('gaji_pokok') }}" class="form-control">
                    </div> --}}
                    <label class="d-block text-center fw-bold"><h6>Pendapatan</h6></label>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="gaji_pokok">Gaji Pokok</label>
                            <input type="text" name="gaji_pokok" id="gaji_pokok" value="{{ old('gaji_pokok') }}" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="insentif_uang_makan">Uang Makan</label>
                            <input type="text" name="insentif_uang_makan" id="insentif_uang_makan" value="{{ old('insentif_uang_makan') }}"class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="insentif_kinerja">Kinerja</label>
                            <input type="text" name="insentif_kinerja" id="insentif_kinerja" value="{{ old('insentif_kinerja', 0) }}" class="form-control">
                        </div>
                    </div>                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="insentif_uang_bensin">Uang Bensin</label>
                            <input type="text" name="insentif_uang_bensin" id="insentif_uang_bensin" value="{{ old('insentif_uang_bensin') }}"class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="insentif_penjualan">Penjualan</label>
                            <input type="text" name="insentif_penjualan" id="insentif_penjualan" value="{{ old('insentif_penjualan') }}"class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="overtime">Lembur</label>
                            <input type="text" name="overtime" id="overtime" value="{{ old('overtime') }}"class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="insentif_lainnya">Lainnya</label>
                            <input type="text" name="insentif_lainnya" id="insentif_lainnya" value="{{ old('insentif_lainnya') }}"class="form-control">
                        </div>
                    </div>
                    {{-- <div class="col-md-6">
                        <div class="form-group">
                            <label for="keterangan_insentif_lainnya">Keterangan Insentif Lainnya</label>
                            <input type="text" name="keterangan_insentif_lainnya" id="keterangan_insentif_lainnya" value="{{ old('keterangan_insentif_lainnya') }}"class="form-control">
                        </div>
                    </div> --}}
                    <label class="d-block text-center fw-bold"><h6>Potongan</h6></label>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="potongan_gaji_pokok">Gaji Pokok</label>
                            <input type="text" name="potongan_gaji_pokok" id="potongan_gaji_pokok" value="{{ old('potongan_gaji_pokok') }}"class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="potongan_uang_makan">Uang Makan</label>
                            <input type="text" name="potongan_uang_makan" id="potongan_uang_makan" value="{{ old('potongan_uang_makan') }}"class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="potongan_keterlambatan">Keterlambatan</label>
                            <input type="text" name="potongan_keterlambatan" id="potongan_keterlambatan" value="{{ old('potongan_keterlambatan') }}"class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="potongan_kinerja">Kinerja</label>
                            <input type="text" name="potongan_kinerja" id="potongan_kinerja" value="{{ old('potongan_kinerja', 0) }}" class="form-control">
                        </div>
                    </div>                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="potongan_pajak">Pajak</label>
                            <input type="text" name="potongan_pajak" id="potongan_pajak" value="{{ old('potongan_pajak') }}"class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="potongan_bpjs_ketenagakerjaan">BPJS Ketenagakerjaan</label>
                            <input type="text" name="potongan_bpjs_ketenagakerjaan" id="potongan_bpjs_ketenagakerjaan" value="{{ old('potongan_bpjs_ketenagakerjaan') }}"class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="potongan_bpjs_kesehatan">BPJS Kesehatan</label>
                            <input type="text" name="potongan_bpjs_kesehatan" id="potongan_bpjs_kesehatan" value="{{ old('potongan_bpjs_kesehatan') }}"class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="potongan_kasbon">Kasbon</label>
                            <input type="text" name="potongan_kasbon" id="potongan_kasbon" value="{{ old('potongan_kasbon') }}"class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="potongan_lainnya">Lainnya</label>
                            <input type="text" name="potongan_lainnya" id="potongan_lainnya" value="{{ old('potongan_lainnya') }}"class="form-control">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                    data-bs-dismiss="modal">Kembali</button>
                <button type="submit" id="submitButton" class="btn btn-primary">Simpan</button>
            </div>
        </form>
        </div>
    </div>
</div>

<div class="container-fluid">
    {{-- <div class="mb-2">
        <button type="button" class="btn btn-primary tambahGaji" data-bs-toggle="modal"
        data-bs-target="#staticBackdrop">
        Tambah Data Gaji
        </button>
    </div> --}}
    <div class="card custom-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="card-title">
                Realisasi Gaji Bulanan
            </div>
            <button class="btn btn-sm btn-success ms-2" id="alert-parameter">Sync <i class="fas fa-sync"></i></button> 
            <form action="" method="GET" class="ms-auto" style="max-width: 500px;">
                <div class="row g-3 align-items-end">
                    <div class="col-md-5">
                        <select class="form-select" id="month" name="month">
                            @foreach ($months as $key => $name)
                                <option value="{{ $key }}" {{ $key == $month ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select" name="year">
                            @foreach ($years as $y)
                                <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>
                                    {{ $y }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" id="applyFilter" class="btn btn-primary w-100">Filter</button>
                    </div>
                </div>
            </form>            
        </div>
        
        <div class="card-body">
            <div class="table-responsive">
                <table id="datatable-basic" class="table table-bordered text-nowrap w-100">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Karyawan</th>
                                    <th>Gaji Pokok</th>
                                    <th>Insentif</th>
                                    <th>Potongan</th>
                                    <th>Total Gaji</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($gajis as $row)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $row->pegawai_nama ?  $row->pegawai_nama : '-'}}</td>
                                        <td>Rp. {{ number_format($row->gaji_pokok ?? 0, 0, ',', '.') }}</td>
                                        <td>Rp. {{ number_format($row->insentif_total ?? 0, 0, ',', '.') }}</td>
                                        <td>Rp. {{ number_format($row->potongan_total ?? 0, 0, ',', '.') }}</td>
                                        <td>
                                            <span style="color: green;">Rp. {{ number_format($row->gaji_total ?? 0, 0, ',', '.') }}</span>
                                        </td>
                                        <td>
                                            <a href="" class="btn btn-teal showGaji" data-bs-toggle="modal"
                                                data-bs-target="#staticBackdrop" 
                                                title="Lihat"
                                                data-id="{{ $row->id }}"
                                                data-pegawai_id="{{ $row->pegawai_id }}" 
                                                data-pegawai_nama="{{ $row->pegawai_nama ?  $row->pegawai_nama : '-'}}" 
                                                data-tanggal_gaji="{{ $row->tanggal_gaji }}" 
                                                data-gaji_pokok="{{ $row->gaji_pokok }}" 
                                                data-potongan_gaji_pokok="{{ $row->potongan_gaji_pokok }}" 
                                                data-potongan_uang_makan="{{ $row->potongan_uang_makan }}" 
                                                data-potongan_keterlambatan="{{ $row->potongan_keterlambatan }}" 
                                                data-potongan_kinerja="{{ $row->potongan_kinerja }}" 
                                                data-potongan_pajak="{{ $row->potongan_pajak }}" 
                                                data-potongan_bpjs_ketenagakerjaan="{{ $row->potongan_bpjs_ketenagakerjaan }}" 
                                                data-potongan_bpjs_kesehatan="{{ $row->potongan_bpjs_kesehatan }}"
                                                data-potongan_kasbon="{{ $row->potongan_kasbon }}"
                                                data-potongan_lainnya="{{ $row->potongan_lainnya }}"
                                                data-keterangan_potongan_lainnya="{{ $row->keterangan_potongan_lainnya }}"
                                                data-insentif_kinerja="{{ $row->insentif_kinerja }}"
                                                data-insentif_uang_makan="{{ $row->insentif_uang_makan }}"
                                                data-insentif_uang_bensin="{{ $row->insentif_uang_bensin }}"
                                                data-insentif_penjualan="{{ $row->insentif_penjualan }}"
                                                data-overtime="{{ $row->overtime }}"
                                                data-insentif_lainnya="{{ $row->insentif_lainnya }}"
                                                data-keterangan_insentif_lainnya="{{ $row->keterangan_insentif_lainnya }}"
                                                >
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                            @if($role == "direktur")
                                            <a href="" class="btn btn-warning editGaji" data-bs-toggle="modal"
                                                data-bs-target="#staticBackdrop" 
                                                title="Edit"
                                                data-id="{{ $row->id }}"
                                                data-pegawai_id="{{ $row->pegawai_id }}" 
                                                data-pegawai_nama="{{ $row->pegawai_nama ?  $row->pegawai_nama : '-'}}" 
                                                data-tanggal_gaji="{{ $row->tanggal_gaji }}" 
                                                data-gaji_pokok="{{ $row->gaji_pokok }}" 
                                                data-potongan_gaji_pokok="{{ $row->potongan_gaji_pokok }}" 
                                                data-potongan_uang_makan="{{ $row->potongan_uang_makan }}" 
                                                data-potongan_keterlambatan="{{ $row->potongan_keterlambatan }}" 
                                                data-potongan_kinerja="{{ $row->potongan_kinerja }}" 
                                                data-potongan_pajak="{{ $row->potongan_pajak }}" 
                                                data-potongan_bpjs_ketenagakerjaan="{{ $row->potongan_bpjs_ketenagakerjaan }}" 
                                                data-potongan_bpjs_kesehatan="{{ $row->potongan_bpjs_kesehatan }}"
                                                data-potongan_kasbon="{{ $row->potongan_kasbon }}"
                                                data-potongan_lainnya="{{ $row->potongan_lainnya }}"
                                                data-keterangan_potongan_lainnya="{{ $row->keterangan_potongan_lainnya }}"
                                                data-insentif_kinerja="{{ $row->insentif_kinerja }}"
                                                data-insentif_uang_makan="{{ $row->insentif_uang_makan }}"
                                                data-insentif_uang_bensin="{{ $row->insentif_uang_bensin }}"
                                                data-insentif_penjualan="{{ $row->insentif_penjualan }}"
                                                data-overtime="{{ $row->overtime }}"
                                                data-insentif_lainnya="{{ $row->insentif_lainnya }}"
                                                data-keterangan_insentif_lainnya="{{ $row->keterangan_insentif_lainnya }}"
                                                >
                                                <i class="fas fa-edit"></i>
                                            </a>                                                                                            
                                            @endif
                                            <a href="/cetak-payslip/{{ $row->hash }}" 
                                                class="btn btn-danger" 
                                                data-bs-toggle="tooltip"
                                                data-bs-placement="top" 
                                                title="Cetak Slip"
                                                data-id="{{ $row->id }}"
                                                target="_blank" 
                                                rel="noopener noreferrer">
                                                <i class="fa-solid fa-file-pdf"></i>
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
            
            $("#formGaji").append('<input type="hidden" name="_method" value="POST">');
            $("#formGaji").attr('action', '/{{ $role }}/gaji/store');           
        })
        // <form action="" method="POST" id="formGaji">

        $(".editGaji").click(function(e){
            e.preventDefault();
            $(".modal-title").text('Edit Gaji Bulanan');
            $(".editGajiDropdown").show();
            $(".tambahGajiDropdown").hide();

            $("#pegawai_id_edit").val($(this).data('pegawai_id'));
            $("#pegawai_nama").val($(this).data('pegawai_nama'));
            $("#gaji_pokok").val($(this).data('gaji_pokok'));

            $("#potongan_gaji_pokok").val($(this).data('potongan_gaji_pokok'));
            $("#potongan_uang_makan").val($(this).data('potongan_uang_makan'));
            $("#potongan_kinerja").val($(this).data('potongan_kinerja'));
            $("#potongan_keterlambatan").val($(this).data('potongan_keterlambatan'));
            $("#potongan_pajak").val($(this).data('potongan_pajak'));
            $("#potongan_bpjs_ketenagakerjaan").val($(this).data('potongan_bpjs_ketenagakerjaan'));
            $("#potongan_bpjs_kesehatan").val($(this).data('potongan_bpjs_kesehatan'));
            $("#potongan_kasbon").val($(this).data('potongan_kasbon'));
            $("#potongan_lainnya").val($(this).data('potongan_lainnya'));
            $("#keterangan_potongan_lainnya").val($(this).data('keterangan_potongan_lainnya'));
            
            $("#insentif_kinerja").val($(this).data('insentif_kinerja'));
            $("#insentif_uang_makan").val($(this).data('insentif_uang_makan'));
            $("#insentif_uang_bensin").val($(this).data('insentif_uang_bensin'));
            $("#insentif_penjualan").val($(this).data('insentif_penjualan'));
            $("#overtime").val($(this).data('overtime'));
            $("#insentif_lainnya").val($(this).data('insentif_lainnya'));
            $("#keterangan_insentif_lainnya").val($(this).data('keterangan_insentif_lainnya'));

            $("#pegawai_id").prop('disabled', true);
            $("#gaji_pokok").prop('disabled', false);
            $("#potongan_gaji_pokok").prop('disabled', false);
            $("#potongan_uang_makan").prop('disabled', false);
            $("#potongan_keterlambatan").prop('disabled', false);
            $("#potongan_kinerja").prop('disabled', false);
            $("#potongan_pajak").prop('disabled', false);
            $("#potongan_bpjs_ketenagakerjaan").prop('disabled', false);
            $("#potongan_bpjs_kesehatan").prop('disabled', false);
            $("#potongan_kasbon").prop('disabled', false);
            $("#potongan_lainnya").prop('disabled', false);
            $("#insentif_kinerja").prop('disabled', false);
            $("#insentif_uang_bensin").prop('disabled', false);
            $("#insentif_uang_makan").prop('disabled', false);
            $("#insentif_penjualan").prop('disabled', false);
            $("#overtime").prop('disabled', false);
            $("#insentif_lainnya").prop('disabled', false);

            $("#submitButton").show();
            $("#formGaji").append('<input type="hidden" name="_method" value="PUT">');
            $("#formGaji").attr('action', '/{{ $role }}/gaji_bulanan/update/' + $(this).data('id'));
        })

        $(".showGaji").click(function(e){
            e.preventDefault();
            $(".modal-title").text('Lihat Gaji Bulanan');
            $(".editGajiDropdown").show();
            $(".tambahGajiDropdown").hide();

            $("#pegawai_id_edit").val($(this).data('pegawai_id'));
            $("#pegawai_nama").val($(this).data('pegawai_nama'));
            $("#gaji_pokok").val($(this).data('gaji_pokok'));

            $("#potongan_gaji_pokok").val($(this).data('potongan_gaji_pokok'));
            $("#potongan_uang_makan").val($(this).data('potongan_uang_makan'));
            $("#potongan_kinerja").val($(this).data('potongan_kinerja'));
            $("#potongan_keterlambatan").val($(this).data('potongan_keterlambatan'));
            $("#potongan_pajak").val($(this).data('potongan_pajak'));
            $("#potongan_bpjs_ketenagakerjaan").val($(this).data('potongan_bpjs_ketenagakerjaan'));
            $("#potongan_bpjs_kesehatan").val($(this).data('potongan_bpjs_kesehatan'));
            $("#potongan_kasbon").val($(this).data('potongan_kasbon'));
            $("#potongan_lainnya").val($(this).data('potongan_lainnya'));
            $("#keterangan_potongan_lainnya").val($(this).data('keterangan_potongan_lainnya'));
            
            $("#insentif_kinerja").val($(this).data('insentif_kinerja'));
            $("#insentif_uang_makan").val($(this).data('insentif_uang_makan'));
            $("#insentif_uang_bensin").val($(this).data('insentif_uang_bensin'));
            $("#insentif_penjualan").val($(this).data('insentif_penjualan'));
            $("#insentif_lainnya").val($(this).data('insentif_lainnya'));
            $("#overtime").val($(this).data('overtime'));
            $("#keterangan_insentif_lainnya").val($(this).data('keterangan_insentif_lainnya'));

            $("#pegawai_id").prop('disabled', true);
            $("#gaji_pokok").prop('disabled', true);
            $("#potongan_gaji_pokok").prop('disabled', true);
            $("#potongan_uang_makan").prop('disabled', true);
            $("#potongan_keterlambatan").prop('disabled', true);
            $("#potongan_kinerja").prop('disabled', true);
            $("#potongan_pajak").prop('disabled', true);
            $("#potongan_bpjs_ketenagakerjaan").prop('disabled', true);
            $("#potongan_bpjs_kesehatan").prop('disabled', true);
            $("#potongan_kasbon").prop('disabled', true);
            $("#potongan_lainnya").prop('disabled', true);
            $("#insentif_kinerja").prop('disabled', true);
            $("#insentif_uang_bensin").prop('disabled', true);
            $("#insentif_uang_makan").prop('disabled', true);
            $("#insentif_penjualan").prop('disabled', true);
            $("#insentif_lainnya").prop('disabled', true);
            $("#overtime").prop('disabled', true);

            $("#submitButton").hide();
        })
    })
    
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

    document.getElementById('applyFilter').addEventListener('click', function () {
        const year = document.getElementById('year').value;
        const month = document.getElementById('month').value;
        const baseUrl = `/{{ $role }}/gaji_bulanan`; // Bangun URL dinamis

        let queryParams = [];
        if (year) {
            queryParams.push(`year=${year}`);
        }
        if (month) {
            queryParams.push(`month=${month}`);
        }

        const queryString = queryParams.length > 0 ? `?${queryParams.join('&')}` : '';
        const finalUrl = baseUrl + queryString;

        // Redirect to the filtered URL
        window.location.href = finalUrl;
    });

    document.getElementById('alert-parameter').onclick = function () {
        const year = document.getElementById('year').textContent;
        const month = document.getElementById('month').value;
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success ms-2',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
        });

        swalWithBootstrapButtons.fire({
            title: 'Apakah anda Yakin ingin melakukan Sync Data?',
            text: "Aksi ini akan mengubah Data Akun Penggajian {{ $month_text }} {{ $year }} pada Sistem Bukukas!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Sync data!',
            cancelButtonText: 'Batalkan',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirect ke endpoint Laravel untuk verifikasi
                // window.location.href = "/admin_sdm/gaji_bulanan/sync/{{ $sync_id }}";
                window.location.href = `/{{ $role }}/gaji_bulanan/sync?year=${year}&month=${month}`;
            } 
            // Tidak perlu else karena cancel hanya menutup tanpa notif tambahan
        });
    }

</script>
@endsection