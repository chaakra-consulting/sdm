@extends('layouts.main')

@section('content')
@php
    use Carbon\Carbon;
    use App\Models\AbsensiHarian;
@endphp
<style>
    .msg-update {
        font-weight: bold;
        animation: fadeBlink 2s infinite; /* Animasi fade in-out */
    }
    /* Animasi fade in-out */
    @keyframes fadeBlink {
        0%, 100% {
            opacity: 1; /* Teks terlihat penuh */
        }
        50% {
            opacity: 0.3; /* Teks memudar sepenuhnya */
        }
    }
    .form-control[readonly] {
        background-color: #f5f5f5; /* Warna abu-abu terang */
        color: #6c757d; /* Warna teks abu-abu */
        border-color: #ced4da; /* Warna border */
    }
</style>
<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="staticBackdropLabel">Modal title
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <form action="" method="POST" id="formDetailAbsensiHarian" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tanggal_kerja">Tanggal Kerja</label>
                            <input type="date" name="tanggal_kerja" id="tanggal_kerja" class="form-control" readonly required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="hari_kerja">Hari Kerja</label>
                            <input type="text" name="hari_kerja" id="hari_kerja" class="form-control" readonly required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group" id="waktuMasukGroup">
                            <label for="waktu_masuk">Waktu Masuk</label>
                            <input type="time" name="waktu_masuk" id="waktu_masuk" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group" id="waktuPulangGroup">
                            <label for="waktu_pulang">Waktu Pulang</label>
                            <input type="time" name="waktu_pulang" id="waktu_pulang" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="keterangan_id" class="form-label">Keterangan</label>
                        <select name="keterangan_id" id="keterangan_id" class="form-select">
                            <option selected disabled>Pilih Keterangan</option>
                            @foreach($keterangan_absensi as $row)
                                <option      
                                    value="{{ $row->id }}" 
                                    data-slug="{{ $row->slug }}"
                                    {{ old('keterangan_id') == $row->id ? 'selected' : (empty(old('keterangan_id')) && $row->slug == 'wfo' ? 'selected' : '') }}>
                                    {{ $row->nama }}
                                </option>
                            @endforeach
                        </select>               
                    </div>                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="keterangan">Note</label>
                            <input type="text" name="keterangan" id="keterangan" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6" id="durasiLemburContainer" style="display: none;">
                        <div class="form-group">
                            <label for="durasi_lembur">Durasi Lembur (Jam)</label>
                            <input type="number" name="durasi_lembur" id="durasi_lembur" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="upload_surat_dokter" class="form-label">Surat Pendukung</label>
                        <input type="file" class="form-control" id="upload_surat_dokter" name="upload_surat_dokter" accept="image/*"
                            onchange="validateFile(this, 'preview_surat', 'error_surat')">
                        <!-- Preview gambar akan diupdate setelah pengguna memilih file -->
                        <img id="preview_surat" alt="Preview Foto KTP"
                            style="max-width: 150px; margin-top: 10px; display: none;">
                        <small id="error_surat" class="text-danger" style="display: none;"></small>
                    </div>
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

<div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="filterModalLabel">Filter Data Absensi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="filterForm">
                    <!-- Pilih Tahun -->
                    <div class="mb-3">
                        <label for="filter-year" class="form-label">Pilih Tahun:</label>
                        <select id="filter-year" class="form-control">
                            <option value="">Semua Tahun</option>
                            @for ($year = now()->year; $year >= 2019; $year--)
                                <option value="{{ $year }}"
                                    {{ $year == $filter_year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <!-- Pilih Bulan -->
                    <div class="mb-3">
                        <label for="filter-month" class="form-label">Pilih Bulan:</label>
                        <select id="filter-month" class="form-control">
                            <option value="">Semua Bulan</option>
                            @foreach ([
                                '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
                                '04' => 'April', '05' => 'Mei', '06' => 'Juni',
                                '07' => 'Juli', '08' => 'Agustus', '09' => 'September',
                                '10' => 'Oktober', '11' => 'November', '12' => 'Desember',
                            ] as $num => $month)
                                <option value="{{ $num }}"
                                    {{ $num == $filter_month ? 'selected' : '' }}>
                                    {{ $month }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>
                <!-- Pesan error akan ditampilkan di sini -->
                <div id="filterErrorContainer" class="text-danger mt-2" style="display: none;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="applyFilter">Terapkan</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="verifikasiModal" tabindex="-1" aria-labelledby="exampleModalScrollable2" data-bs-keyboard="false" aria-hidden="true">
    <!-- Scrollable modal -->
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="staticBackdropLabel2">Modal title
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah anda yakin ingin melakukan verifikasi?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                    data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save
                    Changes</button>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    @if(in_array($role,['admin_sdm','direktur']))
    <div class="mt-1">
        <a href="/{{ $role }}/kepegawaian" class="btn btn-secondary">
            <i class="bi bi-arrow-left">Kembali</i>
        </a>
    </div>
    <br>
    @endif
    <div class="card custom-card border">
        {{-- <a href="javascript:void(0);" class="card-anchor"></a> --}}
        <div class="card-body">
            <div class="d-flex align-items-center">
                <div class="me-3">
                    <span class="avatar avatar-xl">
                        <img src="{{ asset('uploads/' . $foto_user) }}" alt="img">
                    </span>
                </div>
                <div>
                    <p class="card-text mb-1 fs-14 fw-semibold">{{ $nama }}</p>
                    <div class="card-title fs-12 mb-1">{{ $nip }}</div>
                    <div class="card-title text-muted fs-11 mb-1">{{ $jabatan }} / {{ $divisi }}</div>
                    @if($verifikasi == 'Terverifikasi')
                        <div class="card-title fs-12 mb-1">
                            Absensi Bulan {{ $month_text }} : <span class="text-success">{{ $verifikasi }}</span>
                        </div> 
                    @else
                        <div class="card-title fs-12 mb-1">
                            Absensi Bulan {{ $month_text }} : 
                            <span class="text-danger">{{ $verifikasi }}</span>
                            @if($role=='admin_sdm')
                            <button class="btn btn-sm btn-success ms-2" id="alert-parameter">Verifikasi?</button>  
                            @endif 
                        </div>                                                                                        
                    @endif
                </div>    
            </div>
        </div>
        <div class="card-body">
            <form action="" method="GET" class="ms-auto" style="max-width: 400px;">
                <div class="row g-3 align-items-end">
                    <div class="col-md-8">
                        <div class="input-group">
                            <span class="input-group-text"><i class="ri-calendar-line"></i></span>
                            <input type="text" class="form-control" id="date_range" name="date_range" value="{{ old('date_range', $default_range) }} placeholder="Pilih Range Tanggal">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" id="applyFilter" class="btn btn-primary w-100">Filter</button>
                    </div>
                </div>
            </form>
        </div>        
        <div class="container">
            <div class="row">             
                <div class="col-lg-6">
                    <div class="card bg-success-gradient text-fixed-white">
                        <div class="card-body text-fixed-white">
                            <div class="row">
                                    <div class="mt-0 text-center">
                                        <span class="text-fixed-white">{{ $widget[0]->nama }}</span>
                                        <h3 class="text-fixed-white mb-0">{{ $widget[0]->count }}</h3>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card bg-warning-gradient text-fixed-white">
                        <div class="card-body text-fixed-white">
                            <div class="row">
                                    <div class="mt-0 text-center">
                                        <span class="text-fixed-white">{{ $widget[1]->nama }}</span>
                                        <h3 class="text-fixed-white mb-0">{{ $widget[1]->count }}</h3>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-2">
                    <div class="card bg-danger-gradient text-fixed-white">
                        <div class="card-body text-fixed-white">
                            <div class="row">
                                <div class="mt-0 text-center">
                                    <span class="text-fixed-white">{{ $widget[2]->nama }}</span>
                                    <h3 class="text-fixed-white mb-0">{{ $widget[2]->count }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="card bg-primary-gradient text-fixed-white">
                        <div class="card-body text-fixed-white">
                            <div class="row">
                                <div class="mt-0 text-center">
                                    <span class="text-fixed-white">{{ $widget[3]->nama }}</span>
                                    <h3 class="text-fixed-white mb-0">{{ $widget[3]->count }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="card bg-teal-gradient text-fixed-white">
                        <div class="card-body text-fixed-white">
                            <div class="row">
                                <div class="mt-0 text-center">
                                    <span class="text-fixed-white">{{ $widget[4]->nama }}</span>
                                    <h3 class="text-fixed-white mb-0">{{ $widget[4]->count }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="card bg-purple-gradient text-fixed-white">
                        <div class="card-body text-fixed-white">
                            <div class="row">
                                <div class="mt-0 text-center">
                                    <span class="text-fixed-white">{{ $widget[5]->nama }}</span>
                                    <h3 class="text-fixed-white mb-0">{{ $widget[5]->count }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="card bg-dark-gradient text-fixed-white">
                        <div class="card-body text-fixed-white">
                            <div class="row">
                                <div class="mt-0 text-center">
                                    <span class="text-fixed-white">{{ $widget[6]->nama }}</span>
                                    <h3 class="text-fixed-white mb-0">{{ $widget[6]->count }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="card bg-secondary-gradient text-fixed-white">
                        <div class="card-body text-fixed-white">
                            <div class="row">
                                <div class="mt-0 text-center">
                                    <span class="text-fixed-white">{{ $widget[7]->nama }}</span>
                                    <h3 class="text-fixed-white mb-0">{{ $widget[7]->count }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    {{-- </div>
    <div class="card custom-card"> --}}
        {{-- <div class="card-header d-flex justify-content-between align-items-center">
            <div class="card-title">
                Data Absensi Harian Bulan {{ $month_text }} Tahun {{ $filter_year }}
            </div>
            <button class="btn btn-primary " data-bs-toggle="modal" data-bs-target="#filterModal">
                Filter
            </button>
        </div> --}}
        <div class="card-body">
            <div class="table-responsive">
                <table
                    id="datatable-basic-month"
                    class="table table-bordered text-nowrap w-100">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Hari Kerja</th>
                            <th>Waktu Masuk</th>
                            <th>Waktu Pulang</th>
                            <th>Status Keterlambatan</th>
                            <th>Keterangan</th>
                            <th>Note</th>
                            <th>Durasi Lembur</th>
                            <th>File</th>
                            @if($role == 'admin_sdm')
                            <th>Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($absensi_harian as $row)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $row->tanggal ? Carbon::parse($row->tanggal)->format('d M Y') : '-' }}</td>
                                <td>{{ ($row->hari ? $row->hari : '-') }}</td>
                                <td>{{ ($row->absensi && $row->absensi->waktu_masuk  ? $row->absensi->waktu_masuk : '-') }}</td>
                                <td>{{ ($row->absensi && $row->absensi->waktu_pulang  ? $row->absensi->waktu_pulang : '-') }}</td>
                                <td>
                                    @if ($row->absensi)
                                        @if ($row->absensi->status_keterlambatan == 'Terlambat')
                                            <span style="color: red;">{{ $row->absensi->status_keterlambatan }}</span>
                                        @elseif ($row->absensi->status_keterlambatan == 'Tidak Terlambat')
                                            <span style="color: green;">{{ $row->absensi->status_keterlambatan }}</span>
                                        @else
                                            {{ $row->absensi->status_keterlambatan }}
                                        @endif
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ ($row->absensi && $row->absensi->keterangan_absensi  ? $row->absensi->keterangan_absensi : '-') }}</td>
                                <td>{{ ($row->absensi && $row->absensi->keterangan  ? $row->absensi->keterangan : '-') }}</td>
                                <td>{{ ($row->absensi && $row->absensi->durasi_lembur  ? $row->absensi->durasi_lembur.' Jam' : '-') }}</td>
                                <td>
                                    @if ($row->absensi && $row->absensi->upload_surat_dokter)
                                        <a href="{{ asset('uploads/'.$row->absensi->upload_surat_dokter) }}" target="_blank" style="color: blue; text-decoration: underline;">
                                            Lihat File
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>
                                @if($role == 'admin_sdm')
                                <td>
                                    <div class="btn-list">
                                        <a href="" 
                                        class="btn btn-warning {{ $row->absensi ? 'editDetailAbsensiHarian' : 'tambahDetailAbsensiHarian' }}" 
                                        data-bs-toggle="modal"
                                        data-bs-target="#staticBackdrop"
                                        data-tanggal_kerja="{{ $row->tanggal }}" 
                                        data-hari_kerja="{{ $row->hari }}"
                                        data-absensi_id="{{ $row->absensi && $row->absensi->id ? $row->absensi->id : '' }}"
                                        data-waktu_masuk="{{ $row->absensi && $row->absensi->waktu_masuk ? $row->absensi->waktu_masuk : '' }}"
                                        data-waktu_pulang="{{ $row->absensi && $row->absensi->waktu_pulang ? $row->absensi->waktu_pulang : '' }}"
                                        data-keterangan_id="{{ $row->absensi && $row->absensi->keterangan_id ? $row->absensi->keterangan_id : '' }}"
                                        data-keterangan="{{ $row->absensi && $row->absensi->keterangan ? $row->absensi->keterangan : '' }}"
                                        data-durasi_lembur="{{ $row->absensi && $row->absensi->durasi_lembur ? $row->absensi->durasi_lembur : '' }}"
                                        data-upload_surat_dokter="{{ $row->absensi && $row->absensi->upload_surat_dokter ? asset('uploads/'.$row->absensi->upload_surat_dokter) : '' }}"
                                        ><i class="fas fa-edit"></i></a>                                    
                                        <form action="/admin_sdm/absensi_harian/delete/{{ $row->absensi && $row->absensi->id ? $row->absensi->id : '' }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button 
                                                type="submit" 
                                                id="deleteButton" 
                                                class="btn btn-danger"
                                                onclick="return confirm('Hapus Data?')"
                                                data-id="{{ $row->absensi->id ?? '' }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>                                        
                                    </div>
                                </td>
                                @endif
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
    $('#datatable-basic-month').DataTable({
        "pageLength": 31,
        "dom": 'ftip',// fp // Menyertakan search box (f), tabel (t), informasi (i), dan pagination (p)
        "language": {
            "paginate": {
                "first": "First",
                "last": "Last",
                "next": "Next",
                "previous": "Previous"
            },
            "info": "Menampilkan _START_ hingga _END_ dari _TOTAL_ data",
            "search": "Search:"
        }
    });

    document.getElementById('applyFilter').addEventListener('click', function () {
        const year = document.getElementById('filter-year').value;
        const month = document.getElementById('filter-month').value;
        const dateRange = document.getElementById('date_range').value;
        const id = "{{ $pegawai_id }}"; // Ambil id dari Blade variable
        const baseUrl = `/admin_sdm/absensi_harian/${id}`; // Bangun URL dinamis

        let queryParams = [];
        if (year) {
            queryParams.push(`year=${year}`);
        }
        if (month) {
            queryParams.push(`month=${month}`);
        }
        if (dateRange) {
            queryParams.push(`date_range=${dateRange}`);
        }

        const queryString = queryParams.length > 0 ? `?${queryParams.join('&')}` : '';
        const finalUrl = baseUrl + queryString;

        // Redirect to the filtered URL
        window.location.href = finalUrl;
    });

    let id = "{{ $pegawai_id }}";
    $(document).ready(function () {
        // Handler untuk tambah detail absensi
        $(".tambahDetailAbsensiHarian").click(function (e) {
            e.preventDefault();

            // Reset semua nilai dalam modal
            $("#formDetailAbsensiHarian").trigger('reset'); // Reset semua input form
            $(".modal-title").text('Tambah Detail Absensi');

            // Set nilai default jika tersedia
            $("#tanggal_kerja").val($(this).data('tanggal_kerja'));
            $("#hari_kerja").val($(this).data('hari_kerja'));

            // Bersihkan input hidden jika ada dari sebelumnya
            $("#formDetailAbsensiHarian input[name='_method']").remove();

            // Set action form untuk tambah
            $("#formDetailAbsensiHarian").attr('action', '/admin_sdm/absensi_harian/store/' + id);
        });

        $(".editDetailAbsensiHarian").click(function(e){
            e.preventDefault();
            $(".modal-title").text('Edit Detail Absensi');

            // $("#pegawai_id").val($(this).data('pegawai_id'));
            $("#tanggal_kerja").val($(this).data('tanggal_kerja'));
            $("#hari_kerja").val($(this).data('hari_kerja'));
            $("#waktu_masuk").val($(this).data('waktu_masuk'));
            $("#waktu_pulang").val($(this).data('waktu_pulang'));
            $("#keterangan_id").val($(this).data('keterangan_id'));
            $("#keterangan").val($(this).data('keterangan'));
            $("#durasi_lembur").val($(this).data('durasi_lembur'));


            // Tambahkan input hidden untuk method spoofing
            if ($("#formDetailAbsensiHarian input[name='_method']").length === 0) {
                $("#formDetailAbsensiHarian").append('<input type="hidden" name="_method" value="PUT">');
            }
            $("#formDetailAbsensiHarian").attr('action', '/admin_sdm/absensi_harian/update/'+ id +'/' + $(this).data('absensi_id'));
        });
    })

    document.querySelectorAll('button.btn-danger').forEach((button) => {
        const absensiId = button.getAttribute('data-id'); // Ambil ID dari atribut data
        if (!absensiId || absensiId === 'null') {
            button.disabled = true; // Disabled jika ID kosong
        } else {
            button.disabled = false; // Enable jika ID ada
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('staticBackdrop');
    const keteranganSelect = document.getElementById('keterangan_id');
    const waktuMasukInput = document.getElementById('waktu_masuk');
    const waktuPulangInput = document.getElementById('waktu_pulang');
    const durasiLemburInput = document.getElementById('durasi_lembur');
    const durasiLemburContainer = document.getElementById('durasiLemburContainer');
    const waktuMasukGroup = document.getElementById('waktuMasukGroup');
    const waktuPulangGroup = document.getElementById('waktuPulangGroup');

    // Fungsi untuk mengatur form berdasarkan data dari tombol yang diklik
    function handleButtonClick(event) {
        const button = event.target.closest('a'); // Tangkap tombol yang diklik
        if (!button) return;

        // Ambil nilai data-* dari tombol
        const keteranganId = button.getAttribute('data-keterangan_id');
        const waktuMasuk = button.getAttribute('data-waktu_masuk');
        const waktuPulang = button.getAttribute('data-waktu_pulang');
        const durasiLembur = button.getAttribute('data-durasi_lembur');

        // Set nilai form
        if (keteranganId) keteranganSelect.value = keteranganId;
        if (waktuMasuk) waktuMasukInput.value = waktuMasuk;
        if (waktuPulang) waktuPulangInput.value = waktuPulang;
        if (durasiLembur) durasiLemburInput.value = durasiLembur;

        // Trigger fungsi untuk memperbarui tampilan form
        updateFormDisplay();
    }

    // Fungsi untuk memperbarui tampilan berdasarkan keterangan
    function updateFormDisplay() {
        const selectedOption = keteranganSelect.options[keteranganSelect.selectedIndex];
        const slug = selectedOption ? selectedOption.getAttribute('data-slug') : '';

        // Hilangkan waktu jika slug termasuk kategori tertentu
        if (['wfh', 'cuti', 'ijin', 'sakit', 'alpa'].includes(slug)) {
        // Kosongkan nilai input dan nonaktifkan
            waktuMasukInput.value = '';
            waktuPulangInput.value = '';
            waktuMasukInput.disabled = true;
            waktuPulangInput.disabled = true;
        } else {
            // Aktifkan kembali jika diperlukan
            waktuMasukInput.disabled = false;
            waktuPulangInput.disabled = false;
        }

        // Tampilkan durasi lembur jika slug adalah 'lembur'
        if (slug === 'lembur') {
            durasiLemburContainer.style.display = 'block';
        } else {
            durasiLemburContainer.style.display = 'none';
            durasiLemburInput.value = ''; // Reset nilai
        }
    }

    // Listener untuk tombol tambah/edit
    document.addEventListener('click', function (event) {
        if (
            event.target.closest('.editDetailAbsensiHarian') ||
            event.target.closest('.tambahDetailAbsensiHarian')
        ) {
            handleButtonClick(event);
        }
    });

    // Listener untuk perubahan pada select keterangan
    keteranganSelect.addEventListener('change', updateFormDisplay);
});


    // document.addEventListener("DOMContentLoaded", function () {
    //     const keteranganDropdown = document.getElementById("keterangan_id");
    //     const durasiLemburContainer = document.getElementById("durasiLemburContainer");

    //     keteranganDropdown.addEventListener("change", function () {
    //         const selectedOption = keteranganDropdown.options[keteranganDropdown.selectedIndex];
    //         const selectedSlug = selectedOption.getAttribute("data-slug");

    //         if (selectedSlug === "lembur") {
    //             durasiLemburContainer.style.display = "block";
    //         } else {
    //             durasiLemburContainer.style.display = "none";
    //         }
    //     });

    //     const selectedOption = keteranganDropdown.options[keteranganDropdown.selectedIndex];
    //     const selectedSlug = selectedOption?.getAttribute("data-slug");
    //     if (selectedSlug === "lembur") {
    //         durasiLemburContainer.style.display = "block";
    //     } else {
    //         durasiLemburContainer.style.display = "none";
    //     }
    // });

    function validateFile(input, previewId, errorId) {
        const file = input.files[0];
        const maxSize = 2 * 1024 * 1024;
        const errorElement = document.getElementById(errorId);
        const previewElement = document.getElementById(previewId);

        // Reset pesan error dan preview gambar
        errorElement.style.display = 'none';
        errorElement.textContent = '';
        previewElement.style.display = 'none';
        previewElement.src = '';

        if (file) {
            if (file.size > maxSize) {
                // Jika ukuran file lebih dari 2MB
                errorElement.style.display = 'block';
                errorElement.textContent = 'Ukuran file tidak boleh lebih dari 2MB.';
                input.value = ''; // Reset input file
            } else {
                // Jika ukuran file valid, tampilkan preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewElement.style.display = 'block'; // Tampilkan elemen gambar
                    previewElement.src = e.target.result; // Setel sumber gambar
                };
                reader.readAsDataURL(file);
            }
        }
    }

    flatpickr("#date_range", {
        mode: "range",
        dateFormat: "Y-m-d",
        allowInput: true
    });

    document.getElementById('alert-parameter').onclick = function () {
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success ms-2',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
        });

        swalWithBootstrapButtons.fire({
            title: 'Apakah anda Yakin ingin melakukan verifikasi?',
            // text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Verifikasi!',
            cancelButtonText: 'Batalkan',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirect ke endpoint Laravel untuk verifikasi
                window.location.href = "/admin_sdm/absensi_verifikasi/store/{{ $pegawai_id }}";
            } 
            // Tidak perlu else karena cancel hanya menutup tanpa notif tambahan
        });
    }
</script>
@endsection