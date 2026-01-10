@extends('layouts.main')

@section('content')
    <div class="container-fluid">
        <div class="card custom-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="card-title mb-0">
                    {{ $title }}
                </h6>
                <a href="{{ route('manajer.laporan_kinerja') }}" class="btn btn-light btn-sm shadow-sm">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover text-nowrap w-100 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width: 5%">No.</th>
                                <th>Karyawan</th>
                                <th>Project</th>
                                <th>Sub Task</th>
                                <th>Tanggal & Durasi</th>
                                <th>Keterangan</th>
                                <th class="text-center">Status</th>
                                <th>Dikirim Pada</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($getDataLaporan as $index => $report)
                                <tr>
                                    <td class="text-center">
                                        {{ $getDataLaporan->firstItem() + $index }}
                                    </td>
                                    <td class="fw-bold">{{ $report->user->name ?? '-' }}</td>
                                    <td>
                                        {{ $report->subtask->task->project_perusahaan->nama_project ?? '-' }}
                                    </td>
                                    <td>
                                        {{ $report->subtask->nama_subtask ?? '-' }}
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-semibold">
                                                {{ \Carbon\Carbon::parse($report->tanggal)->isoFormat('dddd, D MMMM Y') }}
                                            </span>
                                            <small class="text-muted">
                                                <i class="fas fa-clock me-1"></i>
                                                {{ floor($report->durasi / 60) }} jam {{ $report->durasi % 60 }} menit
                                            </small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="d-inline-block text-truncate" style="max-width: 200px;" title="{{ $report->keterangan }}">
                                            {{ $report->keterangan }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-warning text-dark">
                                            <i class="fas fa-hourglass-half me-1"></i> Pending
                                        </span>
                                    </td>
                                    <td>
                                        {{ $report->created_at ? $report->created_at->format('d M Y H:i') : '-' }}
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <form action="{{ route('manajer.approve.detail_subtask', $report->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="approval_notes" value="Approved">
                                                <button type="button" class="btn btn-success btn-sm btn-approve"
                                                    data-bs-toggle="tooltip" title="Approve">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                            
                                            <button type="button" class="btn btn-danger btn-sm" 
                                                data-bs-toggle="modal"
                                                data-bs-target="#rejectModal" 
                                                data-id="{{ $report->id }}"
                                                data-karyawan="{{ $report->user->name ?? 'Karyawan' }}"
                                                title="Tolak">
                                                <i class="fas fa-times"></i>
                                            </button>
                                            
                                            <button type="button" class="btn btn-warning btn-sm text-white" 
                                                data-bs-toggle="modal"
                                                data-bs-target="#reviseModal" 
                                                data-id="{{ $report->id }}"
                                                data-karyawan="{{ $report->user->name ?? 'Karyawan' }}"
                                                title="Minta Revisi">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5">
                                        <div class="d-flex flex-column align-items-center justify-content-center">
                                            <i class="fas fa-check-circle text-success fa-3x mb-3"></i>
                                            <h5 class="text-muted">Tidak ada laporan pending</h5>
                                            <p class="text-muted small">Semua laporan kinerja telah ditinjau.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap">
                    <div class="text-muted small mb-2 mb-md-0">
                        Menampilkan 
                        <span class="fw-bold">{{ $getDataLaporan->firstItem() ?? 0 }}</span> 
                        sampai 
                        <span class="fw-bold">{{ $getDataLaporan->lastItem() ?? 0 }}</span> 
                        dari total 
                        <span class="fw-bold">{{ $getDataLaporan->total() ?? 0 }}</span> data
                    </div>
                    <div>
                        {{ $getDataLaporan->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="rejectModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Tolak Laporan Kinerja</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="" id="rejectForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="alert alert-light border">
                            <strong>Karyawan:</strong> <span id="rejectKaryawan"></span>
                        </div>
                        <div class="mb-3">
                            <label for="rejectNotes" class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                            <textarea name="approval_notes" id="rejectNotes" rows="3" class="form-control" placeholder="Jelaskan alasan penolakan..." required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Tolak Laporan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="reviseModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title">Minta Revisi Laporan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="" id="reviseForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="alert alert-light border">
                            <strong>Karyawan:</strong> <span id="reviseKaryawan"></span>
                        </div>
                        <div class="mb-3">
                            <label for="reviseNotes" class="form-label">Catatan Revisi <span class="text-danger">*</span></label>
                            <textarea name="approval_notes" id="revisiNotes" rows="3" class="form-control" placeholder="Instruksi revisi untuk karyawan..." required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning text-white">Kirim Permintaan Revisi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        
        const approveButtons = document.querySelectorAll('.btn-approve');
        
        approveButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                
                const form = this.closest('form');
                
                Swal.fire({
                    title: 'Setujui Laporan?',
                    text: "Laporan kinerja ini akan disetujui.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#198754',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Setujui!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        
                        Swal.fire({
                            title: 'Memproses...',
                            text: 'Mohon tunggu sebentar',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        
                        form.submit();
                    }
                });
            });
        });
        
        var rejectModal = document.getElementById('rejectModal');
        rejectModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var id = button.getAttribute('data-id');
            var karyawan = button.getAttribute('data-karyawan');
            var modalKaryawan = rejectModal.querySelector('#rejectKaryawan');
            var modalForm = rejectModal.querySelector('#rejectForm');
            modalKaryawan.textContent = karyawan;
            modalForm.action = '/manajer/laporan_kinerja/reject/detail/' + id;
        });

        var reviseModal = document.getElementById('reviseModal');
        reviseModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var id = button.getAttribute('data-id');
            var karyawan = button.getAttribute('data-karyawan');
            var modalKaryawan = reviseModal.querySelector('#reviseKaryawan');
            var modalForm = reviseModal.querySelector('#reviseForm');
            modalKaryawan.textContent = karyawan;
            modalForm.action = '/manajer/laporan_kinerja/revise/detail/' + id;
        });
    });
</script>
@endsection