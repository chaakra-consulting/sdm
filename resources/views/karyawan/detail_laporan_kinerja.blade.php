@extends('layouts.main')

@section('content')
    @foreach ($detailSubtasks as $item)
        @if ($item->subtask->lampiran->count())
        <div class="modal fade" id="lampiranModal{{ $item->id }}" tabindex="-1" aria-labelledby="lampiranModalLabel{{ $item->id }}" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Lampiran {{ $loop->iteration }} Sub Task - {{ $item->subtask->task->nama_task }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                @if ($item->subtask->lampiran->count())
                <div id="carouselLampiran{{ $item->id }}" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                    @foreach ($item->subtask->lampiran as $key => $lampiran)
                        @php
                            $file = $lampiran->lampiran;
                            $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                            $isImage = in_array($extension, ['jpg', 'jpeg', 'png']);
                            $isPDF = $extension === 'pdf';
                        @endphp
                        <div class="carousel-item {{ $key === 0 ? 'active' : '' }}">
                        @if ($isImage)
                            <img src="{{ asset('uploads/' . $file) }}" class="d-block mx-auto img-fluid" style="max-height: 500px;">
                        @elseif ($isPDF)
                            <iframe src="{{ asset('uploads/' . $file) }}" class="d-block mx-auto" width="100%" height="500px"></iframe>
                        @else
                            <div class="text-center text-muted">File tidak bisa dipreview: <a href="{{ asset('uploads/' . $file) }}" target="_blank">Download</a></div>
                        @endif
                        </div>
                    @endforeach
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselLampiran{{ $item->id }}" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselLampiran{{ $item->id }}" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                </div>
                @else
                    <p class="text-center text-muted">Tidak ada lampiran yang tersedia</p>
                @endif
                </div>
            </div>
            </div>
        </div>          
        @endif
    @endforeach
    <div class="container-fluid">
        <div class="card custom-card">
            <div class="card-header d-flex justify-content-between align-items-start flex-wrap">
                <div class="card-title">
                    <h6 class="mb-0">{{ $getDataUser->name }}</h6>
                    <span class="text-muted">{{ $getDataUser->dataDiri->kepegawaian->subJabatan->nama_sub_jabatan ?? '-' }}</span>
                </div>
                <div class="d-flex justify-content-end mb-3">
                    @if (Auth::check() && Auth::user()->role->slug == 'karyawan')
                        <form action="{{ route('karyawan.laporan_kinerja.detail', [
                            'id' => auth()->user()->id,
                            'month' => $selectedMonth,
                            'year' => $selectedYear
                        ]) }}" method="GET" class="d-flex gap-2">
                    @elseif (Auth::check() && Auth::user()->role->slug == 'admin-sdm')
                        <form action="{{ route('admin_sdm.laporan_kinerja.detail', [
                            'id' => $getDataUser->id,
                            'month' => $selectedMonth,
                            'year' => $selectedYear
                        ]) }}" method="GET" class="d-flex gap-2">
                    @endif
                        <select name="month" class="form-select form-select-sm" style="width: 120px;">
                            @foreach($months as $key => $month)
                                <option value="{{ $key }}" {{ $selectedMonth == $key ? 'selected' : '' }}>
                                    {{ $month }}
                                </option>
                            @endforeach
                        </select>
                        <select name="year" class="form-select form-select-sm" style="width: 100px;">
                            @foreach(range(date('Y') - 2, date('Y') + 1) as $year)
                                <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>{{ $year }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div class="mb-4 d-flex justify-content-between align-items-center">
                    <span class="text-muted">Periode: {{ $startDate->translatedFormat('d F Y') }} s/d {{ $endDate->translatedFormat('d F Y') }}</span>
                    @if (Auth::check() && Auth::user()->role->slug == 'karyawan')
                        <a href="{{ route('karyawan.laporan_kinerja', [
                            'month' => $selectedMonth,
                            'year' => $selectedYear
                        ]) }}" class="btn btn-outline-secondary">
                            Kembali
                        </a>
                    @elseif (Auth::check() && Auth::user()->role->slug == 'admin-sdm')
                        <a href="{{ route('admin_sdm.laporan_kinerja', [
                            'id' => $getDataUser->id,
                            'month' => $selectedMonth,
                            'year' => $selectedYear
                        ]) }}" class="btn btn-outline-secondary">
                            Kembali
                        </a>
                    @endif
                </div>
                <div class="table-responsive">
                    <table id="datatable-basic" class="table table-bordered text-nowrap w-100">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Sub Task (Task - Tipe Task)</th>
                                <th>Project (Instansi)</th>
                                <th>Tanggal</th>
                                <th>Durasi</th>
                                <th>Keterangan</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($detailSubtasks as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        {{ $item->subtask?->nama_subtask ?? '' }}
                                        ({{ $item->subtask?->task?->nama_task . ' -' ?? '' }}
                                        {{ $item->subtask?->task?->tipe_task->nama_tipe ?? '' }})
                                    </td>
                                    <td>
                                        {{ $item->subtask?->task?->project_perusahaan?->nama_project ?? '' }}
                                        ({{ $item->subtask?->task?->project_perusahaan?->perusahaan?->nama_perusahaan ?? '-' }})
                                    </td>
                                    <td>{{ Carbon\Carbon::parse($item->tanggal)->translatedFormat('l, d F Y') }}</td>
                                    <td>{{ floor($item->durasi / 60) }} Jam, {{ $item->durasi % 60 }} Menit</td>
                                    <td class="text-wrap">{{ $item->keterangan ?? '-' }}</td>
                                    <td class="text-center">
                                        @if($item->status === 'revise')
                                            <span class="badge bg-warning"
                                                data-bs-toggle="tooltip" 
                                                data-bs-custom-class="tooltip-secondary"
                                                data-bs-placement="top" 
                                                title="Pesan Revisi: {{ $item->approval_notes ?? '-' }}">
                                                Revisi
                                                <i class="fas fa-info-circle ms-1"></i>
                                            </span>
                                        @elseif($item->status === 'approved')
                                            <span class="badge bg-success">Approve</span>
                                        @elseif($item->status === 'rejected')
                                            <span class="badge bg-danger"
                                                data-bs-toggle="tooltip"
                                                data-bs-custom-class="tooltip-secondary"
                                                data-bs-placement="top"
                                                title="Alasan Penolakan: {{ $item->approval_notes ?? '-' }}">
                                                Rejected
                                                <i class="fas fa-info-circle ms-1"></i>
                                            </span>
                                        @elseif($item->status === 'submitted')
                                            <span class="badge bg-primary">Submitted</span>
                                        @else
                                            <span class="badge bg-secondary">Draft</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($item->subtask->lampiran->count())
                                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#lampiranModal{{ $item->id }}">
                                                <i class="ti ti-file-search"
                                                data-bs-toggle="tooltip" 
                                                data-bs-custom-class="tooltip-primary"
                                                data-bs-placement="top" title="Lihat Lampiran!"></i>
                                            </button>
                                        @else
                                            <span class="text-muted">Tidak ada lampiran</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" style="font-weight: bold">Total Durasi</td>
                                <td colspan="3" >{{ $totalDurasiJam }} Jam, {{ $totalDurasiSisaMenit }} Menit</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            {{-- @if ($detailSubtasks->where('subtask.status', 'revise')->count() > 0)
                <div class="card custom-card mt-4">
                    <div class="card-header bg-warning text-white">
                        <h6 class="mb-0">Revisi Laporan</h6>
                    </div>
                    <div class="card-body">
                        @foreach ($detailSubtasks->where('subtask.status', 'revise') as $reviseItem)
                            @if ($reviseItem->subtask->revisi)
                                <div class="mb-3 p-3 border rounded">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <strong>Tanggal Revisi:</strong>
                                        <span class="text-muted">
                                            {{ $reviseItem->subtask->revisi->created_at->translatedFormat('d F Y H:i') }}
                                        </span>
                                    </div>
                                    <div class="mb-2">
                                        <strong>Pesan Revisi:</strong>
                                        <p class="mb-0">{{ $reviseItem->subtask->revisi->pesan }}</p>
                                    </div>
                                    <div>
                                        <strong>Periode Laporan Direvisi:</strong>
                                        <span class="text-muted">
                                            {{ $reviseItem->subtask->revisi->start_date->translatedFormat('d F Y H:i') }} - 
                                            {{ $reviseItem->subtask->revisi->end_date->translatedFormat('d F Y H:i') }}
                                        </span>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif --}}
        </div>
    </div>
@endsection
@section('script')
    <style>
        .badge { cursor: pointer; }
        .fa-info-circle { font-size: 0.8em; }
    </style>
    <script>
        $(document).ready(function(){
            $('[data-bs-toggle="tooltip"]').tooltip();
        });
    </script>
    <script>
        $("#upload").change(function () {
            const files = this.files;
            const previewArea = $("#preview-area");
            const detailUpload = $("#detail_upload");
    
            previewArea.html('');
            detailUpload.html('');
    
            if (files.length > 0) {
                detailUpload.html(`<strong>${files.length} file dipilih:</strong><br>`);
    
                Array.from(files).forEach(file => {
                    let fileUrl = URL.createObjectURL(file);
                    let fileName = file.name;
                    let ext = fileName.split('.').pop().toLowerCase();
    
                    detailUpload.append(`${fileName}<br>`);
    
                    let previewItem = '';
    
                    if (['jpg', 'jpeg', 'png', 'gif'].includes(ext)) {
                        previewItem = `
                            <div class="col-md-4 mb-3 text-center">
                                <img src="${fileUrl}" class="img-fluid rounded border shadow-sm" style="max-height: 150px;">
                                <p class="small mt-2">${fileName}</p>
                            </div>
                        `;
                    } else if (ext === 'pdf') {
                        previewItem = `
                            <div class="col-md-6 mb-3 text-center">
                                <iframe src="${fileUrl}" class="rounded border" width="100%" height="150px"></iframe>
                                <p class="small mt-2">${fileName}</p>
                            </div>
                        `;
                    } else {
                        previewItem = `
                            <div class="col-md-4 mb-3 text-center">
                                <div class="alert alert-secondary p-2 mb-1" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    <i class="fa fa-file me-2"></i>${fileName}
                                </div>
                            </div>
                        `;
                    }
                    previewArea.append(previewItem);
                });
            }
        });
    </script>  
    <script>
        document.addEventListener('DOMContentLoaded', function(){
            document.getElementById('upload').addEventListener('change', function (e) {
                const file = e.target.files[0];
                const imagePreview = document.getElementById('previewImage');
                const pdfPreview = document.getElementById('previewPDF');
                const detailUpload = document.getElementById('detail_upload');

                if (file) {
                    const fileURL = URL.createObjectURL(file);
                    const fileName = file.name;
                    const fileExt = fileName.split('.').pop().toLowerCase();

                    imagePreview.style.display = 'none';
                    pdfPreview.style.display = 'none';

                    if (['jpg', 'jpeg', 'png'].includes(fileExt)) {
                        imagePreview.src = fileURL;
                        imagePreview.style.display = 'block';
                    } else if (fileExt === 'pdf') {
                        pdfPreview.src = fileURL;
                        pdfPreview.style.display = 'block';
                    }

                    detailUpload.textContent = fileName;
                }
            });
        });  
    </script>
@endsection