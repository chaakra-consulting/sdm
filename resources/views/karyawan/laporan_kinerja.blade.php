@extends('layouts.main')

@section('content')
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="staticBackdropLabel"></h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="POST" id="formLaporanKinerja" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="sub_task_id">Sub Task</label>
                            <select name="sub_task_id" id="sub_task_id" class="form-control" required>
                                <option value="" selected disabled>Pilih Sub Task</option>
                                @foreach ($subtasks as $item)
                                    <option value="{{ $item->id }}" {{ old('id') == $item->id ? 'selected' : '' }}>
                                        {{ $item->nama_subtask ?? '' }}
                                        ({{ $item->task->nama_task . ' -' ?? '' }}
                                        {{ $item->task->tipe_task->nama_tipe ?? '' }})
                                        ({{ $item->task?->project_perusahaan?->nama_project . ' -' ?? '' }}
                                        {{ $item->task?->project_perusahaan?->perusahaan?->nama_perusahaan ?? '' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-6">
                                    <div class="input-group">
                                        <input type="number" min="0" name="durasi_jam" class="form-control"
                                            placeholder="Jam" value="" required>
                                        <span class="input-group-text">Jam</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="input-group">
                                        <input type="number" min="0" max="59" name="durasi_menit"
                                            class="form-control" placeholder="Menit" value="" required>
                                        <span class="input-group-text">Menit</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <textarea class="form-control" name="keterangan" id="keterangan" cols="10" rows="5"></textarea>
                        </div>
                        <input type="hidden" name="user_id" id="user_id" value="{{ auth()->user()->id }}">
                        <input type="hidden" name="tanggal" id="tanggal_terpilih">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kembali</button>
                        <button type="submit" class="btn btn-primary" id="btnSimpanModal">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="card custom-card">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="card-title mb-0">
                    <h6 class="mb-0">{{ $getDataUser->name }}</h6>
                    <span
                        class="text-muted fs-12">{{ $getDataUser->dataDiri->kepegawaian->subJabatan->nama_sub_jabatan ?? '-' }}</span>
                </div>
                <div class="d-flex justify-content-end align-items-center">
                    @php
                        $route =
                            Auth::user()->role->slug == 'karyawan'
                                ? 'karyawan.laporan_kinerja'
                                : 'admin_sdm.laporan_kinerja';
                    @endphp
                    <form action="{{ route($route) }}" method="GET" class="d-flex gap-2">
                        <select name="month" class="form-select form-select-sm" style="min-width: 120px;">
                            @foreach ($months as $key => $month)
                                <option value="{{ $key }}" {{ $selectedMonth == $key ? 'selected' : '' }}>
                                    {{ $month }}
                                </option>
                            @endforeach
                        </select>
                        <select name="year" class="form-select form-select-sm" style="min-width: 100px;">
                            @foreach (range(date('Y') - 2, date('Y') + 1) as $year)
                                <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-filter"></i> Filter</button>
                    </form>
                </div>
            </div>

            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start flex-wrap mb-3">
                    <span class="text-muted fw-semibold">Periode: {{ $startDate->translatedFormat('d F Y') }} s/d
                        {{ $endDate->translatedFormat('d F Y') }}</span>
                    <div id="selected-date" class="text-primary fw-bold"></div>
                </div>
                <div style="overflow-x: auto;">
                    <div class="swiper-container mt-3 px-2">
                        <div class="swiper-wrapper">
                            <div class="d-flex overflow-auto gap-2 px-2" style="min-width: 100px">
                                @foreach ($dates as $item)
                                    @php
                                        $date = $item['date'];
                                        $isDisabled = !$item['is_working_day'];
                                        $isActive = $item['is_active'];
                                        $totalDurasi = $item['total_durasi'];
                                        $jumlahTask = $item['jumlah_task'];
                                        $jam = floor($totalDurasi / 60);
                                        $menit = $totalDurasi % 60;
                                    @endphp
                                    <div class="card slide-item {{ $isDisabled ? 'bg-light text-muted' : ($isActive ? 'active-slide bg-primary text-white' : 'bg-white') }}"
                                        style="min-width: 120px; min-height: 120px"
                                        @if ($isDisabled) title="Hari Libur" @endif
                                        data-date="{{ $date->format('Y-m-d') }}">
                                        @if ($isDisabled)
                                            <div class="ribbon ribbon-top bg-danger">Libur</div>
                                        @endif
                                        <div class="card-body text-center p-2 d-flex flex-column justify-content-center">
                                            <div class="fw-bold fs-4">{{ $date->format('d') }}</div>
                                            <div class="fs-11 text-uppercase">{{ $date->translatedFormat('M Y') }}</div>
                                            <hr class="my-1 opacity-25">
                                            <div class="fs-11">
                                                {{ $jam ? $jam . ' Jam' : '' }} {{ $menit ? $menit . ' Menit' : '' }}
                                                @if (!$jam && !$menit)
                                                    -
                                                @endif
                                            </div>
                                            <div class="fs-10 opacity-75">Task: {{ $jumlahTask ?: '0' }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <div class="row g-3 mb-4 align-items-center">
                    <div class="col-12 col-xl-6">
                        <div class="d-flex gap-2 flex-wrap">
                            <button type="button" class="btn btn-primary btn-sm tambahLaporanKinerja" data-bs-toggle="modal"
                                data-bs-target="#staticBackdrop">
                                <i class="bi bi-plus-lg me-1"></i> Update Pekerjaan
                            </button>

                            @php
                                $roleSlug = Auth::user()->role->slug;
                                $prefix = $roleSlug == 'karyawan' ? 'karyawan' : 'admin_sdm';
                            @endphp
                            
                            <form action="{{ route($prefix . '.laporan_kinerja.kirim', ['id' => auth()->user()->id]) }}"
                                method="POST" id="formKirim">
                                @csrf
                                <input type="hidden" name="tanggal" id="tanggal_terpilih_kirim">
                                <button type="button" class="btn btn-outline-success btn-sm btn-confirm-kirim"
                                    title="Kirim Laporan Tanggal Ini">
                                    <i class="bi bi-send"></i> Kirim
                                </button>
                            </form>
                            <form action="{{ route($prefix . '.laporan_kinerja.batal', ['id' => auth()->user()->id]) }}"
                                method="POST" id="formBatal">
                                @csrf
                                <input type="hidden" name="tanggal" id="tanggal_terpilih_batal">
                                <button type="button" class="btn btn-outline-danger btn-sm btn-confirm-batal"
                                    title="Batal Kirim Tanggal Ini">
                                    <i class="bi bi-x-circle"></i> Batal
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="col-12 col-xl-6">
                        <div class="d-flex gap-2 flex-wrap justify-content-xl-end">
                            <form
                                action="{{ route($prefix . '.laporan_kinerja.bulk_kirim', ['id' => auth()->user()->id]) }}"
                                method="POST" id="formBulkKirim">
                                @csrf
                                <input type="hidden" name="month" value="{{ $selectedMonth }}">
                                <input type="hidden" name="year" value="{{ $selectedYear }}">
                                <button type="button" class="btn btn-success btn-sm btn-confirm-bulk-kirim">
                                    <i class="bi bi-send-check-fill me-1"></i> Kirim Semua
                                </button>
                            </form>
                            <form
                                action="{{ route($prefix . '.laporan_kinerja.bulk_batal', ['id' => auth()->user()->id]) }}"
                                method="POST" id="formBulkBatal">
                                @csrf
                                <input type="hidden" name="month" value="{{ $selectedMonth }}">
                                <input type="hidden" name="year" value="{{ $selectedYear }}">
                                <button type="button" class="btn btn-danger btn-sm btn-confirm-bulk-batal">
                                    <i class="bi bi-x-octagon-fill me-1"></i> Batal Semua
                                </button>
                            </form>
                            <a href="{{ route($prefix . '.laporan_kinerja.detail', [
                                'id' => auth()->user()->id,
                                'month' => $selectedMonth,
                                'year' => $selectedYear,
                            ]) }}"
                                class="btn btn-info btn-sm text-white">
                                <i class="bi bi-info-circle me-1"></i> Detail
                            </a>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="datatable-basic" class="table table-bordered text-nowrap w-100 table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">No</th>
                                <th>Sub Task</th>
                                <th>Task (Tipe Task)</th>
                                <th>Tanggal</th>
                                <th>Durasi</th>
                                <th width="30%">Keterangan</th>
                                <th width="10%" class="text-center">Aksi</th>
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

@section('script')
    <style>
        .swiper-container { width: 100%; padding: 10px 0; }
        .swiper-slide { width: auto !important; }
        .swiper-wrapper { scroll-behavior: smooth; }
        .slide-item {
            cursor: pointer;
            border: 1px solid #e9edf4;
            transition: all 0.3s ease;
            box-shadow: 0 .125rem .25rem rgba(0, 0, 0, .075);
        }
        .slide-item:hover { transform: translateY(-2px); border-color: #0d6efd; }
        .slide-item.active-slide {
            background-color: #0d6efd !important;
            color: white;
            border-color: #0d6efd;
            box-shadow: 0 .5rem 1rem rgba(13, 110, 253, .15);
        }
        .slide-item.active-slide, .slide-item.active-slide * { color: white !important; }
        
        .swal2-container { z-index: 2000 !important; }
    </style>

    <script>
        const userRole = "{{ Auth::user()->role->slug }}";
        const routePrefix = userRole === 'karyawan' ? '/karyawan' : '/admin_sdm';

        document.querySelectorAll('.slide-item').forEach(slide => {
            slide.addEventListener('click', function() {
                document.querySelectorAll('.slide-item').forEach(s => {
                    s.classList.remove('active-slide', 'bg-primary', 'text-white');
                    s.classList.add('bg-white');
                });
                this.classList.remove('bg-white');
                this.classList.add('active-slide', 'bg-primary', 'text-white');

                const selectedDate = this.dataset.date;
                document.getElementById('selected-date').innerText = `Tanggal dipilih: ${new Date(selectedDate).toLocaleDateString('id-ID', {
                    weekday: 'long', day: 'numeric', month: 'long', year: 'numeric'
                })}`;

                const actionUrl = `${routePrefix}/laporan_kinerja/getDataByDate`;

                document.getElementById('tanggal_terpilih').value = selectedDate;
                document.getElementById('tanggal_terpilih_kirim').value = selectedDate;
                document.getElementById('tanggal_terpilih_batal').value = selectedDate;

                $.ajax({
                    url: actionUrl,
                    method: 'GET',
                    data: { tanggal: selectedDate },
                    beforeSend: function() {
                        $('#datatable-basic tbody').html(
                            `<tr><td colspan="7" class="text-center py-4"><div class="spinner-border text-primary" role="status"></div></td></tr>`
                        );
                    },
                    success: function(response) {
                        const tableBody = $('#datatable-basic tbody');
                        tableBody.empty();

                        if (response.data.length > 0) {
                            response.data.forEach((detail, index) => {
                                const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                                const deleteUrl = `${routePrefix}/laporan_kinerja/delete/${detail.id}`;

                                const row = `
                                <tr>
                                    <td>${index + 1}</td>
                                    <td class="fw-semibold">${detail.nama_subtask ?? '-'}</td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span>${detail.nama_task ?? '-'}</span>
                                            <span class="text-muted fs-11">(${detail.nama_tipe ?? '-'})</span>
                                        </div>
                                    </td>
                                    <td>${new Date(detail.tanggal).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' })}</td>
                                    <td>${Math.floor(detail.durasi / 60)} Jam ${detail.durasi % 60} Menit</td>
                                    <td class="text-wrap" style="max-width: 300px;">${detail.keterangan ?? '-'}</td>
                                    <td class="text-center">
                                        ${detail.is_active == 0 ? `
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-warning btn-sm updateSubTask" 
                                                data-id="${detail.id}" 
                                                data-subtask_id="${detail.sub_task_id}"
                                                data-durasi="${detail.durasi}"
                                                data-keterangan="${detail.keterangan ? detail.keterangan.replace(/"/g, '&quot;') : ''}">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                            <form action="${deleteUrl}" method="POST" class="d-inline form-delete-item">
                                                <input type="hidden" name="_token" value="${csrf}">
                                                <input type="hidden" name="_method" value="DELETE">
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                        ` : '<span class="badge bg-success-transparent"><i class="bi bi-check-circle me-1"></i>Terkirim</span>'}
                                    </td>
                                </tr>`;
                                tableBody.append(row);
                            });
                        } else {
                            tableBody.append('<tr><td colspan="7" class="text-center text-muted py-3">Tidak ada data pekerjaan pada tanggal ini</td></tr>');
                        }
                    },
                    error: function(xhr) {
                        $('#datatable-basic tbody').html(`<tr><td colspan="7" class="text-center text-danger">Gagal memuat data.</td></tr>`);
                    }
                });
            });
        });
    </script>
    <script>
        window.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const dateFromUrl = urlParams.get('tanggal');

            const todayDate = new Date().toISOString().slice(0, 10);

            let targetSlide = null;
            if (dateFromUrl) {
                targetSlide = document.querySelector(`.slide-item[data-date="${dateFromUrl}"]`);
            }

            if (!targetSlide) {
                targetSlide = document.querySelector('.slide-item.active-slide');
            }

            if (!targetSlide) {
                targetSlide = document.querySelector(`.slide-item[data-date="${todayDate}"]`);
            }

            if (!targetSlide) {
                targetSlide = document.querySelector('.slide-item');
            }

            if (targetSlide) {
                document.querySelectorAll('.slide-item').forEach(s => {
                    s.classList.remove('active-slide', 'bg-primary', 'text-white');
                    s.classList.add('bg-white');
                });

                targetSlide.classList.remove('bg-white');
                targetSlide.classList.add('active-slide', 'bg-primary', 'text-white');

                targetSlide.scrollIntoView({ behavior: 'smooth', block: 'center', inline: 'center' });

                setTimeout(() => {
                    targetSlide.click();
                }, 300);

                if (dateFromUrl) {
                    const newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
                    window.history.pushState({path: newUrl}, '', newUrl);
                }
            }

            new Swiper('.swiper-container', { slidesPerView: 'auto', spaceBetween: 10, grabCursor: true, freeMode: true });
        });
    </script>
    <script>
        $(document).ready(function() {
            const subTaskSelect = new Choices('#sub_task_id', {
                searchEnabled: true, removeItemButton: true, shouldSort: false,
                placeholder: true, placeholderValue: 'Pilih Sub Task', duplicateItemsAllowed: false,
                allowHTML: true, resetScrollPosition: false
            });
            
            $('#staticBackdrop').on('hidden.bs.modal', function() {
                $('#formLaporanKinerja')[0].reset();
                subTaskSelect.removeActiveItems();
                subTaskSelect.setChoiceByValue('');
                $('input[name="_method"]').remove();
                $('input[name="durasi_jam"], input[name="durasi_menit"]').val('');
                $('#keterangan').val('');
                $('#formLaporanKinerja').attr('action', `${routePrefix}/laporan_kinerja/store`);
            });

            $(document).on('click', '.tambahLaporanKinerja', function() {
                $(".modal-title").text('Update Pekerjaan Baru');
                $("#btnSimpanModal").text("Simpan");
                $('#formLaporanKinerja').attr('action', `${routePrefix}/laporan_kinerja/store`);
                $("#formLaporanKinerja input[name='_method']").remove();
                
                let activeSlide = document.querySelector('.slide-item.active-slide');
                if (activeSlide) {
                    $('#tanggal_terpilih').val(activeSlide.getAttribute('data-date'));
                } else {
                    $('#tanggal_terpilih').val(new Date().toISOString().slice(0, 10));
                }
            });

            $(document).on('click', '.updateSubTask', function() {
                const subtaskId = $(this).data('id');
                const subTaskOptionId = $(this).data('subtask_id').toString();
                const durasi = $(this).data('durasi');
                const keterangan = $(this).data('keterangan');
                const jam = Math.floor(durasi / 60);
                const menit = durasi % 60;

                const modal = new bootstrap.Modal(document.getElementById('staticBackdrop'));
                modal.show();
                
                $(".modal-title").text('Edit Pekerjaan');
                subTaskSelect.setChoiceByValue(subTaskOptionId);
                $("input[name='durasi_jam']").val(jam);
                $("input[name='durasi_menit']").val(menit);
                $("#keterangan").val(keterangan);
                $("#btnSimpanModal").text("Update");

                $("#formLaporanKinerja").attr("action", `${routePrefix}/laporan_kinerja/update/${subtaskId}`);
                if ($("#formLaporanKinerja input[name='_method']").length === 0) {
                    $("#formLaporanKinerja").append(`<input type="hidden" name="_method" value="PUT">`);
                } else {
                    $("#formLaporanKinerja input[name='_method']").val('PUT');
                }
            });
        })
    </script>
    <script>
        $(document).ready(function() {
            function confirmSweetAlert(title, text, icon, confirmText, confirmColor, formToSubmit) {
                Swal.fire({
                    title: title,
                    text: text,
                    icon: icon,
                    showCancelButton: true,
                    confirmButtonColor: confirmColor,
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: confirmText,
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        formToSubmit.submit();
                    }
                });
            }

            $('.btn-confirm-kirim').on('click', function(e) {
                e.preventDefault();
                confirmSweetAlert(
                    'Kirim Laporan?',
                    'Laporan kinerja untuk tanggal ini akan dikirim ke atasan.',
                    'question',
                    'Ya, Kirim!',
                    '#198754',
                    $('#formKirim')
                );
            });

            $('.btn-confirm-batal').on('click', function(e) {
                e.preventDefault();
                confirmSweetAlert(
                    'Batalkan Laporan?',
                    'Status laporan tanggal ini akan dikembalikan ke draft.',
                    'warning',
                    'Ya, Batalkan!',
                    '#dc3545',
                    $('#formBatal')
                );
            });

            $('.btn-confirm-bulk-kirim').on('click', function(e) {
                e.preventDefault();
                confirmSweetAlert(
                    'Kirim Semua Laporan?',
                    'Anda akan mengirim seluruh laporan di bulan ini sekaligus. Pastikan data sudah benar.',
                    'info',
                    'Kirim Semua!',
                    '#198754',
                    $('#formBulkKirim')
                );
            });
            
            $('.btn-confirm-bulk-batal').on('click', function(e) {
                e.preventDefault();
                confirmSweetAlert(
                    'Batalkan Semua?',
                    'Aksi ini akan mengembalikan semua laporan bulan ini menjadi draft. Hati-hati!',
                    'warning',
                    'Ya, Reset Semua!',
                    '#dc3545',
                    $('#formBulkBatal')
                );
            });
            
            $(document).on('submit', '.form-delete-item', function(e) {
                e.preventDefault();
                let form = this;
                Swal.fire({
                    title: 'Hapus Pekerjaan?',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
            
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    timer: 3000,
                    showConfirmButton: false,
                    timerProgressBar: true
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: "{{ session('error') }}",
                    showConfirmButton: true
                });
            @endif
        });
    </script>
@endsection