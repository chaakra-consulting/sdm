@extends('layouts.main')

@section('content')
@php
     use Carbon\Carbon;
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
</style>
<div class="container-fluid">
    <div class="d-flex justify-content-between mb-2">
        <a href="/report/excel-kepegawaian" class="btn btn-success btn-download-karyawan"><i class="fas fa-file-excel"></i> Download Excel</a>
    </div>
    <div class="card custom-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="card-title">
                Data Kepegawaian
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
                                    <th>NIP</th>
                                    <th>Nama Pegawai</th>
                                    <th>Jabatan</th>
                                    <th>Status Pekerjaan</th>
                                    <th>Tanggal Masuk</th>
                                    <th>Tanggal Keluar</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data_diri as $row)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>   
                                            <?php
                                            $kepegawaian = DB::table('data_kepegawaians')
                                                        ->select(
                                                            'data_kepegawaians.*', 
                                                        )
                                                        ->where('data_kepegawaians.user_id', $row->user_id)
                                                        ->first();
                                            ?>                     
                                            {{ ($kepegawaian != null ? $kepegawaian->nip : 'Belum di tetapkan') }}
                                            @if($kepegawaian == null)
                                                <span class="text-danger msg-update">(Perlu Update)</span>
                                            @endif</td>
                                        <td>{{ $row->nama_lengkap }}</td>
                                        <td>   
                                            <?php
                                            $kepegawaian = DB::table('data_kepegawaians')
                                                        ->select(
                                                            'data_kepegawaians.*', 
                                                            'sub_jabatans.id', 
                                                            'sub_jabatans.nama_sub_jabatan'
                                                        )
                                                        ->join('sub_jabatans', 'sub_jabatans.id', '=', 'data_kepegawaians.sub_jabatan_id')
                                                        ->where('data_kepegawaians.user_id', $row->user_id)
                                                        ->first();
                                            ?>                     
                                            {{ ($kepegawaian != null ? $kepegawaian->nama_sub_jabatan : 'Belum di tetapkan') }}
                                            @if($kepegawaian == null)
                                                <span class="text-danger msg-update">(Perlu Update)</span>
                                            @endif
                                        </td>
                                        <td>   
                                            <?php
                                            $kepegawaian = DB::table('data_kepegawaians')
                                                        ->select(
                                                            'data_kepegawaians.*', 
                                                            'data_status_pekerjaans.id', 
                                                            'data_status_pekerjaans.nama_status_pekerjaan'
                                                        )
                                                        ->join('data_status_pekerjaans', 'data_status_pekerjaans.id', '=', 'data_kepegawaians.status_pekerjaan_id')
                                                        ->where('data_kepegawaians.user_id', $row->user_id)
                                                        ->first();
                                            ?>                     
                                            {{ ($kepegawaian != null ? $kepegawaian->nama_status_pekerjaan : 'Belum di tetapkan') }}
                                            @if($kepegawaian == null)
                                                <span class="text-danger msg-update">(Perlu Update)</span>
                                            @endif
                                        </td>
                                        <td>
                                            <?php
                                            $kepegawaian = DB::table('data_kepegawaians')
                                                        ->where('data_kepegawaians.user_id', $row->user_id)
                                                        ->first();
                                            ?>     
                                            {{ ($kepegawaian != null ? Carbon::parse($kepegawaian->tgl_masuk)->format('d M Y') : '-') }}
                                        </td>
                                        <td>
                                            <?php
                                            $kepegawaian = DB::table('data_kepegawaians')
                                                        ->where('data_kepegawaians.user_id', $row->user_id)
                                                        ->first();
                                            ?>      
                                            {{ ($kepegawaian != null ? Carbon::parse($kepegawaian->tgl_berakhir)->format('d M Y')  : '-') }}
                                        </td>
                                        <td>
                                            <div class="btn-list">
                                                <a href="/{{ $role }}/detail_kepegawaian/{{ $row->id }}" class="btn btn-secondary"
                                                    data-bs-toggle="tooltip"
                                                    data-bs-placement="top" 
                                                    title="Kepegawaian">
                                                    <i class="bi bi-person-rolodex"></i>
                                                </a>
                                                <a href="/{{ $role }}/absensi_harian/{{ $row->id }}" class="btn btn-warning"
                                                    data-bs-toggle="tooltip"
                                                    data-bs-placement="top" 
                                                    title="Absensi">
                                                    <i class="bi bi-card-checklist"></i>
                                                </a>
                                            </div>
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
                                    <th>NIP</th>
                                    <th>Nama Pegawai</th>
                                    <th>Jabatan</th>
                                    <th>Status Pekerjaan</th>
                                    <th>Tanggal Masuk</th>
                                    <th>Tanggal Keluar</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data_diri_tidak_aktif as $row)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>   
                                            <?php
                                            $kepegawaian = DB::table('data_kepegawaians')
                                                        ->select(
                                                            'data_kepegawaians.*', 
                                                        )
                                                        ->where('data_kepegawaians.user_id', $row->user_id)
                                                        ->first();
                                            ?>                     
                                            {{ ($kepegawaian != null ? $kepegawaian->nip : 'Belum di tetapkan') }}
                                            @if($kepegawaian == null)
                                                <span class="text-danger msg-update">(Perlu Update)</span>
                                            @endif</td>
                                        <td>{{ $row->nama_lengkap }}</td>
                                        <td>   
                                            <?php
                                            $kepegawaian = DB::table('data_kepegawaians')
                                                        ->select(
                                                            'data_kepegawaians.*', 
                                                            'sub_jabatans.id', 
                                                            'sub_jabatans.nama_sub_jabatan'
                                                        )
                                                        ->join('sub_jabatans', 'sub_jabatans.id', '=', 'data_kepegawaians.sub_jabatan_id')
                                                        ->where('data_kepegawaians.user_id', $row->user_id)
                                                        ->first();
                                            ?>                     
                                            {{ ($kepegawaian != null ? $kepegawaian->nama_sub_jabatan : 'Belum di tetapkan') }}
                                            @if($kepegawaian == null)
                                                <span class="text-danger msg-update">(Perlu Update)</span>
                                            @endif
                                        </td>
                                        <td>   
                                            <?php
                                            $kepegawaian = DB::table('data_kepegawaians')
                                                        ->select(
                                                            'data_kepegawaians.*', 
                                                            'data_status_pekerjaans.id', 
                                                            'data_status_pekerjaans.nama_status_pekerjaan'
                                                        )
                                                        ->join('data_status_pekerjaans', 'data_status_pekerjaans.id', '=', 'data_kepegawaians.status_pekerjaan_id')
                                                        ->where('data_kepegawaians.user_id', $row->user_id)
                                                        ->first();
                                            ?>                     
                                            {{ ($kepegawaian != null ? $kepegawaian->nama_status_pekerjaan : 'Belum di tetapkan') }}
                                            @if($kepegawaian == null)
                                                <span class="text-danger msg-update">(Perlu Update)</span>
                                            @endif
                                        </td>
                                        <td>
                                            <?php
                                            $kepegawaian = DB::table('data_kepegawaians')
                                                        ->where('data_kepegawaians.user_id', $row->user_id)
                                                        ->first();
                                            ?>     
                                            {{ ($kepegawaian != null ? Carbon::parse($kepegawaian->tgl_masuk)->format('d M Y') : '-') }}
                                        </td>
                                        <td>
                                            <?php
                                            $kepegawaian = DB::table('data_kepegawaians')
                                                        ->where('data_kepegawaians.user_id', $row->user_id)
                                                        ->first();
                                            ?>      
                                            {{ ($kepegawaian != null ? Carbon::parse($kepegawaian->tgl_berakhir)->format('d M Y')  : '-') }}
                                        </td>
                                        <td>
                                            <div class="btn-list">
                                                <a href="/{{ $role }}/detail_kepegawaian/{{ $row->id }}" class="btn btn-secondary"
                                                    data-bs-toggle="tooltip"
                                                    data-bs-placement="top" 
                                                    title="Kepegawaian">
                                                    <i class="bi bi-person-rolodex"></i>
                                                </a>
                                                <a href="/{{ $role }}/absensi_harian/{{ $row->id }}" class="btn btn-warning"
                                                    data-bs-toggle="tooltip"
                                                    data-bs-placement="top" 
                                                    title="Absensi">
                                                    <i class="bi bi-card-checklist"></i>
                                                </a>
                                            </div>
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
</script>
@endsection