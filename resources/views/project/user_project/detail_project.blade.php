@extends('layouts.main')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-4 col-lg-4">
                <div class="card mb-4">
                    <form action="{{ route('karyawan.update.project', $project->project_perusahaan->id) }}" method="POST">
                        @csrf
                        @method('put')
                        <div class="card-body">
                            <div class="ps-0 mb-3">
                                <div class="main-profile-overview">
                                    <div class="d-flex justify-content-between mb-2">
                                        <div>
                                            <h5 class="main-profile-name">
                                                {{ $project->project_perusahaan->perusahaan->nama_perusahaan }}
                                            </h5>
                                            @if ($project->project_perusahaan->status == 'belum')
                                                <span class="text-danger">Belum</span>
                                            @elseif ($project->project_perusahaan->status == 'proses')
                                                <span class="text-primary">Proses</span>
                                            @elseif ($project->project_perusahaan->status == 'selesai')
                                                <span class="text-success">Selesai</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="main-profile-bio">
                                        <p class="mb-0">Nama Project :</p>
                                        <h4>{{ $project->project_perusahaan->nama_project }}</h4>
                                    </div>
                                    <hr class="border-1">
                                    <h6 class="fs-14">Deskripsi</h6>
                                    <div class="skill-bar mb-3 clearfix mt-3">
                                        <span>Skala Project : </span>
                                        <span
                                            class="text-sm font-bold">{{ ucwords($project->project_perusahaan->skala_project) }}</span>
                                        <div class="progress progress-sm mt-2">
                                            @if ($project->project_perusahaan->skala_project == 'kecil')
                                                <div class="progress-bar bg-primary-gradient" role="progressbar"
                                                    aria-valuenow="33.3" aria-valuemin="0" aria-valuemax="100"
                                                    style="width: 33.3%"></div>
                                            @elseif ($project->project_perusahaan->skala_project == 'sedang')
                                                <div class="progress-bar bg-primary-gradient" role="progressbar"
                                                    aria-valuenow="66.6" aria-valuemin="0" aria-valuemax="100"
                                                    style="width: 66.6%"></div>
                                            @elseif ($project->project_perusahaan->skala_project == 'besar')
                                                <div class="progress-bar bg-primary-gradient" role="progressbar"
                                                    aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"
                                                    style="width: 100%"></div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="skill-bar mb-1 clearfix">
                                        <span>Timeline</span>
                                        <div class="form-group">
                                            <label for="waktu_mulai">Waktu Mulai :</label>
                                            <div class="input-group">
                                                <div class="input-group-text text-muted">
                                                    <i class="ri-calendar-line"></i>
                                                </div>
                                                <input type="text" class="form-control" name="waktu_mulai"
                                                    value="{{ old('waktu_mulai', $project->project_perusahaan->waktu_mulai ?? now()->format('Y-m-d')) }}"
                                                    id="humanfrienndlydate" placeholder="Waktu Mulai"
                                                    {{ $project->project_perusahaan->waktu_mulai != null ? 'disabled' : '' }}>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="waktu_berakhir">Waktu Berakhir :</label>
                                            <div class="input-group">
                                                <div class="input-group-text text-muted"> <i class="ri-calendar-line"></i>
                                                </div>
                                                <input type="text" class="form-control" name="waktu_berakhir"
                                                    value="{{ $project->project_perusahaans->waktu_berakhir ?? '' }}"
                                                    id="humanfrienndlydate" placeholder="Waktu Berakhir"
                                                    {{ $project->project_perusahaan->waktu_berakhir != null ? 'disabled' : '' }}>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="skill-bar mb-1 clearfix">
                                        <span>Deadline</span>
                                        <div class="form-group">
                                            <div class="input-group">
                                                <div class="input-group-text text-muted"> <i class="ri-calendar-line"></i>
                                                </div>
                                                <input type="text" class="form-control" name="berakhir"
                                                    value="{{ $project->project_perusahaan->deadline }}"
                                                    id="humanfrienndlydate" placeholder="deadline" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="skill-bar clearfix">
                                        <span>Progres</span>
                                        <input type="range" name="progres" class="form-range" id="bootstrap-range"
                                            min="0" max="100"
                                            value="{{ $project->project_perusahaan->progres ?? 0 }}">
                                        <span id="progress-value">{{ $project->project_perusahaan->progres ?? 0 }}%</span>
                                    </div>
                                    <div class="progress progress-sm">
                                        <div class="progress-bar bg-info-gradient" id="bootstrap-progress-bar"
                                            role="progressbar"
                                            style="width:{{ $project->project_perusahaan->progres ?? 0 }}%;"
                                            aria-valuemin="0" aria-valuemax="100">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if ($project->project_perusahaan->waktu_mulai != null)
                                <button type="button" class="btn btn-danger btn-batal-edit" hidden>Batal</button>
                                <button type="button" class="btn btn-warning btn-edit-project">Edit</button>
                            @endif
                            <button type="submit" class="btn btn-primary btn-submit-project"
                                {{ $project->project_perusahaan->waktu_mulai != null ? 'hidden' : '' }}>{{ $project->project_perusahaan->waktu_mulai != null ? 'Update' : 'Mulai' }}</button>
                        </div>
                    </form>
                </div>
                <div class="mt-1">
                    <a href="{{ route('karyawan.project') }}" class="btn btn-secondary">Kembali</a>
                </div>
            </div>
            <div class="col-xl-8 col-lg-8">
                <div class="card custom-card">
                    <div class="modal fade" id="taskModal" tabindex="-1" aria-labelledby="taskModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="taskModalLabel">Tambah Task</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                {{-- <form action="{{ route('task.store') }}" method="POST"> --}}
                                    @csrf
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="taskDate" class="form-label">Tanggal</label>
                                            <input type="text" class="form-control" id="taskDate" name="tanggal"
                                                readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label for="taskName" class="form-label">Nama Task</label>
                                            <input type="text" class="form-control" id="taskName" name="nama_task"
                                                required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="taskDescription" class="form-label">Deskripsi</label>
                                            <textarea class="form-control" id="taskDescription" name="deskripsi" rows="3"></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary">Simpan Task</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="card-header">
                        <div class="card-title">Timeline</div>
                    </div>
                    <div class="card-body">
                        <div id='calendar2'></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <!-- Date & Time Picker JS -->
    <script src="{{ asset('/Tema/dist/assets/libs/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('/Tema/dist/assets/js/date&time_pickers.js') }}"></script>

    <!-- Moment JS -->
    <script src="{{ asset('/Tema/dist/assets/libs/moment/moment.js') }}"></script>

    <!-- Fullcalendar JS -->
    <script src="{{ asset('/Tema/dist/assets/libs/fullcalendar/main.min.js') }}"></script>
    <script src="{{ asset('/Tema/dist/assets/js/fullcalendar.js') }}"></script>
    <script>
        $(document).ready(function() {
            $(".update-project").click(function() {
                console.log('test')
                $(".container-peringatan").slideUp(200);
                $(".container-project").prop('hidden', false).slideDown(200);
            })

            $('.btn-edit-project').click(function() {
                console.log('test')
                $('.btn-edit-project').hide();
                $('.btn-batal-edit').prop('hidden', false);
                $(".btn-submit-project").prop('hidden', false);

                $('.btn-batal-edit').click(function() {
                    $('.btn-edit-project').fadeIn(200);
                    $('.btn-batal-edit').prop('hidden', true);
                    $(".btn-submit-project").prop('hidden', true);
                })
            })
        })
    </script>
    <script>
        document.getElementById("bootstrap-range").addEventListener("input", function() {
            let value = this.value;
            document.getElementById("progress-value").textContent = value + "%";
            document.getElementById("bootstrap-progress-bar").style.width = value + "%";
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function(){
            let calendarEl = document.getElementById('calendar2');
    
            if (calendarEl) {
                let calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay'
                    },
                    events: [{
                            title: '📌 Mulai: {{ $project->project_perusahaan->nama_project }}',
                            start: '{{ $project->project_perusahaan->waktu_mulai ?? now()->format('Y-m-d') }}',
                            color: '#007bff',
                            extendedProps: {
                                description: 'Project dimulai pada {{ $project->project_perusahaan->waktu_mulai }}'
                            }
                        },
                        {
                            title: '⏳ Deadline: {{ $project->project_perusahaan->nama_project }}',
                            start: '{{ $project->project_perusahaan->deadline ?? now()->format('Y-m-d') }}',
                            color: '#dc3545',
                            extendedProps: {
                                description: 'Batas waktu untuk project {{ $project->project_perusahaan->nama_project }}'
                            }
                        }
                    ],
                    dateClick: function(info){
                        $('#taskDate').val(info.dateStr);
                        $('#taskModal').modal('show');
                    }
                });
    
                calendar.render();
            }
        })
    </script>
    <script>
        calendar.on('eventClick', function(info) {
            $(info.el).popover({
                title: info.event.title,
                content: info.event.extendedProps.description,
                trigger: 'focus',
                placement: 'top'
            }).popover('show');
        });
    </script>
@endsection
