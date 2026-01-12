@extends('layouts.main')

@section('content')
    <!-- Modal untuk Bulk Actions -->
    <div class="modal fade" id="bulkApproveModal" tabindex="-1" aria-labelledby="bulkApproveModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('manajer.bulk.approve.detail') }}" method="POST" id="bulkApproveForm">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Approve Bulk Laporan Kinerja</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-success">
                            <strong>Item yang akan di-approve:</strong> <span id="bulkApproveCount">0</span> laporan
                        </div>
                        <div class="mb-3">
                            <label>Catatan Approval (Opsional)</label>
                            <textarea name="approval_notes" class="form-control" rows="3" placeholder="Catatan approval..."></textarea>
                        </div>
                        <input type="hidden" name="detail_ids" id="bulkApproveIds">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Approve Semua</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('manajer.bulk.approve.detail') }}" method="POST" id="approveForm">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Approve Bulk Laporan Kinerja</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-success">
                            <strong>Item yang akan di-approve:</strong> <span id="approve-id">0</span>
                        </div>
                        <div class="mb-3">
                            <label>Catatan Approval (Opsional)</label>
                            <textarea name="approval_notes" class="form-control" rows="3" placeholder="Catatan approval..."></textarea>
                        </div>
                        <input type="hidden" name="detail_ids" id="bulkApproveIds">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Approve</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="bulkRejectModal" tabindex="-1" aria-labelledby="bulkRejectModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('manajer.bulk.reject.detail') }}" method="POST" id="bulkRejectForm">
                    @csrf
                    @method('PUT')
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title">Reject Bulk Laporan Kinerja</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-danger">
                            <strong>Item yang akan di-reject:</strong> <span id="bulkRejectCount">0</span> laporan
                        </div>
                        <div class="mb-3">
                            <label for="bulkRejectNotes" class="form-label">Alasan Penolakan <span
                                    class="text-danger">*</span></label>
                            <textarea name="approval_notes" id="bulkRejectNotes" rows="3" class="form-control"
                                placeholder="Jelaskan alasan penolakan..." required></textarea>
                        </div>
                        <input type="hidden" name="detail_ids" id="bulkRejectIds">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Reject Semua</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="bulkReviseModal" tabindex="-1" aria-labelledby="bulkReviseModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('manajer.bulk.revise.detail') }}" method="POST" id="bulkReviseForm">
                    @csrf
                    @method('PUT')
                    <div class="modal-header bg-warning text-white">
                        <h5 class="modal-title">Revise Bulk Laporan Kinerja</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <strong>Item yang akan di-revise:</strong> <span id="bulkReviseCount">0</span> laporan
                        </div>
                        <div class="mb-3">
                            <label for="bulkReviseNotes" class="form-label">Catatan Revisi <span
                                    class="text-danger">*</span></label>
                            <textarea name="approval_notes" id="bulkReviseNotes" rows="3" class="form-control"
                                placeholder="Instruksi revisi untuk karyawan..." required></textarea>
                        </div>
                        <input type="hidden" name="detail_ids" id="bulkReviseIds">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning text-white">Kirim Revisi Semua</button>
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
                    <span
                        class="text-muted">{{ $getDataUser->dataDiri->kepegawaian->subJabatan->nama_sub_jabatan ?? '-' }}</span>
                </div>
                <div class="d-flex gap-2">
                    <!-- Bulk Action Buttons -->
                    <button type="button" class="btn btn-success btn-sm" id="bulkApproveBtn" disabled>
                        <i class="fas fa-check"></i> Approve Selected
                    </button>
                    <button type="button" class="btn btn-danger btn-sm" id="bulkRejectBtn" disabled>
                        <i class="fas fa-times"></i> Reject Selected
                    </button>
                    <button type="button" class="btn btn-warning btn-sm text-white" id="bulkReviseBtn" disabled>
                        <i class="fas fa-edit"></i> Revise Selected
                    </button>
                </div>
            </div>
            <div class="card-body">
                @if ($periode && $periode !== 'invalid_date')
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
                                <th class="text-center">
                                    <input type="checkbox" id="selectAll" class="form-check-input">
                                </th>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Sub Task (Task - Tipe Task)</th>
                                <th>Project (Instansi)</th>
                                <th>Durasi</th>
                                <th>Keterangan</th>
                                <th>Status</th>
                                <th>Lampiran</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no = 1;
                            @endphp
                            @foreach ($getDataLaporan as $item)
                                <tr>
                                    <td class="text-center">
                                        @if ($item->status === 'submitted')
                                            <input type="checkbox" class="form-check-input row-checkbox"
                                                value="{{ $item->id }}">
                                        @endif
                                    </td>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('l, d F Y') }}</td>
                                    <td>
                                        {{ $item->subtask->nama_subtask ?? '' }}
                                        ({{ $item->subtask->task->nama_task ?? '' }} -
                                        {{ $item->subtask->task->tipe_task->nama_tipe ?? '' }})
                                    </td>
                                    <td>
                                        {{ $item->subtask->task->project_perusahaan?->nama_project ?? '-' }}
                                        <br>
                                        <small class="text-muted">
                                            ({{ $item->subtask->task->project_perusahaan?->perusahaan?->nama_perusahaan ?? '-' }})
                                        </small>
                                    </td>
                                    <td>
                                        {{ floor($item->durasi / 60) }} Jam, {{ $item->durasi % 60 }} Menit
                                    </td>
                                    <td>{{ $item->keterangan ?? '-' }}</td>
                                    <td class="text-center">
                                        @if ($item->status === 'approved')
                                            <span class="badge bg-success">Approved</span>
                                        @elseif($item->status === 'rejected')
                                            <span class="badge bg-danger">Rejected</span>
                                        @elseif($item->status === 'revise')
                                            <span class="badge bg-warning">Revise</span>
                                        @elseif($item->status === 'submitted')
                                            <span class="badge bg-primary">Submitted</span>
                                        @else
                                            <span class="badge bg-secondary">Draft</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($item->subtask->lampiran && $item->subtask->lampiran->count() > 0)
                                            <button type="button" class="btn btn-primary btn-sm"
                                                data-bs-toggle="tooltip" data-bs-custom-class="tooltip-primary"
                                                data-bs-placement="top" title="Lihat Lampiran!"
                                                onclick="previewLampiran({{ $item->subtask->id }}, {{ $item->subtask->lampiran }})">
                                                <i class="ti ti-file-search"></i>
                                            </button>
                                        @else
                                            <span class="text-muted">Tidak Ada Lampiran</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($item->status === 'submitted')
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-success btn-sm btn-approve"
                                                    data-bs-toggle="tooltip" title="Approve"
                                                    data-id="{{ $item->id }}"
                                                    data-detail="{{ $item->subtask->nama_subtask }} - {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <button type="button" class="btn btn-danger btn-sm"
                                                    data-bs-toggle="modal" data-bs-target="#rejectModal"
                                                    data-id="{{ $item->id }}"
                                                    data-detail="{{ $item->subtask->nama_subtask }} - {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}"
                                                    title="Reject">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                                <button type="button" class="btn btn-warning btn-sm text-white"
                                                    data-bs-toggle="modal" data-bs-target="#reviseModal"
                                                    data-id="{{ $item->id }}"
                                                    data-detail="{{ $item->subtask->nama_subtask }} - {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}"
                                                    title="Request Revision">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="5" class="text-end"></th>
                                <th colspan="1">Total Durasi:</th>
                                <th colspan="1" class="text-center">
                                    {{ $durasiJam }} Jam, {{ $durasiMenit }} Menit
                                </th>
                                <th colspan="4"></th>
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
@section('script')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Define all variables inside DOMContentLoaded
            const selectAllCheckbox = document.getElementById('selectAll');
            const rowCheckboxes = document.querySelectorAll('.row-checkbox');
            const bulkApproveBtn = document.getElementById('bulkApproveBtn');
            const bulkRejectBtn = document.getElementById('bulkRejectBtn');
            const bulkReviseBtn = document.getElementById('bulkReviseBtn');

            // Function to update bulk buttons
            function updateBulkButtons() {
                const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
                const hasSelection = checkedBoxes.length > 0;

                if (bulkApproveBtn) bulkApproveBtn.disabled = !hasSelection;
                if (bulkRejectBtn) bulkRejectBtn.disabled = !hasSelection;
                if (bulkReviseBtn) bulkReviseBtn.disabled = !hasSelection;
            }

            // Select All functionality
            if (selectAllCheckbox) {
                selectAllCheckbox.addEventListener('change', function() {
                    const isChecked = this.checked;
                    rowCheckboxes.forEach(checkbox => {
                        checkbox.checked = isChecked;
                    });
                    updateBulkButtons();
                });
            }

            // Individual checkbox change
            rowCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
                    if (selectAllCheckbox) {
                        selectAllCheckbox.checked = checkedBoxes.length === rowCheckboxes.length;
                        selectAllCheckbox.indeterminate = checkedBoxes.length > 0 && checkedBoxes
                            .length < rowCheckboxes.length;
                    }
                    updateBulkButtons();
                });
            });

            // Bulk Approve
            if (bulkApproveBtn) {
                bulkApproveBtn.addEventListener('click', function() {
                    const selectedIds = Array.from(document.querySelectorAll('.row-checkbox:checked')).map(
                        cb => cb.value);
                    if (selectedIds.length === 0) return;

                    const bulkApproveIds = document.getElementById('bulkApproveIds');
                    const bulkApproveCount = document.getElementById('bulkApproveCount');

                    if (bulkApproveIds) bulkApproveIds.value = selectedIds.join(',');
                    if (bulkApproveCount) bulkApproveCount.textContent = selectedIds.length;

                    const modal = new bootstrap.Modal(document.getElementById('bulkApproveModal'));
                    modal.show();
                });
            }

            // Bulk Reject
            if (bulkRejectBtn) {
                bulkRejectBtn.addEventListener('click', function() {
                    const selectedIds = Array.from(document.querySelectorAll('.row-checkbox:checked')).map(
                        cb => cb.value);
                    if (selectedIds.length === 0) return;

                    const bulkRejectIds = document.getElementById('bulkRejectIds');
                    const bulkRejectCount = document.getElementById('bulkRejectCount');

                    if (bulkRejectIds) bulkRejectIds.value = selectedIds.join(',');
                    if (bulkRejectCount) bulkRejectCount.textContent = selectedIds.length;

                    const modal = new bootstrap.Modal(document.getElementById('bulkRejectModal'));
                    modal.show();
                });
            }

            // Bulk Revise
            if (bulkReviseBtn) {
                bulkReviseBtn.addEventListener('click', function() {
                    const selectedIds = Array.from(document.querySelectorAll('.row-checkbox:checked')).map(
                        cb => cb.value);
                    if (selectedIds.length === 0) return;

                    const bulkReviseIds = document.getElementById('bulkReviseIds');
                    const bulkReviseCount = document.getElementById('bulkReviseCount');

                    if (bulkReviseIds) bulkReviseIds.value = selectedIds.join(',');
                    if (bulkReviseCount) bulkReviseCount.textContent = selectedIds.length;

                    const modal = new bootstrap.Modal(document.getElementById('bulkReviseModal'));
                    modal.show();
                });
            }

            // Individual approve buttons
            $('.btn-approve').on('click', function(e) {
                const id = this.getAttribute('data-id');
                const detail = this.getAttribute('data-detail');
                const form = document.getElementById('approveForm');
                const approveId = document.getElementById('approve-id');

                if (form) {
                    form.action = '/manajer/laporan_kinerja/approve/detail/' + id;
                    approveId.textContent = detail;

                    const modal = new bootstrap.Modal(document.getElementById('approveModal'));
                    modal.show();
                }
            })
            // const approveButtons = document.querySelectorAll('.btn-approve');
            // approveButtons.forEach(button => {
            //     button.addEventListener('click', function(e) {
            //         // e.preventDefault();

            //         const id = this.getAttribute('data-id');
            //         const detail = this.getAttribute('data-detail');
            //         const form = document.getElementById('approveForm');
            //         const detailSpan = document.getElementById('approveDetail');

            //         if (form && detailSpan) {
            //             form.action = '/manajer/laporan_kinerja/approve/detail/' + id;
            //             detailSpan.textContent = detail;

            //             const modal = new bootstrap.Modal(document.getElementById('approveModal'));
            //             modal.show();
            //         }
            //     });
            // });

            // Reject modal
            const rejectModal = document.getElementById('rejectModal');
            if (rejectModal) {
                rejectModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const id = button.getAttribute('data-id');
                    const detail = button.getAttribute('data-detail');
                    const modalDetail = rejectModal.querySelector('#rejectDetail');
                    const modalForm = rejectModal.querySelector('#rejectForm');

                    if (modalDetail && modalForm) {
                        modalDetail.textContent = detail;
                        modalForm.action = '/manajer/laporan_kinerja/reject/detail/' + id;
                    }
                });
            }

            // Revise modal
            const reviseModal = document.getElementById('reviseModal');
            if (reviseModal) {
                reviseModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const id = button.getAttribute('data-id');
                    const detail = button.getAttribute('data-detail');
                    const modalDetail = reviseModal.querySelector('#reviseDetail');
                    const modalForm = reviseModal.querySelector('#reviseForm');

                    if (modalDetail && modalForm) {
                        modalDetail.textContent = detail;
                        modalForm.action = '/manajer/laporan_kinerja/revise/detail/' + id;
                    }
                });
            }
        });
    </script>
@endsection
