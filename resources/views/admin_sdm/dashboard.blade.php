@extends('layouts.main')

@section('content')
    <div class="container-fluid">
        <style>
            #barKehadiranValuePerHari,
            #barKehadiranPercentagePerHari {
                max-height: 300px;
            }
        </style>
        <!-- row -->
        <div class="row">
            <div class="card-body">
                <form action="" method="GET" class="ms-auto" style="max-width: 400px;">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-8">
                            <div class="input-group">
                                <span class="input-group-text"><i class="ri-calendar-line"></i></span>
                                <input type="text" class="form-control" id="date_range" name="date_range"
                                    value="{{ old('date_range', $default_range) }} placeholder="Pilih Range Tanggal">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" id="applyFilter" class="btn btn-primary w-100">Filter</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-lg-4">
                <div class="card bg-primary-gradient text-fixed-white">
                    <div class="card-body text-fixed-white">
                        <div class="row">

<div class="container-fluid">
    <style>
    #barKehadiranValuePerHari, #barKehadiranPercentagePerHari {
        max-height: 300px;
    }
    </style>
    <!-- row -->
    <div class="row">
        <div class="card-body">
            <form action="" method="GET" class="ms-auto" style="max-width: 400px;">
                <div class="row g-3 align-items-end">
                    <div class="col-md-8">
                        <div class="input-group">
                            <span class="input-group-text"><i class="ri-calendar-line"></i></span>
                            <input type="text" class="form-control" autocomplete="false" readonly id="date_range" name="date_range" value="{{ old('date_range', $default_range) }}" placeholder="Pilih Range Tanggal">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" id="applyFilter" class="btn btn-primary w-100">Filter</button>
                    </div>
                </div>
            </form>
        </div> 
        <div class="col-lg-4">
            <div class="card bg-primary-gradient text-fixed-white">
                <div class="card-body text-fixed-white">
                    <div class="row">
                            <div class="mt-0 text-center">
                                <span class="text-fixed-white">{{ $widget_absensi[0]->nama }}</span>
                                <h3 class="text-fixed-white mt-3">{{ $widget_absensi[0]->count }}%</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card bg-warning-gradient text-fixed-white">
                    <div class="card-body text-fixed-white">
                        <div class="row">
                            <div class="mt-0 text-center">
                                <span class="text-fixed-white">{{ $widget_absensi[1]->nama }}</span>
                                <h3 class="text-fixed-white mt-3">{{ $widget_absensi[1]->count }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card bg-danger-gradient text-fixed-white">
                    <div class="card-body text-fixed-white">
                        <div class="row">
                            <div class="mt-0 text-center">
                                <span class="text-fixed-white">{{ $widget_absensi[2]->nama }}</span>
                                <h3 class="text-fixed-white mb-0">{{ $widget_absensi[2]->rata_rata }} Menit</h3>
                                <p class="mb-0 fs-16 text-fixed-white">{{ $widget_absensi[2]->count }} Peristiwa</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- row closed -->

        <!-- row opened -->
        <div class="row">
            {{-- <div class="col-xl-6">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">Data Keterangan Absensi</div>
                </div>
                <div class="card-body">
                    <canvas id="keteranganAbsensi" class="chartjs-chart"></canvas>
                </div>
            </div>
        </div> --}}
            <div class="col-xl-6">
                <div class="card custom-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div class="card-title">
                            Data Keterangan Absensi
                        </div>
                        <ul class="nav nav-tabs nav-justified nav-style-1 d-sm-flex d-block" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" role="tab"
                                    href="#keterangan-absensi-percentage" aria-selected="true">Percentage</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" role="tab" href="#keterangan-absensi-value"
                                    aria-selected="false">Value</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane show active text-muted" id="keterangan-absensi-percentage" role="tabpanel">
                                <div id="chart-wrapper-keteranganAbsensiPercentage" class="text-center">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <canvas id="keteranganAbsensiPercentage" class="chartjs-chart"
                                        style="display:none;"></canvas>
                                </div>

                            </div>

                            <div class="tab-pane text-muted" id="keterangan-absensi-value" role="tabpanel">
                                <div id="chart-wrapper-keteranganAbsensiValue" class="text-center">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <canvas id="keteranganAbsensiValue" class="chartjs-chart"
                                        style="display:none;"></canvas>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="card custom-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div class="card-title">
                            Data Kehadiran Per Bulan
                        </div>
                        <ul class="nav nav-tabs nav-justified nav-style-1 d-sm-flex d-block" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" role="tab" href="#kehadiran-percentage"
                                    aria-selected="true">Percentage</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" role="tab" href="#kehadiran-value"
                                    aria-selected="false">Value</a>
                            </li>
                        </ul>
                    </div>
                    <?php $defaultYear = $arr_year[0] ?? date('Y'); ?>
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div class="card-title">
                        </div>
                        <ul class="nav nav-tabs nav-justified nav-style-1 d-sm-flex d-block" role="tablist">
                            <div class="btn-group">
                                <button type="button" class="btn btn-outline-primary dropdown-toggle"
                                    data-bs-toggle="dropdown" aria-expanded="false" id="selectedYearKehadiranPerBulan">
                                    <?= $defaultYear ?>
                                </button>
                                <ul class="dropdown-menu">
                                    <?php foreach ($arr_year as $year): ?>
                                    <li><a class="dropdown-item year-option" href="javascript:void(0);"
                                            data-target="bulan" data-year="<?= $year ?>"><?= $year ?></a></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane show active text-muted" id="kehadiran-percentage" role="tabpanel">
                                <div id="chart-wrapper-barKehadiranPercentage" class="text-center">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <canvas id="barKehadiranPercentage"
                                        class="chartjs-chart"style="display:none;"></canvas>
                                </div>
                            </div>
                            <div class="tab-pane text-muted" id="kehadiran-value" role="tabpanel">
                                <div id="chart-wrapper-barKehadiranValue" class="text-center">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <canvas id="barKehadiranValue" class="chartjs-chart"style="display:none;"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div class="card-title">
                            Data Kehadiran Per Hari
                        </div>
                        <ul class="nav nav-tabs nav-justified nav-style-1 d-sm-flex d-block" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" role="tab"
                                    href="#kehadiran-percentage-per-hari" aria-selected="true">Percentage</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" role="tab" href="#kehadiran-value-per-hari"
                                    aria-selected="false">Value</a>
                            </li>
                        </ul>
                    </div>
                    <?php
                    $defaultYear = $arr_year[0] ?? date('Y');
                    $defaultMonth = $month ?? '01';
                    $months = [
                        '01' => 'Januari',
                        '02' => 'Februari',
                        '03' => 'Maret',
                        '04' => 'April',
                        '05' => 'Mei',
                        '06' => 'Juni',
                        '07' => 'Juli',
                        '08' => 'Agustus',
                        '09' => 'September',
                        '10' => 'Oktober',
                        '11' => 'November',
                        '12' => 'Desember',
                    ];
                    ?>
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div class="card-title"></div>
                        <ul class="nav nav-tabs nav-justified nav-style-1 d-sm-flex d-block" role="tablist">
                            <div class="btn-group">
                                <!-- Dropdown Tahun -->
                                <button type="button" class="btn btn-outline-primary dropdown-toggle"
                                    data-bs-toggle="dropdown" aria-expanded="false" id="selectedYearKehadiranPerHari">
                                    <?= $defaultYear ?>
                                </button>
                                <ul class="dropdown-menu">
                                    <?php foreach ($arr_year as $year): ?>
                                    <li><a class="dropdown-item year-option" href="javascript:void(0);"
                                            data-target="hari" data-year="<?= $year ?>"><?= $year ?></a></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>

                            <div class="btn-group ms-2">
                                <!-- Dropdown Bulan -->
                                <button type="button" class="btn btn-outline-primary dropdown-toggle"
                                    data-bs-toggle="dropdown" aria-expanded="false" id="selectedMonthKehadiranPerHari">
                                    <?= $months[$defaultMonth] ?>
                                </button>

                                <ul class="dropdown-menu">
                                    <?php foreach ($months as $key => $month): ?>
                                    <li><a class="dropdown-item month-option" href="javascript:void(0);"
                                            data-target="hari" data-month="<?= $key ?>"><?= $month ?></a></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </ul>
                    </div>

                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane show active text-muted" id="kehadiran-percentage-per-hari"
                                role="tabpanel">
                                <div id="chart-wrapper-barKehadiranPercentagePerHari" class="text-center">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <canvas id="barKehadiranPercentagePerHari"
                                        class="chartjs-chart"style="display:none;"></canvas>
                                </div>
                            </div>
                            <div class="tab-pane text-muted" id="kehadiran-value-per-hari" role="tabpanel">
                                <div id="chart-wrapper-barKehadiranValuePerHari" class="text-center">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <canvas id="barKehadiranValuePerHari"
                                        class="chartjs-chart"style="display:none;"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Data Rata-Rata Jam Masuk</div>
                    </div>
                    <div class="card-body">
                        <div id="chart-wrapper-barAbsensi" class="text-center">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <canvas id="barAbsensi" class="chartjs-chart"style="display:none;"></canvas>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <script>
        const url = "{{ url('/admin_sdm/dashboard_chart') }}";
        const default_range = @json($default_range);
        <div class="col-xl-6">
            <div class="card custom-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="card-title">
                        Data Keterangan Absensi
                    </div>
                    <ul class="nav nav-tabs nav-justified nav-style-1 d-sm-flex d-block" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" role="tab"
                                href="#keterangan-absensi-percentage" aria-selected="true">Percentage</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" role="tab" href="#keterangan-absensi-value"
                                aria-selected="false">Value</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane show active text-muted" id="keterangan-absensi-percentage" role="tabpanel">
                            <div id="chart-wrapper-keteranganAbsensiPercentage" class="text-center">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <canvas id="keteranganAbsensiPercentage" class="chartjs-chart" style="display:none;"></canvas>
                            </div>
                            
                        </div>
                    
                        <div class="tab-pane text-muted" id="keterangan-absensi-value" role="tabpanel">
                            <div id="chart-wrapper-keteranganAbsensiValue" class="text-center">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <canvas id="keteranganAbsensiValue" class="chartjs-chart" style="display:none;"></canvas>
                            </div>
                            
                        </div>
                    </div>                    
                </div>
            </div>
        </div> 
        <div class="col-xl-6">
            <div class="card custom-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="card-title">
                        Data Kehadiran Per Bulan
                    </div>
                    <ul class="nav nav-tabs nav-justified nav-style-1 d-sm-flex d-block" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" role="tab"
                                href="#kehadiran-percentage" aria-selected="true">Percentage</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" role="tab" href="#kehadiran-value"
                                aria-selected="false">Value</a>
                        </li>
                    </ul>
                </div>
                <?php $defaultYear = $arr_year[0] ?? date('Y');?>
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="card-title">
                    </div>
                    <ul class="nav nav-tabs nav-justified nav-style-1 d-sm-flex d-block" role="tablist">
                        <div class="btn-group">
                            <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" id="selectedYearKehadiranPerBulan">
                                <?= $defaultYear ?>
                            </button>
                            <ul class="dropdown-menu">
                                <?php foreach ($arr_year as $year): ?>
                                    <li><a class="dropdown-item year-option" href="javascript:void(0);" data-target="bulan" data-year="<?= $year ?>"><?= $year ?></a></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane show active text-muted" id="kehadiran-percentage" role="tabpanel">
                            <div id="chart-wrapper-barKehadiranPercentage" class="text-center">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <canvas id="barKehadiranPercentage" class="chartjs-chart"style="display:none;"></canvas>
                            </div>                        
                        </div>
                        <div class="tab-pane text-muted" id="kehadiran-value" role="tabpanel">
                            <div id="chart-wrapper-barKehadiranValue" class="text-center">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <canvas id="barKehadiranValue" class="chartjs-chart"style="display:none;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>  
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="card-title">
                        Data Kehadiran Per Hari
                    </div>
                    <ul class="nav nav-tabs nav-justified nav-style-1 d-sm-flex d-block" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" role="tab"
                                href="#kehadiran-percentage-per-hari" aria-selected="true">Percentage</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" role="tab" href="#kehadiran-value-per-hari"
                                aria-selected="false">Value</a>
                        </li>
                    </ul>
                </div>
                <?php 
                $defaultYear = $arr_year[0] ?? date('Y');
                $defaultMonth = $month ?? '01';
                $months = [
                    '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
                    '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
                    '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
                ];
                ?>
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="card-title"></div>
                    <ul class="nav nav-tabs nav-justified nav-style-1 d-sm-flex d-block" role="tablist">
                        <div class="btn-group">
                            <!-- Dropdown Tahun -->
                            <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" id="selectedYearKehadiranPerHari">
                                <?= $defaultYear ?>
                            </button>
                            <ul class="dropdown-menu">
                                <?php foreach ($arr_year as $year): ?>
                                    <li><a class="dropdown-item year-option" href="javascript:void(0);" data-target="hari" data-year="<?= $year ?>"><?= $year ?></a></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                
                        <div class="btn-group ms-2">
                            <!-- Dropdown Bulan -->
                            <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" id="selectedMonthKehadiranPerHari">
                                <?= $months[$defaultMonth] ?>
                            </button>
                            
                            <ul class="dropdown-menu">
                                <?php foreach ($months as $key => $month): ?>
                                    <li><a class="dropdown-item month-option" href="javascript:void(0);" data-target="hari" data-month="<?= $key ?>"><?= $month ?></a></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </ul>
                </div>
                
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane show active text-muted" id="kehadiran-percentage-per-hari" role="tabpanel">
                            <div id="chart-wrapper-barKehadiranPercentagePerHari" class="text-center">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <canvas id="barKehadiranPercentagePerHari" class="chartjs-chart"style="display:none;"></canvas>
                            </div>
                        </div>
                        <div class="tab-pane text-muted" id="kehadiran-value-per-hari" role="tabpanel">
                            <div id="chart-wrapper-barKehadiranValuePerHari" class="text-center">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <canvas id="barKehadiranValuePerHari" class="chartjs-chart"style="display:none;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>       
        <div class="col-xl-6">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">Data Rata-Rata Jam Masuk</div>
                </div>
                <div class="card-body">
                    <div id="chart-wrapper-barAbsensi" class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <canvas id="barAbsensi" class="chartjs-chart"style="display:none;"></canvas>
                    </div>
                </div>
            </div>
        </div>
       
    </div>
</div>
<script>
    const url = "{{ url('/admin_sdm/dashboard_chart') }}";
    const default_range = @json($default_range);

        const absensiHarianByKetValueUrl = `${url}?chart=value_absensi_harian_by_ket&date_range=${default_range}`;
        const absensiHarianByKetPercentageUrl = `${url}?chart=percentage_absensi_harian_by_ket&date_range=${default_range}`;
        const pegawaiByJamMasukUrl = `${url}?chart=bar_pegawai_by_jam_masuk&date_range=${default_range}`;

        var barKehadiranPerBulanValue = null;
        var barKehadiranPerBulanPercentage = null;

        async function loadChartData(chartUrl, callback, elementId) {
            const wrapper = document.getElementById(`chart-wrapper-${elementId}`);
            if (!wrapper) {
                console.error("Wrapper not found:", `chart-wrapper-${elementId}`);
                return;
            }

            const spinner = wrapper.querySelector(".spinner-border");
            const canvas = document.getElementById(elementId);

            try {
                const response = await fetch(chartUrl);
                if (!response.ok) throw new Error("HTTP error " + response.status);

                const data = await response.json();

                // Panggil chart function
                callback(elementId, data);

                // Sukses: sembunyikan spinner, tampilkan canvas
                if (spinner) spinner.style.display = "none";
                if (canvas) canvas.style.display = "block";
            } catch (error) {
                console.error("Error load data chart:", chartUrl, error);

                // Ganti spinner jadi error message
                if (spinner) {
                    spinner.outerHTML = `<div class="text-danger small">Failed to load chart</div>`;
                }
            }
        }


        document.addEventListener("DOMContentLoaded", function() {
            // 1. Load Standalone Charts
            loadChartData(absensiHarianByKetValueUrl, createDoughnutValueAbsensiHarian, 'keteranganAbsensiValue');
            loadChartData(absensiHarianByKetPercentageUrl, createDoughnutPercentageAbsensiHarian,
                'keteranganAbsensiPercentage');
            loadChartData(pegawaiByJamMasukUrl, createBarChart, 'barAbsensi');
    document.addEventListener("DOMContentLoaded", function () {

        if (typeof flatpickr !== 'undefined') {
        flatpickr("#date_range", {
            mode: "range",
            dateFormat: "Y-m-d",
            allowInput: true
        });
    }
        // 1. Load Standalone Charts
        loadChartData(absensiHarianByKetValueUrl, createDoughnutValueAbsensiHarian, 'keteranganAbsensiValue');
        loadChartData(absensiHarianByKetPercentageUrl, createDoughnutPercentageAbsensiHarian, 'keteranganAbsensiPercentage');
        loadChartData(pegawaiByJamMasukUrl, createBarChart, 'barAbsensi');

            // 2. Initialize Filter-based Charts
            let selectedYearBtnBulan = document.getElementById('selectedYearKehadiranPerBulan');
            let selectedYearBtnHari = document.getElementById('selectedYearKehadiranPerHari');

            let yearOptionsBulan = document.querySelectorAll('.year-option[data-target="bulan"]');
            let yearOptionsHari = document.querySelectorAll('.year-option[data-target="hari"]');

            let monthOptions = document.querySelectorAll('.month-option');

            let graphBarValueKehadiranPerBulan = null;
            let graphBarPercentageKehadiranPerBulan = null;
            let graphBarValueKehadiranPerHari = null;
            let graphBarPercentageKehadiranPerHari = null;

            let selectedYearBulan = selectedYearBtnBulan ? selectedYearBtnBulan.textContent.trim() : null;
            let selectedYearHari = selectedYearBtnHari ? selectedYearBtnHari.textContent.trim() : null;
            let selectedMonth = null;

            // Event listener untuk memilih tahun pada grafik per bulan
            yearOptionsBulan.forEach(item => {
                item.addEventListener('click', function() {
                    selectedYearBulan = this.getAttribute('data-year');
                    selectedYearBtnBulan.textContent = selectedYearBulan;
                    fetchChartDataPerBulan(selectedYearBulan, 'value');
                    fetchChartDataPerBulan(selectedYearBulan, 'percentage');
                });
            });

            // Event listener untuk memilih tahun pada grafik per hari
            yearOptionsHari.forEach(item => {
                item.addEventListener('click', function() {
                    selectedYearHari = this.getAttribute('data-year');
                    selectedYearBtnHari.textContent = selectedYearHari;
                    fetchChartDataPerHari(selectedYearHari, selectedMonth, 'value');
                    fetchChartDataPerHari(selectedYearHari, selectedMonth, 'percentage');
                });
            });

            // Event listener untuk memilih bulan pada grafik per hari
            monthOptions.forEach(item => {
                item.addEventListener('click', function() {
                    selectedMonth = this.getAttribute('data-month');
                    fetchChartDataPerHari(selectedYearHari, selectedMonth, 'value');
                    fetchChartDataPerHari(selectedYearHari, selectedMonth, 'percentage');
                });
            });

            function fetchChartDataPerBulan(year, type) {
                if (!year) return;
                let url = type === 'value' ?
                    `/get-kehadiran-data-value?year=${year}` :
                    `/get-kehadiran-data-percentage?year=${year}`;

                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        if (type === 'value') {
                            updateChart(data, 'barKehadiranValue', graphBarValueKehadiranPerBulan,
                                updatedChart => {
                                    graphBarValueKehadiranPerBulan = updatedChart;
                                });
                        } else {
                            updateChart(data, 'barKehadiranPercentage', graphBarPercentageKehadiranPerBulan,
                                updatedChart => {
                                    graphBarPercentageKehadiranPerBulan = updatedChart;
                                }, true);
                        }
                    })
                    .catch(error => console.error('Error fetching data:', error));
            }

            function fetchChartDataPerHari(year, month, type) {
                if (!year) return;
                let url = type === 'value' ?
                    `/get-kehadiran-data-value-per-hari?year=${year}&month=${month || ''}` :
                    `/get-kehadiran-data-percentage-per-hari?year=${year}&month=${month || ''}`;

                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        if (type === 'value') {
                            updateChart(data, 'barKehadiranValuePerHari', graphBarValueKehadiranPerHari,
                                updatedChart => {
                                    graphBarValueKehadiranPerHari = updatedChart;
                                });
                        } else {
                            updateChart(data, 'barKehadiranPercentagePerHari',
                                graphBarPercentageKehadiranPerHari, updatedChart => {
                                    graphBarPercentageKehadiranPerHari = updatedChart;
                                }, true);
                        }
                    })
                    .catch(error => console.error('Error fetching data:', error));
            }

            function updateChart(data, canvasId, chartInstance, setChartInstance, isPercentage = false) {
                // Destroy existing chart on this canvas dynamic ID
                const existingChart = Chart.getChart(canvasId);
                if (existingChart) {
                    existingChart.destroy();
                }

                // Destroy the specific tracked instance
                if (chartInstance instanceof Chart) {
                    chartInstance.destroy();
                }

                const canvas = document.getElementById(canvasId);
                if (!canvas) return;
                const ctx = canvas.getContext('2d');
                const wrapper = document.getElementById(`chart-wrapper-${canvasId}`);
                if (wrapper) {
                    const spinner = wrapper.querySelector('.spinner-border');
                    if (spinner) spinner.style.display = 'none';
                }
                canvas.style.display = 'block';

                var labels = data.map(item => item.day_text || item.month_text);
                var keteranganTypes = [...new Set(data.flatMap(item => item.data.map(k => k.nama)))];

                var datasets = keteranganTypes.map(keterangan => ({
                    label: keterangan,
                    data: data.map(item => {
                        let found = item.data.find(k => k.nama === keterangan);
                        return found ? found.count : 0;
                    }),
                    backgroundColor: data.find(item => item.data.find(k => k.nama === keterangan))?.data
                        .find(k => k.nama === keterangan)?.color || 'rgba(200, 200, 200, 0.8)'
                }));

                let newChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: datasets
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            tooltip: isPercentage ? {
                                callbacks: {
                                    label: function(tooltipItem) {
                                        let value = tooltipItem.raw;
                                        return `${tooltipItem.dataset.label}: ${value}%`;
                                    }
                                }
                            } : {}
                        },
                        scales: {
                            x: {
                                stacked: true
                            },
                            y: {
                                stacked: true
                            }
                        }
                    },
                });

                setChartInstance(newChart);
            }

            // Muat data awal berdasarkan tahun yang sudah dipilih di button
            fetchChartDataPerBulan(selectedYearBulan, 'value');
            fetchChartDataPerBulan(selectedYearBulan, 'percentage');
            fetchChartDataPerHari(selectedYearHari, selectedMonth, 'value');
            fetchChartDataPerHari(selectedYearHari, selectedMonth, 'percentage');
        });

        // --- CHART CREATION FUNCTIONS ---

        function createDoughnutValueAbsensiHarian(canvasId, data) {
            let existingChart = Chart.getChart(canvasId);
            if (existingChart) existingChart.destroy();

            var ctx = document.getElementById(canvasId).getContext('2d');
            var labels = data.map(item => item.nama);
            var counts = data.map(item => item.count);
            var colors = data.map(item => item.color);

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: counts,
                        backgroundColor: colors,
                    }]
                },
                options: {
                    responsive: true,
                    cutout: '60%',
                    plugins: {
                        legend: {
                            position: 'left',
                            labels: {
                                usePointStyle: true,
                                boxWidth: 10,
                                padding: 15,
                                font: {
                                    size: 12
                                }
                            }
                        },
                        datalabels: {
                            color: '#fff',
                            backgroundColor: 'rgba(0, 0, 0, 0.5)',
                            borderRadius: 5,
                            padding: 6,
                            font: {
                                weight: 'bold',
                                size: 7
                            },
                            formatter: (value, ctx) => {
                                let label = ctx.chart.data.labels[ctx.dataIndex];
                                return `${label}\n${value}`;
                            }
                        }
                    }
                },
                plugins: [ChartDataLabels]
            });
        }

        function createDoughnutPercentageAbsensiHarian(canvasId, data) {
            let existingChart = Chart.getChart(canvasId);
            if (existingChart) existingChart.destroy();

            var ctx = document.getElementById(canvasId).getContext('2d');
            var labels = data.map(item => item.nama);
            var counts = data.map(item => item.count);
            var colors = data.map(item => item.color);

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: counts,
                        backgroundColor: colors,
                    }]
                },
                options: {
                    responsive: true,
                    cutout: '60%',
                    plugins: {
                        legend: {
                            position: 'left',
                            labels: {
                                usePointStyle: true,
                                boxWidth: 10,
                                padding: 15,
                                font: {
                                    size: 12
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    let value = tooltipItem.raw;
                                    return `${tooltipItem.label}: ${value}%`;
                                }
                            }
                        },
                        datalabels: {
                            color: '#fff',
                            backgroundColor: 'rgba(0, 0, 0, 0.5)',
                            borderRadius: 5,
                            padding: 6,
                            font: {
                                weight: 'bold',
                                size: 7
                            },
                            formatter: (value, ctx) => {
                                let label = ctx.chart.data.labels[ctx.dataIndex];
                                return `${label}\n${value}%`;
                            }
                        }
                    }
                },
                plugins: [ChartDataLabels]
            });
        }

        function createBarChart(canvasId, data) {
            let existingChart = Chart.getChart(canvasId);
            if (existingChart) existingChart.destroy();

            var ctx = document.getElementById(canvasId).getContext('2d');
            var labels = data.map(item => item.nama);
            var times = data.map(item => item.count * 3600);
            var colors = data.map(item => item.color);

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Jam Masuk',
                        data: times,
                        backgroundColor: colors,
                        borderColor: colors,
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            type: 'linear',
                            min: 6 * 3600,
                            max: 10 * 3600,
                            ticks: {
                                stepSize: 900,
                                callback: function(value) {
                                    let hours = Math.floor(value / 3600);
                                    let minutes = Math.round((value % 3600) / 60);
                                    return `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}`;
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let value = context.raw;
                                    let hours = Math.floor(value / 3600);
                                    let minutes = Math.round((value % 3600) / 60);
                                    return `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}`;
                                }
                            }
                        },
                        datalabels: {
                            anchor: 'end',
                            align: 'top',
                            formatter: function(value) {
                                let hours = Math.floor(value / 3600);
                                let minutes = Math.round((value % 3600) / 60);
                                return `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}`;
                            },
                            color: '#fff',
                            backgroundColor: 'rgba(0, 0, 0, 0.5)',
                            borderRadius: 5,
                            padding: 5,
                            font: {
                                weight: 'bold',
                                size: 10
                            }
                        }
                    }
                },
                plugins: [ChartDataLabels]
            });
        }

        function createBarValueKehadiran(canvasId, data) {
            let existingChart = Chart.getChart(canvasId);
            if (existingChart) existingChart.destroy();

            const ctx = document.getElementById(canvasId).getContext('2d');
            var labels = data.map(item => item.month_text);
            var keteranganTypes = [...new Set(data.flatMap(item => item.data.map(k => k.nama)))];

            var datasets = keteranganTypes.map(keterangan => {
                return {
                    label: keterangan,
                    data: data.map(item => {
                        let found = item.data.find(k => k.nama === keterangan);
                        return found ? found.count : 0;
                    }),
                    backgroundColor: data.find(item => item.data.find(k => k.nama === keterangan))?.data.find(k => k
                        .nama === keterangan)?.color || 'rgba(200, 200, 200, 0.8)'
                };
            });

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top'
                        }
                    },
                    scales: {
                        x: {
                            stacked: true
                        },
                        y: {
                            stacked: true
                        }
                    }
                }
            });
        }

        function createBarPercentageKehadiran(canvasId, data) {
            let existingChart = Chart.getChart(canvasId);
            if (existingChart) existingChart.destroy();

            const ctx = document.getElementById(canvasId).getContext('2d');
            var labels = data.map(item => item.month_text);
            var keteranganTypes = [...new Set(data.flatMap(item => item.data.map(k => k.nama)))];

            var datasets = keteranganTypes.map(keterangan => {
                return {
                    label: keterangan,
                    data: data.map(item => {
                        let found = item.data.find(k => k.nama === keterangan);
                        return found ? found.count : 0;
                    }),
                    backgroundColor: data.find(item => item.data.find(k => k.nama === keterangan))?.data.find(k => k
                        .nama === keterangan)?.color || 'rgba(200, 200, 200, 0.8)'
                };
            });

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top'
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    let value = tooltipItem.raw;
                                    return `${tooltipItem.dataset.label}: ${value}%`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            stacked: true
                        },
                        y: {
                            stacked: true
                        }
                    }
                }
            });
        }

        function createBarValueKehadiranPerDay(canvasId, data) {
            let existingChart = Chart.getChart(canvasId);
            if (existingChart) existingChart.destroy();

            const ctx = document.getElementById(canvasId).getContext('2d');
            ctx.canvas.width = 200;
            ctx.canvas.height = 200;
            var labels = data.map(item => item.day_text);
            var keteranganTypes = [...new Set(data.flatMap(item => item.data.map(k => k.nama)))];

            var datasets = keteranganTypes.map(keterangan => {
                return {
                    label: keterangan,
                    data: data.map(item => {
                        let found = item.data.find(k => k.nama === keterangan);
                        return found ? found.count : 0;
                    }),
                    backgroundColor: data.find(item => item.data.find(k => k.nama === keterangan))?.data.find(k => k
                        .nama === keterangan)?.color || 'rgba(200, 200, 200, 0.8)'
                };
            });

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top'
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    let value = tooltipItem.raw;
                                    return `${tooltipItem.dataset.label}: ${value}`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            stacked: true
                        },
                        y: {
                            stacked: true
                        }
                    }
                }
            });
        }

        function createBarPercentageKehadiranPerDay(canvasId, data) {
            let existingChart = Chart.getChart(canvasId);
            if (existingChart) existingChart.destroy();

            const ctx = document.getElementById(canvasId).getContext('2d');
            var labels = data.map(item => item.day_text);
            var keteranganTypes = [...new Set(data.flatMap(item => item.data.map(k => k.nama)))];

            var datasets = keteranganTypes.map(keterangan => {
                return {
                    label: keterangan,
                    data: data.map(item => {
                        let found = item.data.find(k => k.nama === keterangan);
                        return found ? found.count : 0;
                    }),
                    backgroundColor: data.find(item => item.data.find(k => k.nama === keterangan))?.data.find(k => k
                        .nama === keterangan)?.color || 'rgba(200, 200, 200, 0.8)'
                };
            });

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top'
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    let value = tooltipItem.raw;
                                    return `${tooltipItem.dataset.label}: ${value}%`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            stacked: true
                        },
                        y: {
                            stacked: true
                        }
                    }
                }
            });
        }

        // --- OTHER LISTENERS ---

        document.getElementById('applyFilter').addEventListener('click', function() {
            const dateRange = document.getElementById('date_range').value;
            const baseUrl = `/admin_sdm/dashboard/`;

            let queryParams = [];
            if (dateRange) {
                queryParams.push(`date_range=${dateRange}`);
            }

            const queryString = queryParams.length > 0 ? `?${queryParams.join('&')}` : '';
            const finalUrl = baseUrl + queryString;

            window.location.href = finalUrl;
        });

        if (typeof flatpickr !== 'undefined') {
            flatpickr("#date_range", {
                mode: "range",
                dateFormat: "Y-m-d",
                allowInput: true
            });
        }
    </script>
    
</script>

@endsection
