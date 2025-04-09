@extends('layouts.main')

@section('content')
    <div class="container-fluid">
        <div class="card custom-card">
            <div class="card-header">
                <div class="card-title">
                    <h6>{{ $getDataUser->name }}</h6>
                    <span class="text-muted">{{ $getDataUser->dataDiri->kepegawaian->subJabatan->nama_sub_jabatan ?? '-' }}</span>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatable-basic" class="table table-bordered w-100">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Periode</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="mb-3">
            <a href="/manajer/laporan_kinerja" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
@endsection