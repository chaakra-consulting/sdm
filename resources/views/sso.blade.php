@extends('layouts.main')

@section('content')

<div class="container-fluid">
    <div class="card custom-card">
        @if ($ssoData->success == false)
        <div class="card-body">
            <div class="alert alert-warning text-center">Silahkan sync menggunakan akun SSO yang terdaftar</div>
            <form action="/sso/store" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" class="form-control">
                    </div>
                    <div class="mt-2">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-sync"></i> Sync</button>
                    </div>
            </form>
        </div>
        @else
        <div class="card-body">
            <img src="https://cdni.iconscout.com/illustration/premium/thumb/check-list-illustration-download-in-svg-png-gif-file-formats--business-task-daily-work-management-pack-people-illustrations-4452998.png?f=webp" alt="" srcset="" width="200">
            <div class="mt-2">
                <h4>Sync Akun SSO Chaakra Sudah Terkait</h4>
            </div>
        </div>
        @endif
    </div>
</div>

@endsection