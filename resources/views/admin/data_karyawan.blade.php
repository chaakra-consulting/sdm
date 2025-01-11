@extends('layouts.main')

@section('content')
@php
     use Illuminate\Support\Facades\DB;
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

<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="staticBackdropLabel">Download Data
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            @csrf
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered text-nowrap w-100" id="table-karyawan">
                        <thead>
                            <tr>
                              <th rowspan="2">No</th>
                              <th rowspan="2">Nama Lengkap</th>
                              <th rowspan="2">NIP</th>
                              <th rowspan="2">Tempat, Tgl Lahir</th>
                              <th rowspan="2">Alamat Ktp</th>
                              <th rowspan="2">Alamat Domisili</th>
                              <th rowspan="2">Agama</th>
                              <th rowspan="2">Jenis Kelamin</th>
                              <th rowspan="2">No Hp</th>
                              <th rowspan="2">No Emergency</th>
                              <th rowspan="2">Email Non Chaakra</th>
                              <th rowspan="2">status pernikahan</th>
                              <th rowspan="2">Pendidikan Terakhir</th>
                              <th rowspan="2">Data Kesehatan</th>
                              <th rowspan="2">Pengalaman Kerja</th>
                              <th rowspan="2">Pelatihan</th>
                              <th colspan="5" style="text-align: center;">Kepegawaian</th>
                            </tr>
                            <tr>
                              <th>Jabatan</th>
                              <th>Status Pekerjaan</th>
                              <th>Tanggal Masuk</th>
                              <th>Tanggal Berakhir</th>
                              <th>No NPWP</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                    data-bs-dismiss="modal">Kembali</button>
                <button type="submit" class="btn btn-success btn-download-excel" hidden><i class="fas fa-download"></i> Unduh</button>
            </div>
        </div>
    </div>
</div>


<div class="container-fluid">
    <div class="d-flex justify-content-between mb-2">
        <a href="/ajax/get_karyawan" class="btn btn-success btn-download-karyawan"><i class="fas fa-file-excel"></i> Download</a>
    </div>
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
        $(".btn-download-karyawan").click(function(e){
            e.preventDefault();

            $("#staticBackdrop").modal('show');
            $("#table-karyawan tbody").empty();
            $.ajax({
                url: $(this).attr('href'),
                method: 'get',
                success: function(data){
                    const currentRows = $("#table-karyawan tbody tr").length;
                    if(currentRows < 0){
                        alert('Data kosong');
                    }else{
                        $(".btn-download-excel").prop('hidden', false);
                    }
                    console.log(data.count);
                    const pengalamanKerja = data.pengalaman_kerja.map(item => `${item.nama_perusahaan} (${item.jabatan_akhir}) - ${item.periode}`).join("<br>");
                    const pelatihan = data.pelatihan.map(item => `${item.nama_pelatihan} ( ${item.tahun_pelatihan} )`).join("<br>");
                    
                    const row = `
                    <tr>
                        <td>${currentRows + 1}</td>
                        <td>${data.data_diri.nama_lengkap}</td>
                        <td>${data.data_diri.nip}</td>
                        <td>${data.data_diri.tempat_lahir}, ${data.data_diri.tanggal_lahir}</td>
                        <td>${data.data_diri.alamat_ktp}</td>
                        <td>${data.data_diri.alamat_domisili}</td>
                        <td>${data.data_diri.agama}</td>
                        <td>${data.data_diri.jenis_kelamin}</td>
                        <td>${data.data_diri.no_hp}</td>
                        <td>${data.data_diri.no_emergency}</td>
                        <td>${data.data_diri.email_nonchaakra}</td>
                        <td>${data.data_diri.status_pernikahan}</td>
                        <td>${data.pendidikan_terakhir.nama_sekolah}, ${data.pendidikan_terakhir.jurusan_sekolah} [ ${data.pendidikan_terakhir.tahun_mulai} - ${data.pendidikan_terakhir.tahun_lulus} ]</td>
                        <td>
                            Golongan Darah : ${data.data_kesehatan.golongan_darah}<br>
                            Riwayat Alergi : ${data.data_kesehatan.riwayat_alergi}<br>
                            Riwayat Penyakit : ${data.data_kesehatan.riwayat_penyakit}<br>
                            Riwayat Penyakit Lain : ${data.data_kesehatan.riwayat_penyakit_lain}<br>
                        </td>
                        <td>${pengalamanKerja}</td>
                        <td>${pelatihan}</td>
                        <td>${data.kepegawaian.nama_sub_jabatan}</td>
                        <td>${data.kepegawaian.nama_status_pekerjaan}</td>
                        <td>${data.kepegawaian.tgl_masuk}</td>
                        <td>${data.kepegawaian.tgl_berakhir}</td>
                        <td>${data.kepegawaian.no_npwp}</td>
                    </tr>
                    `
                    $("#table-karyawan tbody").append(row);
                }
            })
        })

        $(".btn-download-excel").click(function(){
            $('#table-karyawan').table2excel({
                name: "Data Karyawan", // Nama worksheet di Excel
                filename: "data-karyawan.xls", // Nama file Excel
                fileext: ".xls", // Ekstensi file
                exclude_img: true, // Tidak menyertakan gambar
                exclude_links: true, // Tidak menyertakan tautan
                exclude_inputs: true // Tidak menyertakan input
            });
        })
    })
</script>
@endsection