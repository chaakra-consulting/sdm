@extends('layouts.main')

@section('content')
@php
     use Illuminate\Support\Facades\DB;
@endphp

<style>
    .card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.image-top-card img {
    width: 150px;
    opacity: 0.5; 
}

.badge.bg-success {
    margin-right: 10px;
}

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
    <div class="row">
       @foreach($data_diri as $key => $row)
        <div class="col-sm-3">
            <div class="card custom-card text-center">
                <div class="card-header d-flex align-items-center">
                    <span class="badge bg-success">
                        AKTIF
                    </span>
                    <div class="image-top-card ms-3">
                        <img src="{{ asset("Tema/dist/assets/images/media/logo.png") }}" alt="" style="width: 150px;">
                    </div>
                </div>
                <div class="card-body pt-1">
                    <span class="avatar avatar-xxl avatar-rounded me-2 mb-2">
                        <img src="{{ ($row->foto_user != null ? asset('uploads/' . $row->foto_user) : 'https://t4.ftcdn.net/jpg/02/15/84/43/360_F_215844325_ttX9YiIIyeaR7Ne6EaLLjMAmy4GvPC69.jpg') }}" alt="img" class="profile-img">
                    </span>
                    <div class="fw-medium fs-18" style="text-transform: capitalize;">{{ $row->nama_lengkap }}</div>
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
                    <p class="mb-4 text-muted fs-15">
                        {{ ($kepegawaian != null ? $kepegawaian->nama_sub_jabatan : 'Belum di tetapkan') }}
                        @if($kepegawaian == null)
                            <span class="text-danger msg-update">(Perlu Update)</span>
                        @endif
                    </p>
                    <div class="mb-4">
                        <table style="text-align: left;" class="fs-15">
                            <tr>
                                <th><i class="fas fa-map"></i></th>
                                <td class="px-2">:</td>
                                <td>{{ $row->alamat_domisili }}</td>
                            </tr>
                            <tr>
                                <th><i class="fas fa-user"></i></th>
                                <td class="px-2">:</td>
                                <td>{{ $row->jenis_kelamin }}</td>
                            </tr>
                            <tr>
                                <th><i class="fas fa-phone"></i></th>
                                <td class="px-2">:</td>
                                <td>{{ $row->no_hp }}</td>
                            </tr>
                            <tr>
                                <th><i class="fas fa-calendar"></i></th>
                                <td class="px-2">:</td>
                                <td>{{ $row->tanggal_lahir }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="btn-list">
                        <a href="/admin_sdm/detail_kepegawaian/{{ $row->id }}" class="btn btn-secondary">Detail</a>
                    </div>
                    <div class="background-corner"></div>
                </div>
            </div>
        </div>
       @endforeach
    </div>
</div>

@endsection

@section('script')
<script>
    $(document).ready(function(){
        
    })
</script>
@endsection