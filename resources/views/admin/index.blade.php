@extends('layouts.main')

@section('content')

<!-- Start:: row-4 -->
<div class="container-fluid">

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">List User</div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="file-export" class="table table-bordered text-nowrap w-100">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>tgl Daftar</th>
                                    <th>Jabatan (Role User)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->role_id ? $user->role->name : 'No Role Assigned' }}</td>
                                        <td>{{ $user->created_at->format('Y-m-d') }}</td>
                                        <td>
                                            @if($user->role_id != 1)
                                                <!-- Form untuk Update Role -->
                                                <form action="{{ route('admin.users.update-role', $user->id) }}" method="POST">
                                                    @csrf
                                                    <div class="input-group">
                                                        <!-- Dropdown Role -->
                                                        <select name="role_id" class="form-select" required>
                                                            <option selected disabled>Select Role</option>
                                                            @foreach ($roles as $role)
                                                                @if($role->id != 1)
                                                                    <option value="{{ $role->id }}" {{ $user->role_id && $user->role->id == $role->id ? 'selected' : '' }}>
                                                                        {{ $role->name }}
                                                                    </option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                        <!-- Button Submit -->
                                                        <button type="submit" class="btn btn-primary ms-2">Update</button>
                                                    </div>
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
 