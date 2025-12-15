@extends('layouts.main')

@section('content')
    <div class="container-fluid">
        <div class="card custom-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="card-title mb-0">
                    Notifikasi
                </h6>
                <button id="markAllRead" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-check-double"></i> Tandai Semua Dibaca
                </button>
            </div>
            <div class="card-body">
                @if ($notifications->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach ($notifications as $notification)
                            <div class="list-group-item {{ $notification->read_at ? '' : 'bg-light' }}">
                                <div class="d-flex w-100 justify-content-between">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">
                                            @if ($notification->type === 'laporan_kinerja_rejected')
                                                <i class="fas fa-times-circle text-danger"></i> Laporan Kinerja Ditolak
                                            @elseif ($notification->type === 'laporan_kinerja_revised')
                                                <i class="fas fa-edit text-warning"></i> Laporan Kinerja Direvisi
                                            @else 
                                                <i class="fas fa-bell"></i> Notifikasi
                                            @endif
                                        </h6>
                                        <p class="mb-1">{{ $notification->data['message'] }}</p>
                                        <small class="text-muted">
                                            <strong>Project:</strong> {{ $notification->data['project'] ?? '-' }}<br>
                                            <strong>Sub Task:</strong> {{ $notification->data['subtask'] ?? '-' }}<br>
                                            <strong>Tanggal:</strong> {{ isset($notification->data['tanggal']) ? \Carbon\Carbon::parse($notification->data['tanggal'])->format('d F Y') : '-' }}<br>
                                            
                                            @if (!empty($notification->data['notes']))
                                                <strong>Catatan:</strong> {{ $notification->data['notes'] }}
                                            @endif
                                        </small>
                                    </div>
                                    <div class="text-end">
                                        <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                        @if (!$notification->read_at)
                                            <br><span class="badge bg-primary mt-1">Baru</span>
                                        @endif
                                        @if (isset($notification->data['action_url']))
                                            <br><a href="{{ $notification->data['action_url'] }}" class="btn btn-sm btn-outline-primary mt-2">
                                                Lihat Detail
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    {{ $notifications->links() }}
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Tidak ada Notifikasi</h5>
                        <p class="text-muted">Anda belum memiliki Notifikasi</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function () {
            $('#markAllRead').click(function(){
                $.post('{{ route("notifications.markAllAsRead") }}').done(function (response) {
                    if (response.success) {
                        location.reload();
                    }
                }); 
            });

            $('.list-group-item').click(function() {
                const notificationId = $(this).data('id');
                if (notificationId && !$(this).hasClass('read')) {
                    $.post(`{{ url('/notifications') }}/${notificationId}/read`)
                        .done(function(response) {
                            if (response.success) {
                                $(this).removeClass('bg-light').find('.badge').remove();
                            }
                        }.bind(this));
                }
            });
        });
    </script>
@endsection