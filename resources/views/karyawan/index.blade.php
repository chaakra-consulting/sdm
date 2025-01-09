@extends('layouts.main')

@section('content')
<div class="container-fluid">
    <!-- Start Form Data Diri -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">Form Data Diri</div>
                </div>
                <div class="card-body">
                    @if (!$datadiri)
                        <div class="alert alert-warning text-center">Data diri Anda belum tersedia. Silahkan mengisi disini <a href="#"  data-bs-toggle="modal" data-bs-target="#modalTambahDataDiri">
                            Tambah Data
                        </a></div>
                    @else
                        <form action="{{ route('datadiri.update', $datadiri->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('put')
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nik" class="form-label">NIK</label>
                                    <input type="text" class="form-control" id="nik" name="nik" value="{{ $datadiri->nik }}"
                                        maxlength="16" minlength="16" placeholder="Masukkan NIK" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap"
                                        value="{{ $datadiri->nama_lengkap }}" placeholder="Masukkan Nama Lengkap" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                                    <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir"
                                        value="{{ $datadiri->tempat_lahir }}" placeholder="Masukkan Tempat Lahir" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="tgl_lahir" class="form-label">Tanggal Lahir</label>
                                    <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir"
                                        value="{{ $datadiri->tanggal_lahir }}" placeholder="Pilih Tanggal Lahir" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="alamat_ktp" class="form-label">Alamat KTP</label>
                                    <textarea class="form-control" id="alamat_ktp" name="alamat_ktp"
                                        placeholder="Masukkan Alamat KTP" required>{{ $datadiri->alamat_ktp }}</textarea>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="alamat_domisili" class="form-label">Alamat Domisili</label>
                                    <textarea class="form-control" id="alamat_domisili" name="alamat_domisili"
                                        placeholder="Masukkan Alamat Domisili">{{ $datadiri->alamat_domisili }}</textarea>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="agama" class="form-label">Agama</label>
                                    <select class="form-select" id="agama" name="agama" required>
                                        <option value="">Pilih Agama</option>
                                        <option value="Islam" {{ $datadiri->agama == 'Islam' ? 'selected' : '' }}>Islam
                                        </option>
                                        <option value="Kristen" {{ $datadiri->agama == 'Kristen' ? 'selected' : '' }}>Kristen
                                        </option>
                                        <option value="Katolik" {{ $datadiri->agama == 'Katolik' ? 'selected' : '' }}>Katolik
                                        </option>
                                        <option value="Hindu" {{ $datadiri->agama == 'Hindu' ? 'selected' : '' }}>Hindu
                                        </option>
                                        <option value="Buddha" {{ $datadiri->agama == 'Buddha' ? 'selected' : '' }}>Buddha
                                        </option>
                                        <option value="Konghucu" {{ $datadiri->agama == 'Konghucu' ? 'selected' : '' }}>
                                            Konghucu</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                                    <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="Laki-laki" {{ $datadiri->jenis_kelamin == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="Perempuan" {{ $datadiri->jenis_kelamin == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="no_hp" class="form-label">No HP</label>
                                    <input type="text" class="form-control" id="no_hp" name="no_hp"
                                        value="{{ $datadiri->no_hp }}" placeholder="Masukkan Nomor HP" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="foto_user" class="form-label">Foto Diri</label>
                                    <input type="file" class="form-control" id="foto_user" name="foto_user" accept="image/*"
                                        onchange="previewImage(this)">
                                    <img id="preview" src="{{ ($datadiri->foto_user != null ? asset('uploads/' . $datadiri->foto_user) : 'https://t4.ftcdn.net/jpg/02/15/84/43/360_F_215844325_ttX9YiIIyeaR7Ne6EaLLjMAmy4GvPC69.jpg') }}" alt="Preview"
                                        style="max-width: 150px; margin-top: 10px;">
                                </div>
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </div>
                        </form>
                        <script>
                            function previewImage(input) {
                                var preview = document.getElementById('preview');
                                if (input.files && input.files[0]) {
                                    var reader = new FileReader();
                                    reader.onload = function (e) {
                                        preview.src = e.target.result;
                                    }
                                    reader.readAsDataURL(input.files[0]);
                                }
                            }
                        </script>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-6">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">Form data Pendidikan</div>
                </div>
                <div class="card-body">
                    @if (!$pendidikan)
                        <div class="alert alert-warning text-center">Data Pendidikan Anda belum tersedia. Silahkan mengisi disini <a
                                href="#" data-bs-toggle="modal" data-bs-target="#modalTambahPendidikan">
                                Tambah Data
                            </a></div>
                    @else
                        <form action="{{ route('pendidikan.update', $pendidikan->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nama_sekolah" class="form-label">Nama Sekolah</label>
                                    <input type="text" class="form-control" id="nama_sekolah" name="nama_sekolah" value="{{ $pendidikan->nama_sekolah }}"  placeholder="Masukkan Nama Sekolah" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="jurusan_sekolah" class="form-label">Jurusan Sekolah</label>
                                    <input type="text" class="form-control" id="jurusan_sekolah" name="jurusan_sekolah" value="{{ $pendidikan->jurusan_sekolah }}" placeholder="Masukkan Jurusan Sekolah" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="alamat_sekolah" class="form-label">Alamat Sekolah</label>
                                    <textarea class="form-control" id="alamat_sekolah" name="alamat_sekolah" placeholder="Masukkan Alamat Sekolah" required>{{ $pendidikan->alamat_sekolah }}</textarea>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="tahun_mulai" class="form-label">Tahun Masuk</label>
                                    <input type="number" class="form-control" id="tahun_mulai" name="tahun_mulai" value="{{ $pendidikan->tahun_mulai }}" min="1900" max="2099" step="1" maxlength="4" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="tahun_lulus" class="form-label">Tahun Lulus</label>
                                    <input type="number" class="form-control" id="tahun_lulus" name="tahun_lulus" value="{{ $pendidikan->tahun_lulus }}" min="1900" max="2099" step="1" maxlength="4" required>
                                </div>
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="row">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">Form data Pendidikan</div>
                    </div>
                    <div class="card-body">
                        @if (!$kesehatan)
                            <div class="alert alert-danger">Data kesehatan belum dilengkapi</div>
                        @endif
                        <div class="alert alert-secondary">Beri (-) jika inputan tidak diperlukan</div>
                        <form action="{{ (!$kesehatan ? '/karyawan/kesehatan/store' : 'karyawan/kesehatan/update/'. $pendidikan->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @if ($kesehatan)
                            @method('PUT')
                            @endif
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="golongan_darah" class="form-label">Golongan Darah</label>
                                    <input type="text" class="form-control @error('golongan_darah') is-invalid @enderror" id="golongan_darah" name="golongan_darah" value="{{ old('golongan_darah', (!$kesehatan ? '' : $kesehatan->golongan_darah)) }}" placeholder="Masukkan Golongan Darah">
                                    @error('golongan_darah')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror

                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="riwayat_alergi" class="form-label">Golongan Darah</label>
                                    <input type="text" class="form-control" id="riwayat_alergi" name="riwayat_alergi" value="{{ (!$kesehatan ? '' : $kesehatan->riwayat_alergi) }}" placeholder="Riwayat Alergi" >
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="riwayat_penyakit" class="form-label">Golongan Darah</label>
                                    <input type="text" class="form-control" id="riwayat_penyakit" name="riwayat_penyakit" value="{{ (!$kesehatan ? '' : $kesehatan->riwayat_penyakit) }}" placeholder="Riwayat Penyakit" >
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="riwayat_penyakit_lain" class="form-label">Golongan Darah</label>
                                    <input type="text" class="form-control" id="riwayat_penyakit_lain" name="riwayat_penyakit_lain" value="{{ (!$kesehatan ? '' : $kesehatan->riwayat_penyakit_lain) }}" placeholder="Riwayat Penyakit Lain" >
                                </div>
                                
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary">{{ (!$kesehatan ? 'Simpan' : 'Update') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Data Diri -->
<div class="modal fade" id="modalTambahDataDiri" tabindex="-1" aria-labelledby="modalTambahDataDiriLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTambahDataDiriLabel">Tambah Data Diri</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('datadiri.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nik" class="form-label">NIK</label>
                            <input type="text" class="form-control" id="nik" name="nik" maxlength="16" minlength="16"
                                placeholder="Masukkan NIK" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap"
                                placeholder="Masukkan Nama Lengkap" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                            <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir"
                                placeholder="Masukkan Tempat Lahir" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                            <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="alamat_ktp" class="form-label">Alamat KTP</label>
                            <textarea class="form-control" id="alamat_ktp" name="alamat_ktp"
                                placeholder="Masukkan Alamat KTP" required></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="alamat_domisili" class="form-label">Alamat Domisili</label>
                            <textarea class="form-control" id="alamat_domisili" name="alamat_domisili"
                                placeholder="Masukkan Alamat Domisili"></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="agama" class="form-label">Agama</label>
                            <select class="form-select" id="agama" name="agama" required>
                                <option value="">Pilih Agama</option>
                                <option value="Islam">Islam</option>
                                <option value="Kristen">Kristen</option>
                                <option value="Katolik">Katolik</option>
                                <option value="Hindu">Hindu</option>
                                <option value="Buddha">Buddha</option>
                                <option value="Konghucu">Konghucu</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                            <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="no_hp" class="form-label">No HP</label>
                            <input type="text" class="form-control" id="no_hp" name="no_hp" placeholder="Masukkan Nomor HP" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="foto_user" class="form-label">Foto Diri</label>
                            <input type="file" class="form-control" id="foto_user" name="foto_user" accept="image/*">
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Pendidikan -->
<div class="modal fade" id="modalTambahPendidikan" tabindex="-1" aria-labelledby="modalTambahPendidikanLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTambahPendidikanLabel">Tambah Data Pendidikan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('pendidikan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nama_sekolah" class="form-label">Nama Sekolah</label>
                            <input type="text" class="form-control" id="nama_sekolah" name="nama_sekolah"
                                placeholder="Masukkan Nama Sekolah" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="jurusan_sekolah" class="form-label">Jurusan Sekolah</label>
                            <input type="text" class="form-control" id="jurusan_sekolah" name="jurusan_sekolah"
                                placeholder="Masukkan Jurusan Sekolah" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="alamat_sekolah" class="form-label">Alamat Sekolah</label>
                            <textarea class="form-control" id="alamat_sekolah" name="alamat_sekolah"
                                placeholder="Masukkan Alamat Sekolah" required></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="tahun_mulai" class="form-label">Tahun Masuk</label>
                            <input type="number" class="form-control" id="tahun_mulai" name="tahun_mulai" min="1900"
                                max="2099" step="1" maxlength="4" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="tahun_lulus" class="form-label">Tahun Lulus</label>
                            <input type="number" class="form-control" id="tahun_lulus" name="tahun_lulus" min="1900"
                                max="2099" step="1" maxlength="4" required>
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection