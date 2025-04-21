@extends('layouts.main')

@section('content')
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="staticBackdropLabel"></h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="POST" id="formSubTask" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="task_id">Task</label>
                            <select name="task_id" id="task_id" data-trigger class="form-control" required>
                                <option value="" selected disabled>Pilih Task</option>
                                @foreach ($task as $item)
                                    <option value="{{ $item->id }}" 
                                        {{ old('task_id', $getSubtask == null ? '' : $getSubtask->task->id) == $item->id ? 'selected' : '' }}
                                        >{{ $item->nama_task }} ({{ $item->tipe_task->nama_tipe }})</option>
                                @endforeach                        
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="durasi">Durasi</label>
                            <div class="row">
                                <div class="col-6">
                                    <div class="input-group">
                                        <input type="number" min="0" name="durasi_jam" class="form-control" placeholder="Jam" value="" required>
                                        <span class="input-group-text">Jam</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="input-group">
                                        <input type="number" min="0" max="59" name="durasi_menit" class="form-control" placeholder="Menit" value="" required>
                                        <span class="input-group-text">Menit</span>
                                    </div>
                                </div>
                            </div>                                
                        </div>
                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <textarea class="form-control" name="keterangan" id="keterangan" cols="10" rows="5"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="upload">Lampiran</label>
                                <input type="file" class="form-control" name="upload[]" id="upload" multiple>
                                <div id="preview-area" class="row mt-3"></div>
                                <p class="text-center" id="detail_upload"></p>
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
                <div id="selected-date" class="text-muted small mt-1"></div>
            </div>        
            <div class="card-body">
                <span class="text-muted">Periode: {{ $startDate->translatedFormat('d F Y') }} s/d {{ $endDate->translatedFormat('d F Y') }}</span>
                <div style="overflow-x: auto;">
                    <div class="swiper-container mt-3 px-2">
                        <div class="swiper-wrapper">
                            <div class="d-flex overflow-auto gap-2 px-2" style="min-width: 100px">
                                @foreach ($dates as $item)
                                    @php
                                        $date = $item['date'];
                                        $isToday = $item['is_today'];
                                    @endphp
                                    <div class="card slide-item {{ $isToday ? 'active-slide bg-primary text-white' : 'bg-white' }}" 
                                        style="min-width: 120px; min-height: 120px" id="{{ $isToday ? 'today-slide' : '' }}"
                                        data-date="{{ $date->format('Y-m-d') }}">
                                        <div class="card-body text-center p-2">
                                            <div class="fw-bold fs-5">{{ $date->format('d') }}</div>
                                            <div class="fs-6">{{ $date->translatedFormat('F Y') }}</div>
                                        </div>
                                        <ul style="list-style: none; font-size: 12px;">
                                            <li>Durasi :</li>
                                            <li>Task :</li>
                                        </ul>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-3 mb-3 d-flex justify-content-between align-items-start flex-wrap">
                    <div class="">
                        <button type="button" class="btn btn-outline-primary tambahSubTask" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                            <i class="bi bi-plus"></i> Tambah Sub Task
                        </button>
                        <button type="button" class="btn btn-outline-warning">
                            Kirim
                        </button>
                    </div>
                    <div class="">
                        <button type="button" class="btn btn-outline-secondary">
                            Detail
                        </button>
                    </div>
                </div>        
                <div class="table-responsive">
                    <table id="datatable-basic" class="table table-bordered text-nowrap w-100">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tipe Task</th>
                                <th>Task</th>
                                <th>Tanggal</th>
                                <th>Durasi</th>
                                <th>Keterangan</th>
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

        .card-body > div {
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

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css"/>
    <script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>

    <script>
        const swiper = new Swiper('.swiper-container', {
            slidesPerView: 'auto',
            spaceBetween: 20,
            breakpoints: {
                320: { slidesPerView: 1.5 },
                576: { slidesPerView: 2.5 },
                768: { slidesPerView: 3.5 },
                992: { slidesPerView: 4 },
                1200: { slidesPerView: 5 }
            }
        });
        document.querySelectorAll('.slide-item').forEach(slide => {
            slide.addEventListener('click', function () {
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
                document.getElementById('tanggal_terpilih').value = selectedDate;
                $.ajax({
                    url: '/karyawan/laporan_kinerja/getDataByDate',
                    method: 'GET',
                    data: { tanggal: selectedDate },
                    success: function(response){
                        console.log('Respons dari server:', response);
                        const tableBody = $('#datatable-basic tbody');
                        tableBody.empty();
                        if (response.data.length > 0) {
                            response.data.forEach((subtask, index) => {
                                const hasLampiran = subtask.lampiran && subtask.lampiran.length > 0;
                                const lampiranButton = hasLampiran
                                    ? `<button type="button" class="btn btn-primary" onclick="previewLampiran(${subtask.id}, ${JSON.stringify(subtask.lampiran).replace(/"/g, '&quot;')})">
                                            <i class="ti ti-file-search"
                                                data-bs-custom-class="tooltip-secondary"
                                                data-bs-placement="top" title="Lihat Lampiran!"></i>
                                    </button>`
                                    : `<span class="text-muted">Tidak ada</span>`;

                                const row = `
                                    <tr>
                                        <td>${index + 1}</td>
                                        <td>${subtask.nama_tipe || '-'}</td>
                                        <td>${subtask.nama_task || '-'}</td>
                                        <td>
                                            ${new Date(subtask.tgl_sub_task).toLocaleDateString('id-ID', {
                                            day: '2-digit',
                                            month: 'long',
                                            year: 'numeric'
                                            })}
                                        </td>
                                        <td>${Math.floor(subtask.durasi / 60)} Jam, ${subtask.durasi % 60} Menit</td>
                                        <td>${subtask.keterangan}</td>
                                        <td>
                                            ${lampiranButton}
                                            <a href="javascript:void(0);" class="btn btn-warning updateSubTask"
                                                data-bs-toggle="modal"    
                                                data-id="${subtask.id}"
                                                data-task_id="${subtask.task_id}"
                                                data-durasi="${subtask.durasi}"
                                                data-keterangan="${subtask.keterangan}"
                                                data-bs-target="#staticBackdrop">
                                                <i data-bs-toggle="tooltip"
                                                    data-bs-custom-class="tooltip-secondary"
                                                    data-bs-placement="top" title="Update Sub Task!"
                                                    class="bi bi-pencil-square"></i>
                                            </a>
                                            <form action="/karyawan/subtask/delete/${subtask.id}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger delete"
                                                    data-bs-toggle="tooltip" data-bs-custom-class="tooltip-danger"
                                                    data-bs-placement="top" title="Hapus Sub Task!">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>  
                                        </td>
                                    </tr>`;
                                tableBody.append(row);
                            });
                        } else {
                            tableBody.append('<tr><td colspan="7" class="text-center">Tidak ada data</td></tr>');
                        }
                    }
                });
            });
        });
    </script>
    <script>
        window.addEventListener('DOMContentLoaded', function () {
            const todaySlide = document.getElementById('today-slide');
            if (todaySlide) {
                todaySlide.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center',
                    inline: 'center'
                }),
                todaySlide.click();
            }
        });
    </script>
    <script>
        $(document).ready(function() {
            $(document).on('click', '.tambahSubTask', function() {

                $(".modal-title").text('Tambah Sub Task');
                $("#nama_task").val('');
                $("#durasi_jam").val('');
                $("#durasi_menit").val('');
                $("#keterangan").val('');  

                $("#previewImage, #previewPDF").hide().attr("src", "");
                $("#detail_upload").html("");

                $("#btnSubmit").text("Simpan").show();
                $("#upload").prop("disabled", false);
                $("#formSubTask").attr("action", "/karyawan/subtask/store");
                $("#formSubTask input[name='_method']").remove();

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
            $(document).on('click', '.updateSubTask', function () {
                const subtaskId = $(this).data('id');
                const taskId = $(this).data('task_id');
                const durasi = $(this).data('durasi');
                const keterangan = $(this).data('keterangan');
                const lampiran = $(this).data('lampiran');

                const jam = Math.floor(durasi / 60);
                const menit = durasi % 60;

                $(".modal-title").text('Update Sub Task');
                $("#task_id").val(taskId);
                $("input[name='durasi_jam']").val(jam);
                $("input[name='durasi_menit']").val(menit);
                $("#keterangan").val(keterangan);

                $("#formSubTask").attr("action", `/karyawan/subtask/update/${subtaskId}`);
                
                if ($("#formSubTask input[name='_method']").length === 0) {
                    $("#formSubTask").append(`<input type="hidden" name="_method" value="PUT">`);
                } else {
                    $("#formSubTask input[name='_method']").val('PUT');
                }

                $("#btnSubmit").text("Update");
                $("#upload").prop("disabled", false);

                $("#preview-area").html("");
                $("#detail_upload").html("");

                if (lampiran && lampiran.length > 0) {
                    lampiran.forEach(file => {
                        const filePreview = `
                            <div class="col-4">
                                <a href="/path/to/file/${file}" target="_blank">${file}</a>
                            </div>
                        `;
                        $("#preview-area").append(filePreview);
                    });
                }
            });
        })
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
                    if (file.size > 5 * 1024 * 1024) { 
                        alert(`File ${file.name} melebihi batas maksimal 5MB`);
                        return;
                    }
                    
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
        function previewLampiran(subTaskId, lampiranList) {
            let htmlContent = '';
            if (lampiranList.length > 0) {
                htmlContent += `
                    <div id="carouselDynamicLampiran" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                `;
        
                lampiranList.forEach((lampiran, index) => {
                    const file = lampiran.lampiran;
                    const extension = file.split('.').pop().toLowerCase();
                    const isImage = ['jpg', 'jpeg', 'png'].includes(extension);
                    const isPDF = extension === 'pdf';
        
                    htmlContent += `<div class="carousel-item ${index === 0 ? 'active' : ''}">`;
                    if (isImage) {
                        htmlContent += `<img src="/uploads/${file}" class="d-block mx-auto img-fluid" style="max-height: 500px;">`;
                    } else if (isPDF) {
                        htmlContent += `<iframe src="/uploads/${file}" class="d-block mx-auto" width="100%" height="500px"></iframe>`;
                    } else {
                        htmlContent += `<div class="text-center text-muted">File tidak bisa dipreview: <a href="/uploads/${file}" target="_blank">Download</a></div>`;
                    }
                    htmlContent += `</div>`;
                });
        
                htmlContent += `
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselDynamicLampiran" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselDynamicLampiran" data-bs-slide="next">
                            <span class="carousel-control-next-icon"></span>
                        </button>
                    </div>
                `;
            } else {
                htmlContent = '<p class="text-center text-muted">Tidak ada lampiran yang tersedia</p>';
            }
            const modalHTML = `
                <div class="modal fade" id="dynamicLampiranModal" tabindex="-1" aria-labelledby="lampiranModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Lampiran Sub Task</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="$('#dynamicLampiranModal').remove();"></button>
                            </div>
                            <div class="modal-body">
                                ${htmlContent}
                            </div>
                        </div>
                    </div>
                </div>
            `;
            $('body').append(modalHTML);
            const lampiranModal = new bootstrap.Modal(document.getElementById('dynamicLampiranModal'));
            lampiranModal.show();
        }
    </script>        
@endsection