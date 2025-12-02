@extends('layouts.main')

@section('content')
    <div class="container-fluid">
        <div class="card custom-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="card-title mb-0">
                    Laporan Kinerja
                </div>
                <a href="{{ route('manajer.laporan_kinerja.pending') }}" class="btn btn-warning text-white btn-sm shadow-sm">
                    <i class="fas fa-clock me-1"></i> Pending Approvals
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatable-basic" class="table table-bordered w-100">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Jabatan</th>
                                <th>Jumlah Project</th>
                                <th>Jumlah Task</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($getDataUser as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->dataDiri->kepegawaian->subJabatan->nama_sub_jabatan ?? '-' }}</td>
                                    <td>{{ $item->users_project->count() ? $item->users_project->count() : 0 }} Project</td>
                                    <td>{{ $item->users_task->count() ? $item->users_task->count() : 0 }} Task</td>
                                    <td class="text-center">
                                        <a href="{{ route('manajer.list.laporan_kinerja', $item->id) }}" 
                                            class="btn btn-secondary btn-sm" 
                                            data-bs-toggle="tooltip"
                                            data-bs-custom-class="tooltip-secondary"
                                            data-bs-placement="top" title="Detail Laporan Kinerja!"><i
                                                class='bx bx-detail'></i>
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
