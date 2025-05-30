@extends('layouts.main')
@section('content')
    <div class="modal fade" id="revisiModal" tabindex="-1" aria-labelledby="revisiModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('manajer.revise.laporan_kinerja', $getDataUser->id) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Revisi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="periode" value="{{ $periode }}">
                        <div class="mb-3">
                            <label>Pesan Revisi</label>
                            <textarea name="pesan_revisi" class="form-control" rows="4" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-warning">Kirim Revisi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="card custom-card">
            <div class="card-header d-flex justify-content-between">
                <div class="card-title">
                    <h6>{{ $getDataUser->name }}</h6>
                    <span class="text-muted">{{ $getDataUser->dataDiri->kepegawaian->subJabatan->nama_sub_jabatan ?? '-' }}</span>
                </div>
                <div class="d-flex gap-2">
                    <form action="{{ route('manajer.approve.laporan_kinerja', $getDataUser->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="periode" value="{{ $periode }}">
                        <button type="submit" class="btn btn-outline-primary">Approve</button>
                    </form>
                    <button class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#revisiModal">Revise</button>
                </div>
            </div>
            <div class="card-body">
                @if($periode && $periode !== 'invalid_date')
                    @php
                        [$start, $end] = explode('_', $periode);
                    @endphp
                    <div class="text-muted mb-1">
                        Periode: 
                        {{ \Carbon\Carbon::parse($start)->translatedFormat('d F Y') }} s/d
                        {{ \Carbon\Carbon::parse($end)->translatedFormat('d F Y') }}
                    </div>
                @endif
                <div class="table-responsive">
                    <table id="datatable-basic" class="table table-bordered w-100 text-nowrap">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Sub Task (Task - Tipe Task)</th>
                                <th>Project (intansi)</th>
                                <th>Durasi</th>
                                <th>Keterangan</th>
                                <th>Status</th>
                                <th>Lampiran</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no = 1;
                            @endphp
                            @foreach ($getDataLaporan as $subtask)
                                @foreach ($subtask->detail_sub_task as $item)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('l, d F Y') }}</td>
                                    <td>{{ $subtask->nama_subtask ?? '' }} 
                                        ({{ $subtask->task->nama_task ?? '' }} - 
                                        {{ $subtask->task->tipe_task->nama_tipe ?? '' }})</td>
                                    <td>{{ $subtask->task->project_perusahaan->nama_project ?? '' }} 
                                        ({{ $subtask->task->project_perusahaan->perusahaan->nama_perusahaan ?? '' }})</td>
                                    <td>
                                        {{ floor($item->durasi / 60) }} Jam, {{ $item->durasi % 60 }} Menit
                                    </td>
                                    <td>{{ $item->keterangan ?? '-' }}</td>
                                    <td class="text-center">
                                        @if($subtask->status === 'approve')
                                            <span class="badge bg-success">Approve</span>
                                        @elseif($subtask->status === 'revise')
                                            <span class="badge bg-warning">Revise</span>
                                        @else
                                            <span class="badge bg-secondary">Belum Dicek</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($subtask->lampiran->count() > 0)
                                            <button type="button" 
                                            class="btn btn-primary btn-sm" 
                                            data-bs-toggle="tooltip" 
                                            data-bs-custom-class="tooltip-primary"
                                            data-bs-placement="top" title="Lihat Lampiran!" 
                                            onclick="previewLampiran({{ $subtask->id }}, {{ $subtask->lampiran }})">
                                                <i class="ti ti-file-search"></i>
                                            </button>
                                        @else
                                            <span class="text-muted">Tidak Ada Lampiran</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-end"></th>
                                <th colspan="1">Total Durasi:</th>
                                <th colspan="1" class="text-center">
                                    {{ $durasiJam }} Jam, {{ $durasiMenit }} Menit
                                </th>
                                <th colspan="3"></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="mb-3">
            <a href="{{ route('manajer.list.laporan_kinerja', $getDataUser->id) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>
@endsection