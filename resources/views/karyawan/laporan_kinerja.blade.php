@extends('layouts.main')

@section('content')
<div class="container-fluid">
    <div class="card custom-card">
        <div class="card-header">
            <div class="card-title">
                <h6 class="mb-0">{{ $getDataUser->name }}</h6>
                <span class="text-muted">{{ $getDataUser->dataDiri->kepegawaian->subJabatan->nama_sub_jabatan ?? '-' }}</span>
            </div>
        </div>
        <div class="card-body">
            {{-- <span class="text-muted">Periode :</span> --}}
            <div class="swiper-container mt-3">
                <div class="swiper-wrapper">
                    @foreach($dates as $date)
                        <div class="swiper-slide text-center p-3 slide-item {{ $loop->first ? 'active-slide' : '' }}" 
                            data-date="{{ $date->format('Y-m-d') }}"
                            style="border-radius: 15px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); min-width: 150px; cursor: pointer;">
                            <h2 class="m-0 slide-day">{{ $date->format('d') }}</h2>
                            <small class="text-uppercase slide-month">{{ $date->translatedFormat('F Y') }}</small>
                            <ul class="list-unstyled mt-2 mb-0 slide-info">
                                <li>• Project</li>
                                <li>• Jam</li>
                            </ul>
                        </div>
                    @endforeach
                </div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
            </div>
            
            </div>
        </div>
    </div>

    <div id="selected-date" class="mt-3 text-muted"></div>
</div>
@endsection

@section('script')
    <style>
        .slide-item {
            background-color: white;
            color: #333;
            transition: 0.3s ease;
        }

        .slide-item.active-slide {
            background-color: #0d6efd !important;
            color: white;
        }

        .slide-item.active-slide h2,
        .slide-item.active-slide small,
        .slide-item.active-slide li {
            color: white !important;
        }
        .swiper-container {
            padding: 10px 0;
        }

        @media (max-width: 576px) {
            .swiper-container {
                overflow-x: auto;
            }
        }
        .swiper-slide {
            width: auto !important;
        }
    </style>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css"/>
    <script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>

    <script>
        const swiper = new Swiper('.swiper-container', {
            slidesPerView: 'auto',
            spaceBetween: 20,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
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
                document.querySelectorAll('.slide-item').forEach(s => s.classList.remove('active-slide'));
                this.classList.add('active-slide');

                const selectedDate = this.dataset.date;
                document.getElementById('selected-date').innerText = `Tanggal dipilih: ${selectedDate}`;
            });
        });
    </script>
@endsection
