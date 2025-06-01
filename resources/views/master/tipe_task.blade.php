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
                <form action="" method="POST" id="formTipeTask">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nama_tipe">Nama Tipe Task <br><small class="text-danger">Contoh Penamaan (Task Project)</small></label>
                            <input type="text" name="nama_tipe" id="nama_tipe" class="form-control"
                                placeholder="Task ...." required>
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
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="mb-3">
                    <button type="button" class="btn btn-primary tambahTipeTask" data-bs-toggle="modal"
                        data-bs-target="#staticBackdrop">
                        Tambah Tipe Task
                    </button>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Tipe Task</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="datatable-basic" class="table table-bordered text-nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Tipe Task</th>
                                        <th>aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($tipe as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->nama_tipe }}</td>
                                            <td>
                                                @if ($item->slug != 'task-project' && $item->slug != 'task-wajib')
                                                    <a href="javascript:void(0);" class="btn btn-warning editTipeTask" data-bs-toggle="modal"
                                                        data-bs-target="#staticBackdrop" data-id="{{ $item->id }}"
                                                        data-nama_tipe="{{ $item->nama_tipe }}">
                                                        <i data-bs-toggle="tooltip" data-bs-custom-class="tooltip-secondary" data-bs-placement="top" 
                                                        title="Edit Tipe Task!" class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="/manajer/tipe_task/delete/{{ $item->id }}" method="POST"
                                                        class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger delete" 
                                                        data-id="{{ $item->id }}" data-nama_tipe="{{ $item->nama_tipe }}" data-bs-toggle="tooltip" 
                                                        data-bs-custom-class="tooltip-danger" data-bs-placement="top" title="Hapus Tipe Task!">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
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
            $(".tambahTipeTask").click(function() {
                $(".modal-title").text('Tambah Tipe Task');
                $("#formTipeTask").attr('action', '/manajer/tipe_task/store');
            })
            $(".editTipeTask").click(function(e) {
                e.preventDefault();
                $(".modal-title").text('Edit Nama Divisi');
                $("#nama_tipe").val($(this).data('nama_tipe'));

                $("#formTipeTask").append('<input type="hidden" name="_method" value="PUT">');
                $("#formTipeTask").attr('action', '/manajer/tipe_task/update/' + $(this).data('id'));
            })
        })
    </script>
    
    <script>
        $(document).ready(function() {
            $(".delete").click(function(e) {
                e.preventDefault();

                let TipeId = $(this).data("id");
                let TipeName = $(this).data("nama_tipe");

                Swal.fire({
                    title: "Konfirmasi Hapus Tipe Task",
                    text: "Apakah kamu yakin ingin menghapus Tipe Task '" + TipeName + "'?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Ya, Hapus!",
                    cancelButtonText: "Batal"
                }).then((result) => {
                    if (result.isConfirmed) {
                        let form = $('<form action="/manajer/tipe_task/delete/' + TipeId +
                            '" method="POST">' +
                            '@csrf' +
                            '@method('DELETE')' +
                            '</form>');

                        $('body').append(form);
                        form.submit();
                    }
                });
            });
        });
    </script>
@endsection