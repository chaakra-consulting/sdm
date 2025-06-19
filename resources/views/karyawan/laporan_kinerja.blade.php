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
                                    <option value="{{ $item->id }}"
                                        {{ old('id') == $item->id ? 'selected' : '' }}>
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
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="card custom-card">
            <div class="card-header d-flex justify-content-between align-items-start flex-wrap">
                <div class="card-title">
                    <h6 class="mb-0">{{ $getDataUser->name }}</h6>
                    <span class="text-muted">{{ $getDataUser->dataDiri->kepegawaian->subJabatan->nama_sub_jabatan ?? '-' }}</span>
                </div>
                <div class="d-flex justify-content-end mb-3">
                    @if (Auth::check() && Auth::user()->role->slug == 'karyawan')
                        <form action="{{ route('karyawan.laporan_kinerja') }}" method="GET" class="d-flex gap-2">
                    @elseif (Auth::check() && Auth::user()->role->slug == 'admin-sdm')
                        <form action="{{ route('admin_sdm.laporan_kinerja') }}" method="GET" class="d-flex gap-2">
                    @endif
                        <select name="month" class="form-select form-select-sm">
                            @foreach($months as $key => $month)
                                <option value="{{ $key }}" {{ $selectedMonth == $key ? 'selected' : '' }}>
                                    {{ $month }}
                                </option>
                            @endforeach
                        </select>
                        <select name="year" class="form-select form-select-sm">
                            @foreach(range(date('Y') - 2, date('Y') + 1) as $year)
                                <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start flex-wrap">
                    <span class="text-muted">Periode: {{ $startDate->translatedFormat('d F Y') }} s/d
                        {{ $endDate->translatedFormat('d F Y') }}</span>
                    <div id="selected-date" class="text-muted"></div>
                </div>
                <div style="overflow-x: auto;">
                    <div class="swiper-container mt-3 px-2">
                        <div class="swiper-wrapper">
                            <div class="d-flex overflow-auto gap-2 px-2" style="min-width: 100px">
                                @foreach ($dates as $item)
                                    @php
                                        $date = $item['date'];
                                        $isToday = $item['is_today'];
                                        $isFirstDate = $item['is_first_date'];
                                        $filterDate = $item['filter_date'];
                                        $totalDurasi = $item['total_durasi'];
                                        $jumlahTask = $item['jumlah_task'];
                                        $jam = floor($totalDurasi / 60);
                                        $menit = $totalDurasi % 60;
                                        $isActive = $item['is_active'];
                                        $isDisabled = !$item['is_working_day'];
                                    @endphp
                                    <div class="card slide-item {{ $isDisabled ? 'bg-light text-muted' : ($isActive ? 'active-slide bg-primary text-white' : 'bg-white') }}"
                                        style="min-width: 120px; min-height: 120px"
                                        @if($isDisabled) title="Hari Libur" @endif
                                            data-date="{{ $date->format('Y-m-d') }}">
                                        @if($isDisabled)
                                            <div class="ribbon ribbon-top bg-danger">Libur</div>
                                        @endif
                                        <div class="card-body text-center p-2">
                                            <div class="fw-bold fs-5">{{ $date->format('d') }}</div>
                                            <div class="fs-6">{{ $date->translatedFormat('F Y') }}</div>
                                            <div class="fs-12">Durasi : {{ $jam ? $jam . ' Jam' : '-' }} <br>
                                                {{ $menit ? $menit . ' Menit' : '' }}</div>
                                            <div class="fs-12">Sub Task : {{ $jumlahTask ? $jumlahTask : '-' }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-3 mb-3 d-flex justify-content-between align-items-start flex-wrap">
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-primary tambahLaporanKinerja" data-bs-toggle="modal"
                            data-bs-target="#staticBackdrop">
                            <i class="bi bi-plus"></i> Update Pekerjaan
                        </button>
                        @if (Auth::check() && Auth::user()->role->slug == 'karyawan')
                            <form action="{{ route('karyawan.laporan_kinerja.kirim', ['id' => auth()->user()->id]) }}" method="POST" id="formKirim">
                        @elseif (Auth::check() && Auth::user()->role->slug == 'admin-sdm')
                            <form action="{{ route('admin_sdm.laporan_kinerja.kirim', ['id' => auth()->user()->id]) }}" method="POST" id="formKirim">
                        @endif
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="tanggal" id="tanggal_terpilih_kirim">
                            <button type="submit" class="btn btn-outline-warning">
                                <i class="bi bi-send"></i> Kirim
                            </button>
                        </form>
                        @if (Auth::check() && Auth::user()->role->slug == 'karyawan')
                            <form action="{{ route('karyawan.laporan_kinerja.batal', ['id' => auth()->user()->id]) }}" method="POST" id="formBatal">
                        @elseif (Auth::check() && Auth::user()->role->slug == 'admin-sdm')
                            <form action="{{ route('admin_sdm.laporan_kinerja.batal', ['id' => auth()->user()->id]) }}" method="POST" id="formBatal">
                        @endif
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="tanggal" id="tanggal_terpilih_batal">
                            <button type="submit" class="btn btn-outline-danger">
                                <i class="bi bi-x-circle"></i> Batal
                            </button>
                        </form>
                    </div>
                    <div class="">
                        @if (Auth::check() && Auth::user()->role->slug == 'karyawan')
                            <a href="{{ route('karyawan.laporan_kinerja.detail', [
                                'id' => auth()->user()->id,
                                'month' => $selectedMonth,
                                'year' => $selectedYear
                            ]) }}" class="btn btn-outline-secondary">
                                Detail
                            </a>
                        @elseif (Auth::check() && Auth::user()->role->slug == 'admin-sdm')
                            <a href="{{ route('admin_sdm.laporan_kinerja.detail', [
                                'id' => auth()->user()->id,
                                'month' => $selectedMonth,
                                'year' => $selectedYear
                            ]) }}" class="btn btn-outline-secondary">
                                Detail
                            </a>
                        @endif
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="datatable-basic" class="table table-bordered text-nowrap w-100">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Sub Task</th>
                                <th>Task (Tipe Task)</th>
                                <th>Tanggal</th>
                                <th>Durasi</th>
                                <th width="30%">Keterangan</th>
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
@section('script')
    <style>
        .swiper-container {
            width: 100%;
            padding: 10px 0;
        }

        .swiper-slide {
            width: auto !important;
        }

        .swiper-wrapper {
            scroll-behavior: smooth;
        }

        .card-body>div {
            overflow-x: auto;
            white-space: nowrap;
        }

        .slide-item {
            cursor: pointer;
            border: 1px solid #ddd;
            transition: 0.3s ease;
        }

        .slide-item.active-slide {
            background-color: #0d6efd !important;
            color: white;
        }

        .slide-item.active-slide,
        .slide-item.active-slide * {
            color: white !important;
        }
    </style>

    <script>
        const userRole = "{{ Auth::user()->role->slug }}";
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
                    day: '2-digit',
                    month: 'long',
                    year: 'numeric'
                })}`;
                let actionUrl = '';
                if (userRole === 'karyawan') {
                    actionUrl = '/karyawan/laporan_kinerja/getDataByDate'
                } else if (userRole === 'admin-sdm') {
                    actionUrl = '/admin_sdm/laporan_kinerja/getDataByDate'
                }
                document.getElementById('tanggal_terpilih').value = selectedDate;
                document.getElementById('tanggal_terpilih_kirim').value = selectedDate;
                document.getElementById('tanggal_terpilih_batal').value = selectedDate;
                $.ajax({
                    url: actionUrl,
                    method: 'GET',
                    data: {
                        tanggal: selectedDate
                    },
                    success: function(response) {
                        const tableBody = $('#datatable-basic tbody');
                        tableBody.empty();
                        
                        if (response.data.length > 0) {
                            response.data.forEach((detail, index) => {
                                const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                                const row = `
                                        <tr>
                                            <td>${index + 1}</td>
                                            <td>${detail.nama_subtask ?? '-'}</td>
                                            <td>${detail.nama_task ?? '-'} 
                                                (${detail.nama_tipe ?? '-'})</td>
                                            <td>${new Date(detail.tanggal).toLocaleDateString('id-ID', { 
                                                day: '2-digit', 
                                                month: 'long', 
                                                year: 'numeric' 
                                            })}</td>
                                            <td>${Math.floor(detail.durasi / 60)} Jam ${detail.durasi % 60} Menit</td>
                                            <td>${detail.keterangan}</td>
                                            <td class="text-center">
                                                ${detail.is_active == 0 ? `
                                                <a href="javascript:void(0);" class="btn btn-warning btn-sm updateSubTask" 
                                                    data-id="${detail.id}" 
                                                    data-subtask_id="${detail.sub_task_id}"
                                                    data-durasi="${detail.durasi}"
                                                    data-keterangan="${detail.keterangan ? detail.keterangan.replace(/"/g, '&quot;') : ''}">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                                <form action=
                                                @if(Auth::check() && Auth::user()->role->slug == 'karyawan')
                                                    "/karyawan/laporan_kinerja/delete/${detail.id}" 
                                                @elseif(Auth::check() && Auth::user()->role->slug == 'admin-sdm')
                                                    "/admin_sdm/laporan_kinerja/delete/${detail.id}"
                                                @endif
                                                method="POST" class="d-inline">
                                                    <input type="hidden" name="_token" value="${csrf}">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                                ` : '<span class="badge bg-success">Sudah Dikirim</span>'}
                                            </td>
                                        </tr>`;
                                tableBody.append(row);
                            });
                        } else {
                            tableBody.append('<tr><td colspan="7" class="text-center">Tidak ada data</td></tr>');
                        }
                    },
                    error: function(xhr) {
                        console.error('Error:', xhr.responseText);
                        $('#datatable-basic tbody').html(
                            `<tr><td colspan="7" class="text-center text-danger">Gagal memuat data</td></tr>`
                        );
                    }
                });
            });
        });
    </script>
    <script>
        window.addEventListener('DOMContentLoaded', function() {
            const activeSlide = document.querySelector('.slide-item.active-slide');

            if (!activeSlide) {
                const todaySlide = document.querySelector(`.slide-item[data-date="${today}"]`);
                if (todaySlide) {
                    todaySlide.classList.add('active-slide', 'bg-primary', 'text-white');
                    todaySlide.click();
                }
            }
            if(activeSlide) {
                activeSlide.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center',
                    inline: 'center'
                });
                        setTimeout(() => {
                    activeSlide.click();
                }, 300);
            }
            const swiper = new Swiper('.swiper-container', {
                slidesPerView: 'auto',
                spaceBetween: 20,
                breakpoints: {
                    320: {
                        slidesPerView: 1.5
                    },
                    576: {
                        slidesPerView: 2.5
                    },
                    768: {
                        slidesPerView: 3.5
                    },
                    992: {
                        slidesPerView: 4
                    },
                    1200: {
                        slidesPerView: 5
                    }
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            const subTaskSelect = new Choices('#sub_task_id', {
                searchEnabled: true,
                removeItemButton: true,
                shouldSort: false,
                placeholder: true,
                placeholderValue: 'Pilih Sub Task',
                duplicateItemsAllowed: false,
                allowHTML: true,
                resetScrollPosition: false
            });
            const userRole = "{{ Auth::user()->role->slug }}";
            let originalTaskOptions = $('#sub_task_id').html();

            $('#staticBackdrop').on('hidden.bs.modal', function() {
                $('#formLaporanKinerja')[0].reset();

                $('#formLaporanKinerja')[0].reset();

                subTaskSelect.clearStore();
                subTaskSelect.removeActiveItems();
                subTaskSelect.setValue([]);
                subTaskSelect.setChoiceByValue('');
                subTaskSelect.input.focus();

                if ($('#sub_task_id').children('option').length < @json(count($subtasks))) {
                    $('#sub_task_id').html(originalTaskOptions);
                    subTaskSelect.destroy();
                    subTaskSelect.init();
                }
                $('input[name="_method"]').remove();
                $('input[name="durasi_jam"], input[name="durasi_menit"]').val('');
                $('#keterangan').val('');
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').remove();
                if (userRole === 'karyawan') {
                    $('#formLaporanKinerja').attr('action', '/karyawan/laporan_kinerja/store');
                } else if (userRole === 'admin-sdm') {
                    $('#formLaporanKinerja').attr('action', '/admin_sdm/laporan_kinerja/store');   
                }
            });
            $(document).on('click', '.tambahLaporanKinerja', function() {
                $(".modal-title").text('Update Pekerjaan');
                $("#nama_subtask").val('');
                $("#durasi_jam").val('');
                $("#durasi_menit").val('');
                $("#keterangan").val('');

                $("#btnSubmit").text("Simpan").show();
                $("#upload").prop("disabled", false);
                if (userRole === 'karyawan') {
                    $("#formLaporanKinerja").attr("action", "/karyawan/laporan_kinerja/store");
                } else if (userRole === 'admin-sdm') {
                    $("#formLaporanKinerja").attr("action", "/admin_sdm/laporan_kinerja/store");
                    
                }
                $("#formLaporanKinerja input[name='_method']").remove();

                $('#projectWrapper').addClass('d-none');
                $('#project_perusahaan_id').val('').prop('required', false);
                $('#tipeTaskWrapper').removeClass('col-md-6').addClass('col-md-12');

                let activeSlide = document.querySelector('.slide-item.active-slide');
                if (activeSlide) {
                    let selectedDate = activeSlide.getAttribute('data-date');
                    $('#tanggal_terpilih').val(selectedDate);
                } else {
                    const today = new Date().toLocaleDateString('id-ID', {
                        day: '2-digit',
                        month: 'long',
                        year: 'numeric'
                    });
                    $('#tanggal_terpilih').val(today);
                }

                $("input[name='durasi_jam']").val("");
                $("input[name='durasi_menit']").val("");
            });
            $(document).on('click', '.updateSubTask', function() {
                const subtaskId = $(this).data('id').toString();
                const subTaskOptionId = $(this).data('subtask_id').toString();
                const durasi = $(this).data('durasi');
                const keterangan = $(this).data('keterangan');
                const jam = Math.floor(durasi / 60);
                const menit = durasi % 60;
                const modal = new bootstrap.Modal(document.getElementById('staticBackdrop'));
                modal.show();
                
                $(".modal-title").text('Update Sub Task');
                subTaskSelect.removeActiveItems();
                subTaskSelect.setChoiceByValue(subTaskOptionId);
                subTaskSelect.input.focus();

                $("input[name='durasi_jam']").val(jam);
                $("input[name='durasi_menit']").val(menit);
                $("#keterangan").val(keterangan);

                if (userRole === 'karyawan') {
                    $("#formLaporanKinerja").attr("action", `/karyawan/laporan_kinerja/update/${subtaskId}`);
                } else if (userRole === 'admin-sdm') {
                    $("#formLaporanKinerja").attr("action", `/admin_sdm/laporan_kinerja/update/${subtaskId}`);
                }

                if ($("#formLaporanKinerja input[name='_method']").length === 0) {
                    $("#formLaporanKinerja").append(`<input type="hidden" name="_method" value="PUT">`);
                } else {
                    $("#formLaporanKinerja input[name='_method']").val('PUT');
                }

                $("#btnSubmit").text("Update");
            });
        })
    </script>
    <script>
        $(document).ready(function() {
            $('.btn-edit-project').click(function() {
                $('.btn-edit-project').hide();
                $('.btn-batal-edit').prop('hidden', false);
                $(".btn-submit-project").prop('hidden', false);

                $('.btn-batal-edit').click(function() {
                    $('.btn-edit-project').fadeIn(200);
                    $('.btn-batal-edit').prop('hidden', true);
                    $(".btn-submit-project").prop('hidden', true);
                })
            });
        });
    </script>
@endsection
