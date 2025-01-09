@extends('layouts.main')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">List Role</div>
                </div>
                <div class="card-body">
                    <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal"
                        data-bs-target="#addRoleModal">
                        Tambah Role
                    </button>
                    <div class="table-responsive">
                        <table id="" class="table table-bordered text-nowrap w-100">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Role</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($roles as $key => $role)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $role->name }}</td>
                                        <td>
                                            @if($role->id != 1)
                                                <!-- Tombol Edit yang membuka modal -->
                                                <button type="button" class="btn btn-warning btn-icon"
                                                    data-bs-toggle="modal" data-bs-target="#editRoleModal"
                                                    data-id="{{ $role->id }}" data-name="{{ $role->name }}">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>

                                                <!-- Form hapus role -->
                                                <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST"
                                                    style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-icon"
                                                        onclick="return confirm('Are you sure you want to delete this role?')">
                                                        <i class="bi bi-trash"></i>
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

<!-- Modal Add Role -->
<div class="modal fade" id="addRoleModal" tabindex="-1" aria-labelledby="addRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addRoleModalLabel">Tambah Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.roles.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="name" class="form-label">Role Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter role name"
                            required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Tambah Role</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Role -->
<div class="modal fade" id="editRoleModal" tabindex="-1" aria-labelledby="editRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editRoleModalLabel">Edit Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form untuk edit role -->
                <form action="{{ route('admin.roles.update', 'id') }}" method="POST" id="editRoleForm">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="role-name" class="form-label">Role Name</label>
                        <input type="text" class="form-control" id="role-name" name="name" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    // Update form action for the Edit Role modal
    $('#editRoleModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var roleId = button.data('id'); // Extract info from data-* attributes
        var roleName = button.data('name');

        // Update the modal's content.
        var modal = $(this);
        modal.find('.modal-body #role-name').val(roleName);
        modal.find('form').attr('action', '/roles/' + roleId);
    });
</script>
@endsection