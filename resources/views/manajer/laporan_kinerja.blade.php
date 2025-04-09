@extends('layouts.main')

@section('content')
    <div class="container-fluid">
        <div class="card custom-card">
            <div class="card-header">
                <div class="card-title">
                    Laporan Kinerja
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatable-basic" class="table table-bordered w-100">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Task</th>
                                <th>Tipe Task</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
