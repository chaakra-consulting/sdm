@extends('layouts.main')

@section('content')
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="staticBackdropLabel"></h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="POST" id="formDaftarPerusahaan">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nama_perusahaan">Nama Instansi</label>
                            <input type="text" name="nama_perusahaan" id="nama_perusahaan" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="alamat">Alamat</label>
                            <textarea class="form-control" name="alamat" id="alamat" cols="30" rows="10"></textarea>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-7">
                                <label for="nama_pimpinan">Nama Pimpinan</label>
                                <input type="text" name="nama_pimpinan" id="nama_pimpinan" class="form-control">
                            </div>
                            <div class="col-md-5">
                                <label for="gender">Gender</label>
                                <select class="form-select" aria-label="gender" name="gender" id="gender">
                                    <option selected disabled>Pilih Gender</option>
                                    <option value="Laki-Laki">Laki-Laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="kontak">Kontak</label>
                            <input type="text" name="kontak" id="kontak" class="form-control">
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
    <div class="container-fluid">
        <div class="mb-2">
            <button type="button" class="btn btn-primary tambahDaftarPerusahaan" data-bs-toggle="modal"
                data-bs-target="#staticBackdrop">
                Tambah Instansi
            </button>
            <a href="{{ route('manajer.transfer.data') }}" class="btn btn-success" data-bs-toggle="tooltip"
                data-bs-custom-class="tooltip-success" data-bs-placement="top" title="Transfer Data Instansi!">
                <i class="typcn typcn-cloud-storage" 
                ></i>
            </a>
        </div>
        <div class="card custom-card">
            <div class="card-header">
                <div class="card-title">
                    Daftar Instansi
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatable-basic" class="table table-bordered w-100">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Instansi</th>
                                <th>Alamat</th>
                                <th>Nama Pimpinan</th>
                                <th>Kontak</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($perusahaan as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->nama_perusahaan }}</td>
                                    <td>{{ $item->alamat }}</td>
                                    <td>
                                        {{ $item->nama_pimpinan }}
                                        <br>
                                        {{ $item->gender != null ? '(' . $item->gender . ')' : '' }}
                                    </td>
                                    <td>{{ $item->kontak }}</td>
                                    <td class="text-nowrap">
                                        <a href="" class="btn btn-warning editDaftarPerusahaan"
                                            data-bs-toggle="modal" data-bs-target="#staticBackdrop"
                                            data-id="{{ $item->id }}"
                                            data-nama_perusahaan="{{ $item->nama_perusahaan }}"
                                            data-alamat="{{ $item->alamat }}"
                                            data-nama_pimpinan="{{ $item->nama_pimpinan }}"
                                            data-kontak="{{ $item->kontak }}" data-gender="{{ $item->gender }}">
                                            <i class="fas fa-edit" data-bs-toggle="tooltip"
                                                data-bs-custom-class="tooltip-secondary" data-bs-placement="top"
                                                title="Update Instansi!">
                                            </i>
                                        </a>
                                        <form action="{{ route('manajer.delete.perusahaan', $item->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger delete-perusahaan"
                                                data-id="{{ $item->id }}"
                                                data-nama_perusahaan="{{ $item->nama_perusahaan }}"
                                                data-bs-toggle="tooltip" data-bs-custom-class="tooltip-danger"
                                                data-bs-placement="top" title="Hapus Instansi!">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
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
        $(document).ready(function() {
            $(".tambahDaftarPerusahaan").click(function() {
                $(".modal-title").text('Tambah Daftar Instansi');
                $("#nama_perusahaan").val('');
                $("#alamat").val('');
                $("#nama_pimpinan").val('');
                $("#gender").change().val('Pilih Gender');
                $("#kontak").val('');
                $("#formDaftarPerusahaan").attr('action', '/manajer/perusahaan/store');

                $("#formDaftarPerusahaan input[name='_method']").remove();
            });

            $(document).on('click', '.editDaftarPerusahaan', function(e) {
                e.preventDefault();
                $(".modal-title").text('Edit Daftar Instansi');
                $("#nama_perusahaan").val($(this).data('nama_perusahaan'));
                $("#alamat").val($(this).data('alamat'));
                $("#nama_pimpinan").val($(this).data('nama_pimpinan'));
                $("#gender").val($(this).data('gender')).change();
                $("#kontak").val($(this).data('kontak'));

                $("#formDaftarPerusahaan input[name='_method']").remove();
                $("#formDaftarPerusahaan").append('<input type="hidden" name="_method" value="PUT">');
                $("#formDaftarPerusahaan").attr('action', '/manajer/perusahaan/update/' + $(this).data(
                    'id'));
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $(document).on("click", ".delete-perusahaan", function(e) {
                e.preventDefault();

                let perusahaanId = $(this).data("id");
                let perusahaanName = $(this).data("nama_perusahaan");

                Swal.fire({
                    title: "Konfirmasi Hapus Instansi",
                    text: "Apakah kamu yakin ingin menghapus instansi '" + perusahaanName + "'?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#cf0202",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Ya, Hapus!",
                    cancelButtonText: "Batal"
                }).then((result) => {
                    if (result.isConfirmed) {
                        let form = $("<form>", {
                            action: "/manajer/perusahaan/delete/" + perusahaanId,
                            method: "POST"
                        }).append(
                            $("<input>", {
                                type: "hidden",
                                name: "_token",
                                value: "{{ csrf_token() }}"
                            }),
                            $("<input>", {
                                type: "hidden",
                                name: "_method",
                                value: "DELETE"
                            })
                        );

                        $("body").append(form);
                        form.submit();
                    }
                });
            });
        });
    </script>
@endsection
