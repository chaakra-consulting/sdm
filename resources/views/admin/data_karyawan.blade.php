@extends('layouts.main')

@section('content')
@php
     use App\Models\SubJabatan; 
     use App\Models\User;
@endphp

<style>
    .card-header {
    display: flex;
    align-items: center; /* Posisikan elemen vertikal di tengah */
    justify-content: space-between; /* Badge dan gambar berjauhan */
}

.image-top-card img {
    width: 150px; /* Ukuran gambar */
    opacity: 0.5; /* Atur transparansi ke 5% */
}

.badge.bg-success {
    margin-right: 10px; /* Tambahkan jarak badge ke gambar jika diperlukan */
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
                        $getUser = User::where('id', $row->user_id)->first();
                        $getJabatan = null;
                        if($getUser->sub_jabatan_id != null){
                            $getJabatan = SubJabatan::where('id', $getUser->sub_jabatan_id)->first();
                        }else{
                            $getJabatan = null;
                        }
                    ?>
                    <p class="mb-4 text-muted fs-15">{{ ($getJabatan != null ? $getJabatan->nama_sub_jabatan : 'Belum di tetapkan') }}</p>
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
                        <a href="/admin/detail_karyawan/{{ $row->id }}" class="btn btn-secondary">Detail</a>
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