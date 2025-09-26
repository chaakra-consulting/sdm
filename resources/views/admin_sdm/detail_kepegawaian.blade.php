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
    <div class="mt-1">
        <a href="/{{ $role }}/kepegawaian" class="btn btn-secondary">
            <i class="bi bi-arrow-left">Kembali</i>
        </a>
    </div>
    <br>
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
                            @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                            @if($kepegawaian == null)
                            <div class="container-peringatan">
                                <div class="card border-0">
                                    <div class="alert custom-alert1 alert-warning">
                                        <div class="text-center px-5 pb-0">
                                            <svg class="custom-alert-icon svg-warning icon-kedip" xmlns="http://www.w3.org/2000/svg" height="1.5rem" viewBox="0 0 24 24" width="1.5rem" fill="#000000"><path d="M0 0h24v24H0z" fill="none"/><path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/></svg>
                                            <h5>Peringatan</h5>
                                            <p class="">Data kepegawaian perlu di update. Silahkan klik tombol di bawah untuk update data kepegawaian</p>
                                            <div class="">
                                                <button type="button" class="btn btn-sm btn-secondary m-1 update-kepegawaian">Update Sekarang</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            <div class="container-kepegawaian" {{ ($kepegawaian != null ? '' : 'hidden') }}>
                                <form action="{{ ($kepegawaian != null ? '/admin_sdm/kepegawaian/update/'. $kepegawaian->id_kepegawaian : '/admin_sdm/kepegawaian/store') }}" method="POST">
                                    @csrf
                                    @if($kepegawaian != null)
                                    @method('put')
                                    @endif
                                    <input type="hidden" name="user_id" value="{{ $karyawan->user_id }}">
                                    <div class="form-group">
                                        <label for="sub_jabatan_id" class="form-label">Jabatan</label>
                                        <select name="sub_jabatan_id" id="sub_jabatan_id" class="form-control" {{ ($kepegawaian != null ? 'Update' : 'Simpan') }} {{ ($kepegawaian != null ? 'disabled' : '') }}>
                                            <option selected disabled>Pilih Jabatan</option>
                                            @foreach($sub_jabatan as $key => $row)
                                                <option {{ old('sub_jabatan_id', ($kepegawaian == null ? '' : $kepegawaian->sub_jabatan_id)) == $row->id ? 'selected' : '' }} value="{{ $row->id }}">{{ $row->nama_sub_jabatan }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="status_pekerjaan_id" class="form-label">Status Pekerjaan</label>
                                        <select name="status_pekerjaan_id" id="status_pekerjaan_id" class="form-control" {{ ($kepegawaian != null ? 'disabled' : '') }}>
                                            <option selected disabled>Pilih Status Pekerjaan</option>
                                            @foreach($status_pekerjaan as $key => $row)
                                                <option {{ old('sub_jabatan_id', ($kepegawaian == null ? '' : $kepegawaian->status_pekerjaan_id)) == $row->id ? 'selected' : '' }} value="{{ $row->id }}">{{ $row->nama_status_pekerjaan }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="divisi_id" class="form-label">Divisi</label>
                                        <select name="divisi_id" id="divisi_id" class="form-control" {{ ($kepegawaian != null ? 'disabled' : '') }}>
                                            <option selected disabled>Pilih Divisi</option>
                                            @foreach($divisi as $key => $row)
                                                <option {{ old('divisi_id', ($kepegawaian == null ? '' : $kepegawaian->divisi_id)) == $row->id ? 'selected' : '' }} value="{{ $row->id }}">{{ $row->nama_divisi }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                  <div class="row">
                                   <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="tgl_masuk" class="form-label">Tanggal Masuk</label>
                                            <input type="date" name="tgl_masuk" id="tgl_masuk" class="form-control" value="{{ old('tgl_masuk', ($kepegawaian == null ? '' : $kepegawaian->tgl_masuk)) }}" {{ ($kepegawaian != null ? 'disabled' : '') }}>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="tgl_berakhir" class="form-label">Tanggal Berakhir</label>
                                            <input type="date" name="tgl_berakhir" id="tgl_berakhir" class="form-control" value="{{ old('tgl_berakhir', ($kepegawaian == null ? '' : $kepegawaian->tgl_berakhir)) }}" {{ ($kepegawaian != null ? 'disabled' : '') }}>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="is_active" class="form-label">Status Keaktifan</label>
                                        <select name="is_active" id="is_active" class="form-control" {{ ($kepegawaian != null ? 'disabled' : '') }}>
                                            <option selected disabled>Pilih Status Keaktifan</option>
                                            <option value="1" {{ isset($kepegawaian) && $kepegawaian->is_active == true ? 'selected' : '' }}>Aktif</option>
                                            <option value="0" {{ isset($kepegawaian) && $kepegawaian->is_active == false ? 'selected' : '' }}>Tidak Aktif</option>
                                        </select>
                                    </div>                                    
                                    <div class="form-group">
                                        <label for="no_npwp" class="form-label">No NPWP</label>
                                        <input type="number" name="no_npwp" id="no_npwp" class="form-control" placeholder="Masukan Nomer NPWP Jika Ada" value="{{ old('no_npwp', ($kepegawaian == null ? '' : $kepegawaian->no_npwp)) }}" {{ ($kepegawaian != null ? 'disabled' : '') }}>
                                    </div>
                                  </div>
                                  @if($kepegawaian != null)
                                  <button type="button" class="btn btn-danger btn-batal-edit" hidden>Batal</button>
                                    @if($role == 'admin_sdm')
                                    <button type="button" class="btn btn-warning btn-edit-kepegawaian">Edit</button>
                                    @endif
                                  @endif
                                  <button type="submit" class="btn btn-primary btn-submit-kepegawaian" {{ ($kepegawaian != null ? 'hidden' : '') }}>{{ ($kepegawaian != null ? 'Update' : 'Simpan') }}</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-8">
            {{-- <div class="row row-sm">
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
            </div> --}}
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
                            <div class="container-karyawan" {{ ($karyawan != null ? '' : 'hidden') }}>
                                {{-- <form class="form-horizontal"> --}}
                                <form action="{{ '/admin_sdm/datadiri/update/'. $karyawan->id }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('put')
                                    <input type="hidden" name="jenis_page" value="page_detail_kepegawaian">
                                    <div class="form-group mb-3">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label class="form-label fw-medium fs-6">Foto KTP</label>
                                            </div>
                                            <div class="col-md-1 text-center">
                                                <label class="form-label fw-medium fs-6">:</label>
                                            </div>
                                            <div class="col-md-8" id="foto_ktp_container">
                                                @if ($karyawan->foto_ktp)
                                                    <img src="{{ asset('uploads/' . $karyawan->foto_ktp) }}"
                                                    alt="Foto KTP"
                                                    class="img-thumbnail rounded w-50"
                                                    loading="lazy">
                                                @else
                                                    -
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mb-3"> 
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label class="form-label fw-medium fs-6">NIK</label>
                                            </div>
                                            <div class="col-md-1 text-center">
                                                <label class="form-label fw-medium fs-6">:</label>
                                            </div>
                                            <div class="col-md-8">
                                                <input type="text" class="form-control fs-6" name="nik" id="nik" value="{{ old('nik', ($karyawan == null ? '' : $karyawan->nik)) }}" {{ ($karyawan != null ? 'disabled' : '') }}>
                                            </div>
                                        </div>   
                                    </div>  
                                    <div class="form-group mb-3">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label class="form-label fw-medium fs-6">Nama Lengkap</label>
                                            </div>
                                            <div class="col-md-1 text-center">
                                                <label class="form-label fw-medium fs-6">:</label>
                                            </div>
                                            <div class="col-md-8">
                                                <input type="text" class="form-control fs-6" name="nama_lengkap" id="nama_lengkap" value="{{ old('nama_lengkap', ($karyawan == null ? '' : $karyawan->nama_lengkap)) }}" {{ ($karyawan != null ? 'disabled' : '') }}>
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="form-group mb-3"> 
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label class="form-label fw-medium fs-6">NIP</label>
                                            </div>
                                            <div class="col-md-1 text-center">
                                                <label class="form-label fw-medium fs-6">:</label>
                                            </div>
                                            <div class="col-md-8">
                                                <input type="text" class="form-control fs-6" name="nip" id="nip" value="{{ old('nip', (!$kepegawaian && !$kepegawaian->nip ? '' : $kepegawaian->nip)) }}" {{ ($kepegawaian != null ? 'disabled' : '') }}>
                                            </div>
                                        </div>   
                                    </div>  
                                    <div class="form-group mb-3"> 
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label class="form-label fw-medium fs-6">Tempat Lahir</label>
                                            </div>
                                            <div class="col-md-1 text-center">
                                                <label class="form-label fw-medium fs-6">:</label>
                                            </div>
                                            <div class="col-md-8">
                                                <input type="text" class="form-control fs-6" name="tempat_lahir" id="tempat_lahir" value="{{ old('tempat_lahir', ($karyawan == null ? '' : $karyawan->tempat_lahir)) }}" {{ ($karyawan != null ? 'disabled' : '') }}>
                                            </div>
                                        </div>   
                                    </div>    
                                    <div class="form-group mb-3"> 
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label class="form-label fw-medium fs-6">Tanggal Lahir</label>
                                            </div>
                                            <div class="col-md-1 text-center">
                                                <label class="form-label fw-medium fs-6">:</label>
                                            </div>
                                            <div class="col-md-8">
                                                <input type="date" class="form-control fs-6" name="tanggal_lahir" id="tanggal_lahir" value="{{ old('tanggal_lahir', ($karyawan == null ? '' : $karyawan->tanggal_lahir)) }}" {{ ($karyawan != null ? 'disabled' : '') }}>
                                            </div>
                                        </div>   
                                    </div>  
                                    <div class="form-group mb-3"> 
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label class="form-label fw-medium fs-6">Alamat KTP</label>
                                            </div>
                                            <div class="col-md-1 text-center">
                                                <label class="form-label fw-medium fs-6">:</label>
                                            </div>
                                            <div class="col-md-8">
                                                <textarea
                                                    class="form-control fs-6"
                                                    name="alamat_ktp"
                                                    id="alamat_ktp"
                                                    rows="3"
                                                    {{ $karyawan ? 'disabled' : '' }}
                                                >{{ old('alamat_ktp', $karyawan?->alamat_ktp) }}</textarea>
                                            </div>
                                        </div>   
                                    </div>    
                                    <div class="form-group mb-3"> 
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label class="form-label fw-medium fs-6">Alamat Domisili</label>
                                            </div>
                                            <div class="col-md-1 text-center">
                                                <label class="form-label fw-medium fs-6">:</label>
                                            </div>
                                            <div class="col-md-8">
                                                <textarea
                                                    class="form-control fs-6"
                                                    name="alamat_domisili"
                                                    id="alamat_domisili"
                                                    rows="3"
                                                    {{ $karyawan ? 'disabled' : '' }}
                                                >{{ old('alamat_domisili', $karyawan?->alamat_domisili) }}</textarea>
                                            </div>
                                        </div>   
                                    </div>                                
                                    <div class="form-group mb-3"> 
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label class="form-label fw-medium fs-6">Agama</label>
                                            </div>
                                            <div class="col-md-1 text-center">
                                                <label class="form-label fw-medium fs-6">:</label>
                                            </div>
                                            <div class="col-md-8">
                                                <input type="text" class="form-control fs-6" name="agama" id="agama" value="{{ old('agama', ($karyawan == null ? '' : $karyawan->agama)) }}" {{ ($karyawan != null ? 'disabled' : '') }}>
                                            </div>
                                        </div>   
                                    </div> 
                                    <div class="form-group mb-3"> 
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label class="form-label fw-medium fs-6">Jenis Kelamin</label>
                                            </div>
                                            <div class="col-md-1 text-center">
                                                <label class="form-label fw-medium fs-6">:</label>
                                            </div>
                                            <div class="col-md-8">
                                                <input type="text" class="form-control fs-6" name="jenis_kelamin" id="jenis_kelamin" value="{{ old('jenis_kelamin', ($karyawan == null ? '' : $karyawan->jenis_kelamin)) }}" {{ ($karyawan != null ? 'disabled' : '') }}>
                                            </div>
                                        </div>   
                                    </div>  
                                    <div class="form-group mb-3"> 
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label class="form-label fw-medium fs-6">No. HP</label>
                                            </div>
                                            <div class="col-md-1 text-center">
                                                <label class="form-label fw-medium fs-6">:</label>
                                            </div>
                                            <div class="col-md-8">
                                                <input type="number" class="form-control fs-6" name="no_hp" id="no_hp" value="{{ old('no_hp', ($karyawan == null ? '' : $karyawan->no_hp)) }}" {{ ($karyawan != null ? 'disabled' : '') }}>
                                            </div>
                                        </div>   
                                    </div>  
                                    <div class="form-group mb-3"> 
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label class="form-label fw-medium fs-6">Email Non Chaakra</label>
                                            </div>
                                            <div class="col-md-1 text-center">
                                                <label class="form-label fw-medium fs-6">:</label>
                                            </div>
                                            <div class="col-md-8">
                                                <input type="text" class="form-control fs-6" name="email_nonchaakra" id="email_nonchaakra" value="{{ old('email_nonchaakra', ($karyawan == null ? '' : $karyawan->email_nonchaakra)) }}" {{ ($karyawan != null ? 'disabled' : '') }}>
                                            </div>
                                        </div>   
                                    </div> 
                                    <div class="form-group mb-3"> 
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label class="form-label fw-medium fs-6">Status Pernikahan</label>
                                            </div>
                                            <div class="col-md-1 text-center">
                                                <label class="form-label fw-medium fs-6">:</label>
                                            </div>
                                            <div class="col-md-8">
                                                <input type="text" style="text-transform: capitalize;" class="form-control fs-6" name="status_pernikahan" id="status_pernikahan" value="{{ old('status_pernikahan', ($karyawan == null ? '' : $karyawan->status_pernikahan)) }}" {{ ($karyawan != null ? 'disabled' : '') }}>
                                            </div>
                                        </div>   
                                    </div> 
                                    <div class="mt-3 main-content-label text-center">Kontak Emergency</div> 
                                    <div class="form-group mb-3"> 
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label class="form-label fw-medium fs-6">Nama</label>
                                            </div>
                                            <div class="col-md-1 text-center">
                                                <label class="form-label fw-medium fs-6">:</label>
                                            </div>
                                            <div class="col-md-8">
                                                <input type="text" class="form-control fs-6" name="nama_emergency" id="nama_emergency" value="{{ old('nama_emergency', ($karyawan == null ? '' : $karyawan->nama_emergency)) }}" {{ ($karyawan != null ? 'disabled' : '') }}>
                                            </div>
                                        </div>   
                                    </div>  
                                    <div class="form-group mb-3"> 
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label class="form-label fw-medium fs-6">Hubungan</label>
                                            </div>
                                            <div class="col-md-1 text-center">
                                                <label class="form-label fw-medium fs-6">:</label>
                                            </div>
                                            <div class="col-md-8">
                                                {{-- <input type="text" class="form-control fs-6" name="hubungan_emergency" id="hubungan_emergency" value="{{ old('hubungan_emergency', ($karyawan == null ? '' : $karyawan->hubungan_emergency)) }}" {{ ($karyawan != null ? 'disabled' : '') }}> --}}
                                                <select class="form-select fs-6" id="hubungan_emergency" name="hubungan_emergency" {{ ($karyawan != null ? 'disabled' : '') }}>
                                                    <option value="">-</option>
                                                    <option {{ old('hubungan_emergency', ($karyawan == null ? '' : $karyawan->hubungan_emergency)) == 'Bapak' ? 'selected' : '' }} value="Bapak">Bapak</option>
                                                    <option {{ old('hubungan_emergency', ($karyawan == null ? '' : $karyawan->hubungan_emergency)) == 'Ibu' ? 'selected' : '' }} value="Ibu">Ibu</option>
                                                    <option {{ old('hubungan_emergency', ($karyawan == null ? '' : $karyawan->hubungan_emergency)) == 'Suami' ? 'selected' : '' }} value="Suami">Suami</option>
                                                    <option {{ old('hubungan_emergency', ($karyawan == null ? '' : $karyawan->hubungan_emergency)) == 'Istri' ? 'selected' : '' }} value="Istri">Istri</option>
                                                    <option {{ old('hubungan_emergency', ($karyawan == null ? '' : $karyawan->hubungan_emergency)) == 'Saudara Kandung' ? 'selected' : '' }} value="Saudara Kandung">Saudara Kandung</option>
                                                    <option {{ old('hubungan_emergency', ($karyawan == null ? '' : $karyawan->hubungan_emergency)) == 'Lainnya' ? 'selected' : '' }} value="Lainnya">Lainnya</option>
                                                </select>
                                            </div>
                                        </div>   
                                    </div>
                                    <div class="form-group mb-3"> 
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label class="form-label fw-medium fs-6">No. Hp</label>
                                            </div>
                                            <div class="col-md-1 text-center">
                                                <label class="form-label fw-medium fs-6">:</label>
                                            </div>
                                            <div class="col-md-8">
                                                <input type="number" class="form-control fs-6" name="no_emergency" id="no_emergency" value="{{ old('no_emergency', ($karyawan == null ? '' : $karyawan->no_emergency)) }}" {{ ($karyawan != null ? 'disabled' : '') }}>
                                            </div>
                                        </div>   
                                    </div>  
                                    @if($kepegawaian != null)
                                        <button type="button" class="btn btn-danger btn-batal-karyawan" hidden>Batal</button>
                                        @if($role == 'admin_sdm' && $kepegawaian->status_pekerjaan_id == '4')
                                        <button type="button" class="btn btn-warning btn-edit-karyawan">Edit</button>
                                        @endif
                                    @endif  
                                    <button type="submit" class="btn btn-primary btn-submit-karyawan" {{ ($karyawan != null ? 'hidden' : '') }}>{{ ($kepegawaian != null ? 'Update' : 'Simpan') }}</button>       
                                </form>
                            </div>
                        </div>
                        <div class="tab-pane border-0 p-0" id="kesehatan">
                           @if(!$kesehatan)
                           <div class="alert alert-danger">Data Kesehatan belum dilengkapi</div>
                           @endif
                           <div class="container-kesehatan">
                                <form action="{{ ($kesehatan != null ? '/admin_sdm/kesehatan/update/'. $kesehatan->id : '/admin_sdm/kesehatan/store') }}" method="POST">
                                    @csrf
                                    @if($kesehatan != null)
                                    @method('put')
                                    @endif
                                    <input type="hidden" name="jenis_page" value="page_detail_kepegawaian">
                                    <input type="hidden" name="user_id" value={{ $karyawan->user_id }}>
                                    <div class="form-group mb-3"> 
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label class="form-label fw-medium fs-6">Golongan Darah</label>
                                            </div>
                                            <div class="col-md-1 text-center">
                                                <label class="form-label fw-medium fs-6">:</label>
                                            </div>
                                            <div class="col-md-8">
                                                <select class="form-select fs-6" id="golongan_darah" name="golongan_darah" disabled>
                                                    <option value="">-</option>
                                                    <option {{ old('golongan_darah', ($kesehatan == null ? '' : $kesehatan->golongan_darah)) == 'A' ? 'selected' : '' }} value="A">A</option>
                                                    <option {{ old('golongan_darah', ($kesehatan == null ? '' : $kesehatan->golongan_darah)) == 'B' ? 'selected' : '' }} value="B">B</option>
                                                    <option {{ old('golongan_darah', ($kesehatan == null ? '' : $kesehatan->golongan_darah)) == 'AB' ? 'selected' : '' }} value="AB">AB</option>
                                                    <option {{ old('golongan_darah', ($kesehatan == null ? '' : $kesehatan->golongan_darah)) == 'O' ? 'selected' : '' }} value="O">O</option>
                                                </select>                                            
                                            </div>
                                        </div>   
                                    </div>
                                    <div class="form-group mb-3"> 
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label class="form-label fw-medium fs-6">Riwayat Alergi</label>
                                            </div>
                                            <div class="col-md-1 text-center">
                                                <label class="form-label fw-medium fs-6">:</label>
                                            </div>
                                            <div class="col-md-8">
                                                <input type="text" class="form-control fs-6" name="riwayat_alergi" id="riwayat_alergi" value="{{ old('riwayat_alergi', ($kesehatan == null ? '' : $kesehatan->riwayat_alergi)) }}" disabled>
                                            </div>
                                        </div>   
                                    </div>
                                    <div class="form-group mb-3"> 
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label class="form-label fw-medium fs-6">Riwayat Penyakit</label>
                                            </div>
                                            <div class="col-md-1 text-center">
                                                <label class="form-label fw-medium fs-6">:</label>
                                            </div>
                                            <div class="col-md-8">
                                                <input type="text" class="form-control fs-6" name="riwayat_penyakit" id="riwayat_penyakit" value="{{ old('riwayat_penyakit', ($kesehatan == null ? '' : $kesehatan->riwayat_penyakit)) }}" disabled>
                                            </div>
                                        </div>   
                                    </div> 
                                    <div class="form-group mb-3"> 
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label class="form-label fw-medium fs-6">Riwayat Penyakit Lain</label>
                                            </div>
                                            <div class="col-md-1 text-center">
                                                <label class="form-label fw-medium fs-6">:</label>
                                            </div>
                                            <div class="col-md-8">
                                                <input type="text" class="form-control fs-6" name="riwayat_penyakit_lain" id="riwayat_penyakit_lain" value="{{ old('riwayat_penyakit_lain', ($kesehatan == null ? '' : $kesehatan->riwayat_penyakit_lain)) }}" disabled>
                                            </div>
                                        </div>   
                                    </div>  
                                    <button type="button" class="btn btn-danger btn-batal-kesehatan" hidden>Batal</button>
                                    @if($role == 'admin_sdm' && $kepegawaian->status_pekerjaan_id == '4')
                                        <button type="button" class="btn btn-warning btn-edit-kesehatan">Edit</button>
                                    @endif  
                                    <button type="submit" class="btn btn-primary btn-submit-kesehatan" hidden>{{ ($kesehatan != null ? 'Update' : 'Simpan') }}</button>         
                                </form>
                            </div>
                        </div>
                        <div class="tab-pane border-0 p-0" id="pendidikan">
                            @if(!$pendidikan)
                            <div class="alert alert-danger">Data Pendidikan belum dilengkapi</div>
                            @endif
                            <div class="container-pendidikan">
                                <form action="{{ ($pendidikan != null ? '/admin_sdm/datadiri/pendidikan/'. $pendidikan->id : '/admin_sdm/datadiri/pendidikan') }}" method="POST">
                                    @csrf
                                    @if($pendidikan != null)
                                    @method('put')
                                    @endif
                                    <input type="hidden" name="jenis_page" value="page_detail_kepegawaian">
                                    <input type="hidden" name="user_id" value={{ $karyawan->user_id }}>
                                    <div class="form-group mb-3"> 
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label class="form-label fw-medium fs-6">Nama</label>
                                            </div>
                                            <div class="col-md-1 text-center">
                                                <label class="form-label fw-medium fs-6">:</label>
                                            </div>
                                            <div class="col-md-8">
                                                <input type="text" class="form-control fs-6" name="nama_sekolah" id="nama_sekolah" value="{{ old('nama_sekolah', ($pendidikan == null ? '' : $pendidikan->nama_sekolah)) }}" disabled>
                                            </div>
                                        </div>   
                                    </div> 
                                    <div class="form-group mb-3"> 
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label class="form-label fw-medium fs-6">Jurusan</label>
                                            </div>
                                            <div class="col-md-1 text-center">
                                                <label class="form-label fw-medium fs-6">:</label>
                                            </div>
                                            <div class="col-md-8">
                                                <input type="text" class="form-control fs-6" name="jurusan_sekolah" id="jurusan_sekolah" value="{{ old('jurusan_sekolah', ($pendidikan == null ? '' : $pendidikan->jurusan_sekolah)) }}" disabled>
                                            </div>
                                        </div>   
                                    </div>  
                                    <div class="form-group mb-3"> 
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label class="form-label fw-medium fs-6">Alamat</label>
                                            </div>
                                            <div class="col-md-1 text-center">
                                                <label class="form-label fw-medium fs-6">:</label>
                                            </div>
                                            <div class="col-md-8">
                                                <textarea
                                                    class="form-control fs-6"
                                                    name="alamat_sekolah"
                                                    id="alamat_sekolah"
                                                    rows="3"
                                                    disabled
                                                >{{ old('alamat_sekolah', $pendidikan?->alamat_sekolah) }}</textarea>
                                            </div>
                                        </div>   
                                    </div>
                                    <div class="form-group mb-3"> 
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label class="form-label fw-medium fs-6">Tahun Mulai</label>
                                            </div>
                                            <div class="col-md-1 text-center">
                                                <label class="form-label fw-medium fs-6">:</label>
                                            </div>
                                            <div class="col-md-8">
                                                <input type="number" class="form-control fs-6" name="tahun_mulai" id="tahun_mulai" value="{{ old('tahun_mulai', ($pendidikan == null ? '' : $pendidikan->tahun_mulai)) }}" disabled>
                                            </div>
                                        </div>   
                                    </div> 
                                    <div class="form-group mb-3"> 
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label class="form-label fw-medium fs-6">Tahun Lulus</label>
                                            </div>
                                            <div class="col-md-1 text-center">
                                                <label class="form-label fw-medium fs-6">:</label>
                                            </div>
                                            <div class="col-md-8">
                                                <input type="number" class="form-control fs-6" name="tahun_lulus" id="tahun_lulus" value="{{ old('tahun_lulus', ($pendidikan == null ? '' : $pendidikan->tahun_lulus)) }}" disabled>
                                            </div>
                                        </div>   
                                    </div> 
                                    <button type="button" class="btn btn-danger btn-batal-pendidikan" hidden>Batal</button>
                                    @if($role == 'admin_sdm' && $kepegawaian->status_pekerjaan_id == '4')
                                    <button type="button" class="btn btn-warning btn-edit-pendidikan">Edit</button>
                                    @endif 
                                    <button type="submit" class="btn btn-primary btn-submit-pendidikan" hidden>{{ ($pendidikan != null ? 'Update' : 'Simpan') }}</button>
                                </form> 
                            </div>
                            {{-- @endif --}}
                        </div>
                        <div class="tab-pane border-0 p-0" id="pengalaman_kerja">
                            <div class="table-responsive">
                                @if($role == 'admin_sdm' && $kepegawaian->status_pekerjaan_id == '4')
                                <div class="mb-2">
                                    <button type="button" class="btn btn-primary tambahPengalaman" data-bs-toggle="modal"
                                        data-bs-target="#staticBackdrop">
                                        Tambah Pengalaman
                                    </button>
                                </div>
                                @endif
                                <table class="table table-bordered text-nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            @if($role == 'admin_sdm' && $kepegawaian->status_pekerjaan_id == '4')
                                            <th>Aksi</th>
                                            @endif
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
                                                @if($role == 'admin_sdm' && $kepegawaian->status_pekerjaan_id == '4')
                                                <td>
                                                    <a href="" class="btn btn-warning editPengalaman" data-bs-toggle="modal"
                                                    data-bs-target="#staticBackdropPengalaman" data-id_pengalaman="{{ $row->id }}"
                                                    data-nama_perusahaan="{{ $row->nama_perusahaan }}"
                                                    data-tgl_mulai="{{ $row->tgl_mulai }}"
                                                    data-tgl_selesai="{{ $row->tgl_selesai }}"
                                                    data-jabatan_akhir="{{ $row->jabatan_akhir }}"
                                                    data-alasan_keluar="{{ $row->alasan_keluar }}"
                                                    data-no_hp_referensi="{{ $row->no_hp_referensi }}"
                                                    data-upload_surat_referensi="{{ $row->upload_surat_referensi }}"
                                                    data-dokumen_pdf="{{ asset('uploads/' . $row->upload_surat_referensi) }}">
                                                    <i class="fas fa-edit"></i></a>
                                                </td>
                                                @endif
                                                <td>{{ $row->nama_perusahaan }}</td>
                                                <td>{{ $row->tgl_mulai }} - {{ $row->tgl_selesai }}</td>
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
                                @if($role == 'admin_sdm' && $kepegawaian->status_pekerjaan_id == '4')
                                <div class="mb-2">
                                    <button type="button" class="btn btn-primary tambahPelatihan" data-bs-toggle="modal"
                                        data-bs-target="#staticBackdropPelatihan">
                                        Tambah Pelatihan
                                    </button>
                                </div>
                                @endif
                                <table class="table table-bordered text-nowrap w-100">  
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            @if($role == 'admin_sdm' && $kepegawaian->status_pekerjaan_id == '4')
                                            <th>Aksi</th>
                                            @endif
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
                                                @if($role == 'admin_sdm' && $kepegawaian->status_pekerjaan_id == '4')
                                                <td>
                                                    <a href="" class="btn btn-warning editPelatihan" data-bs-toggle="modal"
                                                        data-bs-target="#staticBackdropPelatihan" 
                                                        data-id="{{ $row->id }}" 
                                                        data-nama_pelatihan="{{ $row->nama_pelatihan }}" 
                                                        data-tujuan_pelatihan="{{ $row->tujuan_pelatihan }}" 
                                                        data-tahun_pelatihan="{{ $row->tahun_pelatihan }}" 
                                                        data-nomor_sertifikat="{{ $row->nomor_sertifikat }}" >
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </td>
                                                @endif
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
                            @if($role == 'admin_sdm' && $kepegawaian->status_pekerjaan_id == '4')
                            <div class="mb-2">
                                <button type="button" class="btn btn-primary tambahSocialMedia" data-bs-toggle="modal"
                                    data-bs-target="#staticBackdropSocialMedia">
                                    Tambah Sosial Media
                                </button>
                            </div>
                            @endif
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
</div>
<div class="modal fade" id="staticBackdropPengalaman" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title-pengalaman-kerja" id="staticBackdropLabel">Modal title
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="POST" id="formPengalaman" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="jenis_page" value="page_detail_kepegawaian">
                    <input type="hidden" name="user_id" value={{ $karyawan->user_id }}>
                    <div class="form-group">
                        <label for="nama_perusahaan">Nama Perusahaan</label>
                        <input type="text" name="nama_perusahaan" id="nama_perusahaan" class="form-control" required>
                    </div>
                    <div class="row g-3 align-items-center mb-3">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="tgl_mulai" class="form-label">Tanggal Mulai</label>
                                <input type="date" name="tgl_mulai" id="tgl_mulai" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="tgl_selesai" class="form-label">Tanggal Selesai</label>
                                <input type="date" name="tgl_selesai" id="tgl_selesai" class="form-control" required>
                            </div>
                        </div>
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
                        <label for="upload_surat_referensi">Surat Referensi <small class="text-danger">(MAX 2MB) pdf
                                only</small></label>
                        <input type="file" name="upload_surat_referensi" id="upload_surat_referensi"
                            class="form-control" accept="application/pdf">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btnClearForm">Clear Form</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kembali</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="staticBackdropPelatihan" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title-pelatihan" id="staticBackdropLabel">Modal title
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <form action="" method="POST" id="formPelatihan" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <input type="hidden" name="jenis_page" value="page_detail_kepegawaian">
                <input type="hidden" name="user_id" value={{ $karyawan->user_id }}>
                <div class="form-group">
                    <label for="nama_pelatihan">Nama Pelatihan</label>
                    <input type="text" name="nama_pelatihan" id="nama_pelatihan" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="tujuan_pelatihan">Tujuan Pelatihan</label>
                    <input type="text" name="tujuan_pelatihan" id="tujuan_pelatihan" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="tahun_pelatihan">Tahun Pelatihan</label>
                    <input type="date" name="tahun_pelatihan" id="tahun_pelatihan" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="nomor_sertifikat">Nomor Sertifikat</label>
                    <input type="text" name="nomor_sertifikat" id="nomor_sertifikat" class="form-control" required></input>
                </div>
                <div class="form-group">
                    <label for="upload_sertifikat">Upload Sertifikat <small class="text-danger">(MAX 2MB) pdf only</small></label>
                    <input type="file" name="upload_sertifikat" id="upload_sertifikat" class="form-control" accept="application/pdf">
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
<div class="modal fade" id="staticBackdropSocialMedia" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title-social-media" id="staticBackdropLabel">Modal title
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <form action="" method="POST" id="formSocialMedia">
            @csrf
            <div class="modal-body">
                <input type="hidden" name="jenis_page" value="page_detail_kepegawaian">
                <input type="hidden" name="user_id" value={{ $karyawan->user_id }}>
                <div class="form-group">
                    <label for="nama_social_media">Nama Sosial Media</label>
                    <select name="nama_social_media" id="nama_social_media" class="form-control">
                        <option selected disabled>Pilih Sosial Media</option>
                        <option value="instagram">Instagram</option>
                        <option value="facebook">Facebook</option>
                        <option value="linkedin">LinkedIn</option>
                        <option value="twitter">Twitter</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="link">Link Sosial Media</label>
                    <input type="url" name="link" id="link" class="form-control" required>
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

        // $(".container-kepegawaian").hide();
        $(".update-kepegawaian").click(function(){
            console.log('test')
            $(".container-peringatan").slideUp(200);
            $(".container-kepegawaian").prop('hidden', false).slideDown(200);
        })

        $('.btn-edit-kepegawaian').click(function(){
            console.log('test')
            $('.btn-edit-kepegawaian').hide();
            $('.btn-batal-edit').prop('hidden', false);
            $(".btn-submit-kepegawaian").prop('hidden', false);

            $("#sub_jabatan_id").prop('disabled', false);
            $("#status_pekerjaan_id").prop('disabled', false);
            $("#tgl_masuk").prop('disabled', false);
            $("#tgl_berakhir").prop('disabled', false);
            $("#is_active").prop('disabled', false);
            $("#no_npwp").prop('disabled', false);

            $('.btn-batal-edit').click(function(){
                $("#sub_jabatan_id").prop('disabled', true);
                $("#status_pekerjaan_id").prop('disabled', true);
                $("#tgl_masuk").prop('disabled', true);
                $("#tgl_berakhir").prop('disabled', true);
                $("#is_active").prop('disabled', true);
                $("#no_npwp").prop('disabled', true);

                $('.btn-edit-kepegawaian').fadeIn(200);
                $('.btn-batal-edit').prop('hidden', true);
                $(".btn-submit-kepegawaian").prop('hidden', true);
            })
        })
        const container = $('#foto_ktp_container');
        const originalHtml = container.html();

        $('.btn-edit-karyawan').on('click', function () {
            $(this).hide();
            $('.btn-batal-karyawan').prop('hidden', false);
            $('.btn-submit-karyawan').prop('hidden', false);

            $('#nama_lengkap, #nip, #tempat_lahir, #tanggal_lahir, #alamat_ktp, #alamat_domisili, #agama, #jenis_kelamin, #no_hp, #no_emergency, #nama_emergency, #hubungan_emergency, #email_nonchaakra, #status_pernikahan, #nik')
                .prop('disabled', false);
            
            container.html(`
                <input type="file" class="form-control mb-2" name="foto_ktp"
                    id="foto_ktp" accept="image/*">
                {!! $karyawan && $karyawan->foto_ktp
                    ? '<img src="'.asset('uploads/'.$karyawan->foto_ktp).'"
                            id="preview_foto_ktp"
                            class="img-thumbnail rounded w-50">'
                    : '' !!}
            `);
        });

        container.on('change', '#foto_ktp', function (e) {
            const file = e.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = function (evt) {
                // jika img preview belum ada, buat
                let img = container.find('#preview_foto_ktp');
                if (!img.length) {
                    img = $('<img>', {
                        id: 'preview_foto_ktp',
                        class: 'img-thumbnail rounded w-50 mt-2'
                    }).appendTo(container);
                }
                img.attr('src', evt.target.result);
            };
            reader.readAsDataURL(file);
        });

        $('.btn-batal-karyawan').on('click', function () {
            $('#nama_lengkap, #nip, #tempat_lahir, #tanggal_lahir, #alamat_ktp, #alamat_domisili, #agama, #jenis_kelamin, #no_hp, #no_emergency, #nama_emergency, #hubungan_emergency, #email_nonchaakra, #status_pernikahan, #nik')
                .prop('disabled', true);

            container.html(originalHtml);

            $('.btn-edit-karyawan').fadeIn(200);
            $(this).prop('hidden', true);
            $('.btn-submit-karyawan').prop('hidden', true);
        });

        $('.btn-edit-pendidikan').click(function(){
            console.log('test')
            $('.btn-edit-pendidikan').hide();
            $('.btn-batal-pendidikan').prop('hidden', false);
            $(".btn-submit-pendidikan").prop('hidden', false);

            $("#nama_sekolah").prop('disabled', false);
            $("#jurusan_sekolah").prop('disabled', false);
            $("#alamat_sekolah").prop('disabled', false);
            $("#tahun_mulai").prop('disabled', false);
            $("#tahun_lulus").prop('disabled', false);

            $('.btn-batal-pendidikan').click(function(){
                $("#nama_sekolah").prop('disabled', true);
                $("#jurusan_sekolah").prop('disabled', true);
                $("#alamat_sekolah").prop('disabled', true);
                $("#tahun_mulai").prop('disabled', true);
                $("#tahun_lulus").prop('disabled', true);

                $('.btn-edit-pendidikan').fadeIn(200);
                $('.btn-batal-pendidikan').prop('hidden', true);
                $(".btn-submit-pendidikan").prop('hidden', true);
            })
        })

        $('.btn-edit-kesehatan').click(function(){
            console.log('test')
            $('.btn-edit-kesehatan').hide();
            $('.btn-batal-kesehatan').prop('hidden', false);
            $(".btn-submit-kesehatan").prop('hidden', false);

            $("#golongan_darah").prop('disabled', false);
            $("#riwayat_alergi").prop('disabled', false);
            $("#riwayat_penyakit").prop('disabled', false);
            $("#riwayat_penyakit_lain").prop('disabled', false);

            $('.btn-batal-kesehatan').click(function(){
                $("#golongan_darah").prop('disabled', true);
                $("#riwayat_alergi").prop('disabled', true);
                $("#riwayat_penyakit").prop('disabled', true);
                $("#riwayat_penyakit_lain").prop('disabled', true);

                $('.btn-edit-kesehatan').fadeIn(200);
                $('.btn-batal-kesehatan').prop('hidden', true);
                $(".btn-submit-kesehatan").prop('hidden', true);
            })
        })

        $(".tambahPengalaman").click(function() {
            $(".modal-title-pengalaman-kerja").text('Tambah Pengalaman Kerja');
            $("#formPengalaman").attr('action', '/admin_sdm/pengalaman_kerja/store');
        })

        $(".editPengalaman").click(function(e) {
            e.preventDefault();
            $(".modal-title-pengalaman-kerja").text('Edit Pengalaman Kerja');
            $("#nama_perusahaan").val($(this).data('nama_perusahaan'));
            $("#tgl_mulai").val($(this).data('tgl_mulai'));
            $("#tgl_selesai").val($(this).data('tgl_selesai'));
            $("#jabatan_akhir").val($(this).data('jabatan_akhir'));
            $("#alasan_keluar").val($(this).data('alasan_keluar'));
            $("#no_hp_referensi").val($(this).data('no_hp_referensi'));
            $("#formPengalaman").append('<input type="hidden" name="_method" value="PUT">');
            $("#formPengalaman").attr('action', '/admin_sdm/pengalaman_kerja/update/' + $(this).data('id_pengalaman'));
        })

        $(".btnClearForm").click(function() {
            $("#nama_perusahaan").val('');
            $("#tgl_mulai").val('');
            $("#tgl_selesai").val('');
            $("#jabatan_akhir").val('');
            $("#alasan_keluar").val('');
            $("#no_hp_referensi").val('');
            $("#upload_surat_referensi").val('');
            $("textarea").val('');
        })

        $(".tambahPelatihan").click(function(){
            $(".modal-title-pelatihan").text('Tambah Pelatihan');
            $("textarea").val(''); // Mengosongkan textarea jika ada
            $("#formPelatihan").attr('action', '/admin_sdm/pelatihan/store');
        })

        $(".editPelatihan").click(function(e){
            e.preventDefault();
            $(".modal-title-pelatihan").text('Edit Pelatihan');
            $("#nama_pelatihan").val($(this).data('nama_pelatihan'));
            $("#tujuan_pelatihan").val($(this).data('tujuan_pelatihan'));
            $("#tahun_pelatihan").val($(this).data('tahun_pelatihan'));
            $("#nomor_sertifikat").val($(this).data('nomor_sertifikat'));

            $("#formPelatihan").append('<input type="hidden" name="_method" value="PUT">');
            $("#formPelatihan").attr('action', '/admin_sdm/pelatihan/update/' + $(this).data('id'));
        })

        $(".tambahSocialMedia").click(function(){
            $(".modal-title-social-media").text('Tambah Sosial Media');
            $("#nama_social_media").change().val('Pilih Sosial Media');
            $("#link").val('');
            $("#formSocialMedia").attr('action', '/admin_sdm/social_media/store');
        })

        $(".editSocialMedia").click(function(e){
            e.preventDefault();
            $(".modal-title-social-media").text('Edit Sosial Media');
            $("#nama_social_media").change().val($(this).data('nama_social_media'));
            $("#link").val($(this).data('link'));
            
            $("#formSocialMedia").append('<input type="hidden" name="_method" value="PUT">');
            $("#formSocialMedia").attr('action', '/admin_sdm/social_media/update/' + $(this).data('id'));
        })
    })
</script>
@endsection