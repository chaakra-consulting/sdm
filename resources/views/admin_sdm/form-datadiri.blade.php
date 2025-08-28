@extends('layouts.main')

@section('content')

    <div class="container-fluid">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <!-- Start Form Data Diri -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    {{-- <center>
                    <div class="card-header justify-content-between">
                            <div class="card-title">Data Diri</div>
                    </div>
                    </center> --}}
                    <div class="card-body">
                        <center>
                            <div class="card-title">Data Diri</div>
                        </center>
                        <form action="{{ route('admin_sdm.datadiri.store_sdm') }}" method="POST" enctype="multipart/form-data">                        
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nik" class="form-label">NIK</label>
                                <input type="text" class="form-control" id="nik" name="nik"
                                     maxlength="16" minlength="16"
                                    placeholder="Masukkan NIK" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap"
                                     placeholder="Masukkan Nama Lengkap" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="nip" class="form-label">NIP</label>
                                <input type="text" class="form-control" id="nip" name="nip">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                                <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir"
                                    placeholder="Masukkan Tempat Lahir" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="tgl_lahir" class="form-label">Tanggal Lahir</label>
                                <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir"
                                    placeholder="Pilih Tanggal Lahir" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="alamat_ktp" class="form-label">Alamat KTP</label>
                                <textarea class="form-control" id="alamat_ktp" name="alamat_ktp" placeholder="Masukkan Alamat KTP" required></textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="alamat_domisili" class="form-label">Alamat Domisili</label>
                                <textarea class="form-control" id="alamat_domisili" name="alamat_domisili" placeholder="Masukkan Alamat Domisili"></textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="agama" class="form-label">Agama</label>
                                <select class="form-select" id="agama" name="agama" required>
                                    <option value="">Pilih Agama</option>
                                    <option value="Islam">Islam
                                    </option>
                                    <option value="Kristen">
                                        Kristen
                                    </option>
                                    <option value="Katolik">
                                        Katolik
                                    </option>
                                    <option value="Hindu">
                                        Hindu
                                    </option>
                                    <option value="Buddha">
                                        Buddha
                                    </option>
                                    <option value="Konghucu">
                                        Konghucu</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                                <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="Laki-laki">Laki-laki
                                    </option>
                                    <option value="Perempuan">Perempuan
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="no_hp" class="form-label">No HP</label>
                                <input type="text" class="form-control" id="no_hp" name="no_hp"
                                    placeholder="Masukkan Nomor HP" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="hubungan_emergency" class="form-label">Hubungan Emergency</label>
                                <select class="form-select" id="hubungan_emergency" name="hubungan_emergency"
                                    required>
                                    <option value="">Pilih Hubungan Emergency</option>
                                    <option value="Bapak">
                                        Bapak
                                    </option>
                                    <option value="Ibu">
                                        Ibu
                                    </option>
                                    <option value="Suami">
                                        Suami
                                    </option>
                                    <option value="Istri">
                                        Istri
                                    </option>
                                    <option value="Saudara Kandung">
                                        Saudara Kandung
                                    </option>
                                    <option value="Lainnya">
                                        Lainnya
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="nama_emergency" class="form-label">Nama Emergency</label>
                                <input type="text" class="form-control" id="nama_emergency" name="nama_emergency"
                                    placeholder="Masukkan Nomor HP" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="no_emergency" class="form-label">No HP Emergency</label>
                                <input type="text" class="form-control" id="no_emergency" name="no_emergency"
                                    placeholder="Masukkan Nomor HP" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email_nonchaakra" class="form-label">Email Non Chaakra</label>
                                <input type="email" class="form-control" id="email_nonchaakra"
                                    name="email_nonchaakra" placeholder="Masukkan Email Non Chaakra" required>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="status_pernikahan" class="form-label">Status Pernikahan</label>
                                <select name="status_pernikahan" id="status_pernikahan" class="form-control">
                                    <option
                                        value="lajang">Lajang</option>
                                    <option
                                        value="menikah">Menikah</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="foto_user" class="form-label">Foto Diri</label>
                                <input type="file" class="form-control" id="foto_user" name="foto_user"
                                    accept="image/*" onchange="validateFile(this, 'preview_user', 'error_user')">
                                <img id="preview_user"
                                    {{-- src="{{ $datadiri->foto_user != null ? asset('uploads/' . $datadiri->foto_user) : 'https://t4.ftcdn.net/jpg/02/15/84/43/360_F_215844325_ttX9YiIIyeaR7Ne6EaLLjMAmy4GvPC69.jpg' }}" --}}
                                    alt="Preview Foto User" style="max-width: 150px; margin-top: 10px;">
                                <small id="error_user" class="text-danger" style="display: none;"></small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="foto_ktp" class="form-label">Foto KTP</label>
                                <input type="file" class="form-control" id="foto_ktp" name="foto_ktp"
                                    accept="image/*" onchange="validateFile(this, 'preview_ktp', 'error_ktp')">
                                <img id="preview_ktp"
                                    {{-- src="{{ $datadiri->foto_ktp != null ? asset('uploads/' . $datadiri->foto_ktp) : 'https://t4.ftcdn.net/jpg/02/15/84/43/360_F_215844325_ttX9YiIIyeaR7Ne6EaLLjMAmy4GvPC69.jpg' }}" --}}
                                    alt="Preview Foto KTP" style="max-width: 150px; margin-top: 10px;">
                                <small id="error_ktp" class="text-danger" style="display: none;"></small>
                            </div>
                            <center>                            
                                <div class="card-title">Pendidikan</div>
                            </center>
                            <div class="col-md-6 mb-3">
                                <label for="nama_sekolah" class="form-label">Nama Sekolah</label>
                                <input type="text" class="form-control" id="nama_sekolah" name="nama_sekolah"
                                    placeholder="Masukkan Nama Sekolah"
                                    required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="jurusan_sekolah" class="form-label">Jurusan Sekolah</label>
                                <input type="text" class="form-control" id="jurusan_sekolah"
                                    name="jurusan_sekolah"
                                    placeholder="Masukkan Jurusan Sekolah" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="alamat_sekolah" class="form-label">Alamat Sekolah</label>
                                <textarea class="form-control" id="alamat_sekolah" name="alamat_sekolah" placeholder="Masukkan Alamat Sekolah"
                                    required></textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="tahun_mulai" class="form-label">Tahun Masuk</label>
                                <input type="number" class="form-control" id="tahun_mulai" name="tahun_mulai"
                                    min="1900" max="2099"
                                    step="1" maxlength="4" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="tahun_lulus" class="form-label">Tahun Lulus</label>
                                <input type="number" class="form-control" id="tahun_lulus" name="tahun_lulus"
                                     min="1900" max="2099"
                                    step="1" maxlength="4" required>
                            </div>
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">Kirim</button>
                            </div>
                        </div>
                        </form>
                        <script>
                            function previewImage(input, previewId) {
                                const file = input.files[0];
                                if (file) {
                                    const reader = new FileReader();
                                    reader.onload = function(e) {
                                        const preview = document.getElementById(previewId);
                                        preview.src = e.target.result;
                                    };
                                    reader.readAsDataURL(file);
                                }
                            }
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
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
</script>
