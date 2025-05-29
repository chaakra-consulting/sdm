@extends('layouts.main')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-4 col-md-4">
                <div class="card bg-primary-gradient">
                    <div class="card-body">
                        <div id="myCarousel" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                <div class="carousel-item active flex-column">
                                    <i class="bi bi-files fs-30 text-fixed-white mb-2"></i>
                                    <h4 class="text-fixed-white">Jumlah Project <br>
                                        <span class="font-bold">
                                            {{ $getDataUser->users_project->count() 
                                            ? $getDataUser->users_project->count() : 0 }}
                                        </span>
                                    </h4>
                                </div>
                                <div class="carousel-item flex-column">
                                    <i class="bi bi-file-earmark-check fs-30 text-fixed-white mb-2"></i>
                                    <h4 class="text-fixed-white">Project Selesai<br>
                                        <span class="font-bold">
                                            {{ $getDataUser->users_project()
                                            ->whereHas('project_perusahaan', function($q) {
                                                $q->where('status', 'selesai');
                                            })->count() }}
                                        </span>
                                    </h4>
                                </div>
                                <div class="carousel-item flex-column">
                                    <i class="bi bi-file-earmark fs-30 text-fixed-white mb-2"></i>
                                    <h4 class="text-fixed-white">Project belum selesai<br>
                                        <span class="font-bold">
                                            {{ $getDataUser->users_project()
                                            ->whereHas('project_perusahaan', function($q) {
                                                $q->where('status', '!=', 'selesai');
                                            })->count() }}
                                        </span>
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4">
                <div class="card bg-danger-gradient">
                    <div class="card-body">
                        <div id="myCarousel0" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                <div class="carousel-item active flex-column">
                                    <i class="bi bi-files fs-30 text-fixed-white mb-2"></i>
                                    <h4 class="text-fixed-white">Jumlah Task <br>
                                        <span class="font-bold">
                                            {{ $getDataUser->users_task->count() 
                                            ? $getDataUser->users_task->count() : 0 }}
                                        </span>
                                    </h4>
                                </div>
                                <div class="carousel-item flex-column">
                                    <i class="bi bi-file-earmark-check fs-30 text-fixed-white mb-2"></i>
                                    <h4 class="text-fixed-white">Task Selesai<br>
                                        <span class="font-bold">
                                            {{ $getDataUser->users_task()
                                            ->whereHas('task', function($q) {
                                                $q->where('status', 'selesai');
                                            })->count() }}
                                        </span>
                                    </h4>
                                </div>
                                <div class="carousel-item flex-column">
                                    <i class="bi bi-file-earmark fs-30 text-fixed-white mb-2"></i>
                                    <h4 class="text-fixed-white">Task belum selesai<br>
                                        <span class="font-bold">
                                            {{ $getDataUser->users_task()
                                            ->whereHas('task', function($q) {
                                                $q->where('status', '!=', 'selesai');
                                            })->count() }}
                                        </span>
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- col -->
            <div class="col-lg-4 col-md-4">
                <div class="card bg-purple-gradient">
                    <div class="card-body">
                        <div id="myCarousel1" class="carousel slide" data-bs-ride="carousel">
                            <!-- Carousel items -->
                            <div class="carousel-inner">
                                <div class="carousel-item active flex-column">
                                    <i class="bi bi-files fs-30 text-fixed-white mb-2"></i>
                                    <h4 class="text-fixed-white">Jumlah Sub Taks <br>
                                        <span class="font-bold">
                                            {{ $getDataUser->users_task->sum(function($userTask) {
                                                return $userTask->subtask->count();
                                            }) }}
                                        </span>
                                    </h4>
                                </div>
                                <div class="carousel-item flex-column">
                                    <i class="bi bi-file-earmark-check fs-30 text-fixed-white mb-2"></i>
                                    <h4 class="text-fixed-white">Sub Task Approve <br>
                                        <span class="font-bold">
                                            {{ $getDataUser->users_task->sum(fn($ut) => 
                                                $ut->subtask->where(
                                                    'status', 
                                                    'approve')->count()) }}
                                        </span>
                                    </h4>
                                </div>
                                <div class="carousel-item flex-column">
                                    <i class="bi bi-file-earmark fs-30 text-fixed-white mb-2"></i>
                                    <h4 class="text-fixed-white">Sub Task belum approve<br>
                                        <span class="font-bold">
                                            {{ $getDataUser->users_task->sum(fn($ut) => 
                                                $ut->subtask->where(
                                                    'status', 
                                                    '!=', 
                                                    'approve')->count()) }}
                                        </span>
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
                            @foreach ($groupedLaporan as $periodeKey => $data)
                                @php
                                    $start = $data['start'];
                                    $end = $data['end'];
                                    $statusPeriode = $statuses[$periodeKey] ?? [
                                        'perlu_revisi' => false,
                                        'belum_dikirim' => false,
                                        'semua_approve' => false,
                                        'semua_dikirim' => false,
                                    ];
                                @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        {{ $start->translatedFormat('d F Y') }} - 
                                        {{ $end->translatedFormat('d F Y') }}
                                    </td>
                                    <td>
                                        @if($statusPeriode['perlu_revisi'])
                                            <span class="badge bg-warning">Perlu Revisi</span>
                                        @elseif($statusPeriode['semua_approve'])
                                            <span class="badge bg-success">Approved</span>
                                        @elseif($statusPeriode['semua_dikirim'])
                                            <span class="badge bg-info">Menunggu Approval</span>
                                        @elseif($statusPeriode['belum_dikirim'])
                                            <span class="badge bg-warning">Laporan Belum dikirim</span>
                                        @else
                                            <span class="badge bg-secondary">Belum Dicek</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('manajer.detail.laporan_kinerja', [
                                            'id' => $getDataUser->id,
                                            'periode' => $periodeKey
                                        ]) }}" 
                                            class="btn btn-secondary btn-sm">
                                            <i class='bx bx-detail'></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="mb-3">
            <a href="/manajer/laporan_kinerja" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>
@endsection