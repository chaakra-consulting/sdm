@extends('layouts.main')

@section('content')

<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="staticBackdropLabel">Modal title
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <form action="" method="POST" id="formAbsensi">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label for="hari">Hari</label>
                    <input type="text" name="hari" id="hari" value= "hari"class="form-control" disabled>
                </div>
                <div class="form-group">
                    <label for="is_libur">Apakah Libur?</label>
                    <input type="checkbox" name="is_libur" id="is_libur" class="form-check-input">
                    <small id="liburStatus" class="text"></small>
                </div>
                
                <div class="form-group">
                    <label for="waktu_masuk">Waktu Masuk</label>
                    <input type="time" name="waktu_masuk" id="waktu_masuk" value= "waktu_masuk" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="waktu_pulang">Waktu Pulang</label>
                    <input type="time" name="waktu_pulang" id="waktu_pulang" value= "waktu_pulang"class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="batas_waktu_terlambat">Batas Waktu Terlambat</label>
                    <input type="time" name="batas_waktu_terlambat" id="batas_waktu_terlambat" value= "batas_waktu_terlambat"class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="denda_terlambat">Denda Terlambat</label>
                    <input type="number" name="denda_terlambat" id="denda_terlambat" value= "denda_terlambat"class="form-control" required>
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

<div class="container-fluid">
    {{-- <div class="mb-2">
        <button type="button" class="btn btn-primary tambahAbsensi" data-bs-toggle="modal"
        data-bs-target="#staticBackdrop">
        Tambah Jabatan
        </button>
    </div> --}}
    <div class="card custom-card">
        <div class="card-header">
            <div class="card-title">
                Master Absensi
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table
                    id="datatable-basic"
                    class="table table-bordered text-nowrap w-100"
                >
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Hari</th>
                            <th>Waktu Masuk</th>
                            <th>Waktu Pulang</th>
                            <th>Batas Waktu Terlambat</th>
                            <th>Denda Terlambat</th>
                            <th>Status Libur</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($absensi as $row)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ ucwords($row->hari) }}</td>
                                <td>{{ $row->waktu_masuk }}</td>
                                <td>{{ $row->waktu_pulang }}</td>
                                <td>{{ $row->batas_waktu_terlambat }}</td>
                                <td>{{ $row->denda_terlambat }}</td>
                                <td>{{ $row->is_libur ? 'Ya' : 'Tidak' }}</td>
                                <td>
                                    <a href="" class="btn btn-warning editAbsensi" data-bs-toggle="modal"
                                        data-bs-target="#staticBackdrop" data-id="{{ $row->id }}" data-hari="{{ $row->hari }}" data-waktu_masuk="{{ $row->waktu_masuk }}" data-waktu_pulang="{{ $row->waktu_pulang }}" data-batas_waktu_terlambat="{{ $row->batas_waktu_terlambat }}" data-denda_terlambat="{{ $row->denda_terlambat }}" data-is_libur="{{ $row->is_libur }}"><i class="fas fa-edit"></i>
                                    </a>
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
    $(document).ready(function(){
      $(".editAbsensi").click(function(e){
          e.preventDefault();
        $(".modal-title").text('Edit Sub Jabatan');
        $("#hari").val($(this).data('hari').charAt(0).toUpperCase()+ $(this).data('hari').slice(1));
        $("#waktu_masuk").val($(this).data('waktu_masuk'));
        $("#waktu_pulang").val($(this).data('waktu_pulang'));
        $("#batas_waktu_terlambat").val($(this).data('batas_waktu_terlambat'));
        $("#denda_terlambat").val($(this).data('denda_terlambat'));

        const isLibur = $(this).data('is_libur');
        $("#is_libur").prop('checked', isLibur);

        $("#formAbsensi").append('<input type="hidden" name="_method" value="PUT">');
        $("#formAbsensi").attr('action', '/admin_sdm/absensi/update/' + $(this).data('id'));
      })
    })

    $(document).ready(function () {
        $(".editAbsensi").click(function (e) {
            e.preventDefault();

            const isLibur = $(this).data('is_libur');
            $("#is_libur").prop('checked', isLibur);

            const statusText = isLibur ? 'Ya' : 'Tidak';
            $("#liburStatus").text(statusText);
        });

        $("#is_libur").on('change', function () {
            const statusText = $(this).is(':checked') ? 'Ya' : 'Tidak';
            $("#liburStatus").text(statusText);
        });
    });


    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.editAbsensi').forEach(function(button) {
            button.addEventListener('click', function() {
                const modal = document.querySelector('#staticBackdrop');
                const isLiburCheckbox = modal.querySelector('#is_libur');
                
                const formFields = [
                    'waktu_masuk', 'waktu_pulang', 'batas_waktu_terlambat', 'denda_terlambat'
                ];

                modal.querySelector('#waktu_masuk').value = button.getAttribute('data-waktu_masuk');
                modal.querySelector('#waktu_pulang').value = button.getAttribute('data-waktu_pulang');
                modal.querySelector('#batas_waktu_terlambat').value = button.getAttribute('data-batas_waktu_terlambat');
                modal.querySelector('#denda_terlambat').value = button.getAttribute('data-denda_terlambat');
                modal.querySelector('#is_libur').checked = button.getAttribute('data-is_libur') === '1';
                
                function toggleFormFields() {
                    formFields.forEach(fieldId => {
                        const field = modal.querySelector(`#${fieldId}`);
                        if (isLiburCheckbox.checked) {
                            field.disabled = true;
                            field.closest('.form-group').style.display = 'none';
                        } else {
                            field.disabled = false;
                            field.closest('.form-group').style.display = '';
                        }
                    });
                }

                isLiburCheckbox.addEventListener('change', toggleFormFields);

                toggleFormFields();
            });
        });
    });  
</script>
@endsection