@extends('layouts.main')

@section('content')

<style>
    .form-label {
        font-weight: bold;
    }
    .icon-kedip {
        animation: blinkIcon 1s infinite;
    }

@keyframes blinkIcon {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0;
    }
}

</style>

{{-- Modal View Dokumen PDF --}}
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
    <div class="row row-sm">
        <div class="col-xl-4">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="ps-0">
                        <div class="main-profile-overview">
                            <span class="avatar avatar-xxl avatar-rounded main-img-user profile-user user-profile">
                                <img src="{{ ($karyawan->foto_user != null ? asset('uploads/' . $karyawan->foto_user) : 'https://t4.ftcdn.net/jpg/02/15/84/43/360_F_215844325_ttX9YiIIyeaR7Ne6EaLLjMAmy4GvPC69.jpg') }}" alt="" class="profile-img">
                            </span>
                            <div class="d-flex justify-content-between mb-4">
                                <div>
                                    <h5 class="main-profile-name" style="text-transform: capitalize;">{{ $karyawan->nama_lengkap }}</h5>
                                    <p class="main-profile-name-text text-muted fs-16">{{ ($kepegawaian != null ? $kepegawaian->nama_sub_jabatan : 'Belum di tetapkan') }}</p>
                                </div>
                            </div>            
                            <label class="main-content-label fs-15 mb-4">Data Kepegawaian</label>
                            @if($kepegawaian == null)
                                <div class="card border-0">
                                    <div class="alert custom-alert1 alert-warning">
                                        <div class="text-center px-5 pb-0">
                                            <svg class="custom-alert-icon svg-warning icon-kedip" xmlns="http://www.w3.org/2000/svg" height="1.5rem" viewBox="0 0 24 24" width="1.5rem" fill="#000000"><path d="M0 0h24v24H0z" fill="none"/><path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/></svg>
                                            <h5>Peringatan</h5>
                                            <p class="">Data kepegawaian perlu di update. Admin sdm belum mengupdate data ini.</p>
                                        </div>
                                    </div>
                                </div>
                            @else
                            <div class="fs-15">
                                <div class="form-group">
                                    <label for="sub_jabatan_id" class="form-label">Jabatan</label>
                                    <p>{{ $kepegawaian->nama_sub_jabatan }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="status_pekerjaan_id" class="form-label">Status Pekerjaan</label>
                                    <p>{{ $kepegawaian->nama_status_pekerjaan }}</p>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="tgl_masuk" class="form-label">Tanggal Masuk</label>
                                            <p class="tanggal_indo">{{ $kepegawaian->tgl_masuk }}</p>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="tgl_berakhir" class="form-label">Tanggal Berakhir</label>
                                            <p class="tanggal_indo">{{ $kepegawaian->tgl_berakhir }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="no_npwp" class="form-label">No NPWP</label>
                                    <p>{{ ($kepegawaian == null ? 'no npwp kosong' : $kepegawaian->no_npwp) }}</p>
                                </div> 
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-8">
            <div class="row row-sm">
                <div class="col-sm-12 col-lg-4 col-md-12">
                    <div class="card ">
                        <div class="card-body">
                            <div class="counter-status d-flex md-mb-0">
                                <div class="counter-icon bg-primary-transparent">
                                    <i class="icon-layers text-primary"></i>
                                </div>
                                <div class="ms-auto">
                                    <h5 class="fs-13">Orders</h5>
                                    <h2 class="mb-0 fs-22 mb-1 mt-1">1,587</h2>
                                    <p class="text-muted mb-0 fs-11"><i
                                            class="si si-arrow-up-circle text-success me-1"></i>increase</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-lg-4 col-md-12">
                    <div class="card ">
                        <div class="card-body">
                            <div class="counter-status d-flex md-mb-0">
                                <div class="counter-icon bg-danger-transparent">
                                    <i class="icon-paypal text-danger"></i>
                                </div>
                                <div class="ms-auto">
                                    <h5 class="fs-13">Revenue</h5>
                                    <h2 class="mb-0 fs-22 mb-1 mt-1">46,782</h2>
                                    <p class="text-muted mb-0 fs-11"><i
                                            class="si si-arrow-up-circle text-success me-1"></i>increase</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-lg-4 col-md-12">
                    <div class="card ">
                        <div class="card-body">
                            <div class="counter-status d-flex md-mb-0">
                                <div class="counter-icon bg-success-transparent">
                                    <i class="icon-rocket text-success"></i>
                                </div>
                                <div class="ms-auto">
                                    <h5 class="fs-13">Product sold</h5>
                                    <h2 class="mb-0 fs-22 mb-1 mt-1">1,890</h2>
                                    <p class="text-muted mb-0 fs-11"><i
                                            class="si si-arrow-up-circle text-success me-1"></i>increase</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="tabs-menu ">
                        <!-- Tabs -->
                        <ul class="nav nav-tabs profile navtab-custom panel-tabs">
                            <li class="">
                                <a href="#data_diri" data-bs-toggle="tab" class="active" aria-expanded="true"> <span
                                        class="visible-xs"><i
                                            class="las la-user-circle fs-16 me-1"></i></span> <span
                                        class="hidden-xs">DATA DIRI</span> </a>
                            </li>
                            <li class="">
                                <a href="#kesehatan" data-bs-toggle="tab" aria-expanded="true"> <span
                                        class="visible-xs"><i
                                            class="las la-user-circle fs-16 me-1"></i></span> <span
                                        class="hidden-xs">DATA KESEHATAN</span> </a>
                            </li>
                            <li class="">
                                <a href="#pendidikan" data-bs-toggle="tab" aria-expanded="true"> <span
                                        class="visible-xs"><i
                                            class="las la-user-circle fs-16 me-1"></i></span> <span
                                        class="hidden-xs">PENDIDIKAN TERAKHIR</span> </a>
                            </li>
                            <li class="">
                                <a href="#pengalaman_kerja" data-bs-toggle="tab" aria-expanded="false"> <span
                                        class="visible-xs"><i class="las la-images fs-15 me-1"></i></span>
                                    <span class="hidden-xs">PENGALAMAN KERJA</span> </a>
                            </li>
                            <li class="">
                                <a href="#pelatihan" data-bs-toggle="tab" aria-expanded="false"> <span
                                        class="visible-xs"><i class="las la-life-ring fs-16 me-1"></i></span>
                                <span class="hidden-xs">PELATIHAN</span></a>
                            </li>
                            <li class="">
                                <a href="#social" data-bs-toggle="tab" aria-expanded="false"> <span
                                        class="visible-xs"><i class="las la-life-ring fs-16 me-1"></i></span>
                                <span class="hidden-xs">Sosial Media</span></a>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content border border-top-0 p-4 br-dark">
                        <div class="tab-pane border-0 p-0 active" id="data_diri">
                            <table style="text-align: left;" class="fs-18">
                                <tr>
                                    <th>Foto KTP</th>
                                    <td class="px-2">:</td>
                                    <td>
                                        <span class="main-img">
                                            <img src="{{ ($karyawan->foto_ktp != null ? asset('uploads/' . $karyawan->foto_ktp) : 'https://t4.ftcdn.net/jpg/02/15/84/43/360_F_215844325_ttX9YiIIyeaR7Ne6EaLLjMAmy4GvPC69.jpg') }}" alt="" class="img-thumbnail rounded w-50">
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Nama Lengkap</th>
                                    <td class="px-2">:</td>
                                    <td>{{ $karyawan->nama_lengkap }}</td>
                                </tr>
                                <tr>
                                    <th>NIP</th>
                                    <td class="px-2">:</td>
                                    <td>{{ $karyawan->nip }}</td>
                                </tr>
                                <tr>
                                    <th>Tempat Lahir</th>
                                    <td class="px-2">:</td>
                                    <td>{{ $karyawan->tempat_lahir }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Lahir</th>
                                    <td class="px-2">:</td>
                                    <td class="tanggal_indo">{{ $karyawan->tanggal_lahir }}</td>
                                </tr>
                                <tr>
                                    <th>Alamat KTP</th>
                                    <td class="px-2">:</td>
                                    <td>{{ $karyawan->alamat_ktp }}</td>
                                </tr>
                                <tr>
                                    <th>Alamat Domisili</th>
                                    <td class="px-2">:</td>
                                    <td>{{ $karyawan->alamat_domisili }}</td>
                                </tr>
                                <tr>
                                    <th>Agama</th>
                                    <td class="px-2">:</td>
                                    <td>{{ $karyawan->agama }}</td>
                                </tr>
                                <tr>
                                    <th>Jenis Kelamin</th>
                                    <td class="px-2">:</td>
                                    <td>{{ $karyawan->jenis_kelamin }}</td>
                                </tr>
                                <tr>
                                    <th>No Hp</th>
                                    <td class="px-2">:</td>
                                    <td>{{ $karyawan->no_hp }}</td>
                                </tr>
                                <tr>
                                    <th>No Emergency</th>
                                    <td class="px-2">:</td>
                                    <td>{{ $karyawan->no_emergency }}</td>
                                </tr>
                                <tr>
                                    <th>Nama Emergency</th>
                                    <td class="px-2">:</td>
                                    <td>{{ $karyawan->nama_emergency }} ( {{ $karyawan->hubungan_emergency }} )</td>
                                </tr>
                                <tr>
                                    <th>Email Non Chaakra</th>
                                    <td class="px-2">:</td>
                                    <td>{{ $karyawan->email_nonchaakra }}</td>
                                </tr>
                                <tr>
                                    <th>Status Pernikahan</th>
                                    <td class="px-2">:</td>
                                    <td style="text-transform: capitalize;">{{ $karyawan->status_pernikahan }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="tab-pane border-0 p-0" id="kesehatan">
                           @if(!$kesehatan)
                           <div class="alert alert-danger">Data belum dilengkapi</div>
                           @else
                           <table style="text-align: left;" class="fs-18">
                            <tr>
                                <th>Golongan Darah</th>
                                <td class="px-2">:</td>
                                <td>{{ $kesehatan->golongan_darah }}</td>
                            </tr>
                            <tr>
                                <th>Riwayat Alergi</th>
                                <td class="px-2">:</td>
                                <td>{{ $kesehatan->riwayat_alergi }}</td>
                            </tr>
                            <tr>
                                <th>Riwayat Penyakit</th>
                                <td class="px-2">:</td>
                                <td>{{ $kesehatan->riwayat_penyakit }}</td>
                            </tr>
                            <tr>
                                <th>Riwayat Penyakit lain</th>
                                <td class="px-2">:</td>
                                <td>{{ $kesehatan->riwayat_penyakit_lain }}</td>
                            </tr>
                        </table>
                        @endif
                        </div>
                        <div class="tab-pane border-0 p-0" id="pendidikan">
                            @if(!$pendidikan)
                            <div class="alert alert-danger">Data belum dilengkapi</div>
                            @else
                            <table style="text-align: left;" class="fs-18">
                                <tr>
                                    <th>Nama Sekolah</th>
                                    <td class="px-2">:</td>
                                    <td>{{ $pendidikan->nama_sekolah }}</td>
                                </tr>
                                <tr>
                                    <th>Jurusan</th>
                                    <td class="px-2">:</td>
                                    <td>{{ $pendidikan->jurusan_sekolah }}</td>
                                </tr>
                                <tr>
                                    <th>Alamat Sekolah</th>
                                    <td class="px-2">:</td>
                                    <td>{{ $pendidikan->alamat_sekolah }}</td>
                                </tr>
                                <tr>
                                    <th>Tahun Masuk</th>
                                    <td class="px-2">:</td>
                                    <td>{{ $pendidikan->tahun_mulai }}</td>
                                </tr>
                                <tr>
                                    <th>Tahun Lulus</th>
                                    <td class="px-2">:</td>
                                    <td>{{ $pendidikan->tahun_lulus }}</td>
                                </tr>
                            </table>
                            @endif
                        </div>
                        <div class="tab-pane border-0 p-0" id="pengalaman_kerja">
                            <div class="table-responsive">
                                <table class="table table-bordered text-nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Perusahaan</th>
                                            <th>Periode</th>
                                            <th>Jabatan Akhir</th>
                                            <th>Alasan Keluar</th>
                                            <th>Dokumen</th>
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
                                                <td>
                                                    @if($row->upload_surat_referensi != null)
                                                    <a href="" class="btn btn-danger btnViewDokumenPdf" data-tipe_dokumen="pengalaman_kerja" data-title_dokumen="{{ $row->nama_perusahaan }}" data-dokumen_pdf="{{ asset('uploads/'. $row->upload_surat_referensi) }}">View Dokumen</a>
                                                    @else
                                                    <p class="text-danger">Tidak ada file</p>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane border-0 p-0" id="pelatihan" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-bordered text-nowrap w-100">  
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Pelatihan</th>
                                            <th>Tujuan Pelatihan</th>
                                            <th>Tahun Pelatihan</th>
                                            <th>Nomor Sertifikat</th>
                                            <th>Dokumen</th>
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
                                                    <a href="" class="btn btn-danger btnViewDokumenPdf" data-tipe_dokumen="pelatihan" data-title_dokumen="{{ $row->nama_pelatihan }}" data-dokumen_pdf="{{ asset('uploads/'. $row->upload_sertifikat) }}">View Dokumen</a>
                                                    @else
                                                    <p class="text-danger">Tidak ada file</p>
                                                    @endif

                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane border-0 p-0" id="social">
                            <div class="main-profile-social-list">
                                @foreach ($social_media as $item)
                                    <div class="media">
                                        <div class="media-icon bg-primary-transparent text-black">
                                            <i class="icon ion-logo-{{ $item->nama_social_media }}"></i>
                                        </div>
                                        <div class="media-body">
                                            <span>{{ $item->nama_social_media }}</span> <a href="{{ $item->link }}" class="text-primary">{{ $item->link }}</a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="mt-1">
        <a href="/admin/data_karyawan" class="btn btn-secondary">Kembali</a>
    </div>
</div>



@endsection

@section('script')
<script>
    $(document).ready(function(){  
        $('.btnViewDokumenPdf').click(function(e){
            e.preventDefault();
            $('#staticBackdropViewDokumenPdf').modal('show');
            let tipe_dokumen = $(this).data('tipe_dokumen')
            let title = '';
            if(tipe_dokumen == 'pengalaman_kerja'){
                title = 'Surat Referensi'
            }else if(tipe_dokumen == 'pelatihan'){
                title = 'Sertifikat';
            }
            $("#staticBackdropViewDokumenPdf .modal-title").text(title + ' : ' + $(this).data('title_dokumen'));

            let dokumen_pdf = $(this).data('dokumen_pdf');
            console.log(dokumen_pdf);
            $('#viewDokumenPdf').attr('src', dokumen_pdf);
        })
    })
</script>
@endsection