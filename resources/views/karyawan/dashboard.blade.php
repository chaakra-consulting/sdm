@extends('layouts.main')

@section('content')

<div class="container-fluid">
    <!-- row -->
    <div class="row">
        <div class="card-body">
            <form action="" method="GET" class="ms-auto" style="max-width: 400px;">
                <div class="row g-3 align-items-end">
                    <div class="col-md-8">
                        <div class="input-group">
                            <span class="input-group-text"><i class="ri-calendar-line"></i></span>
                            <input type="text" class="form-control" id="date_range" name="date_range" value="{{ old('date_range', $default_range) }}" placeholder="Pilih Range Tanggal">
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
                        <div class="tab-pane show active text-muted" id="keterangan-absensi-percentage"
                            role="tabpanel">
                            <canvas id="keteranganAbsensiPercentage" class="chartjs-chart"></canvas>
                        </div>
                        <div class="tab-pane text-muted" id="keterangan-absensi-value" role="tabpanel">
                            <canvas id="keteranganAbsensiValue" class="chartjs-chart"></canvas>
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
                        <div class="tab-pane show active text-muted" id="kehadiran-percentage"
                            role="tabpanel">
                            <canvas id="barKehadiranPercentage" class="chartjs-chart"></canvas>
                        </div>
                        <div class="tab-pane text-muted" id="kehadiran-value" role="tabpanel">
                            <canvas id="barKehadiranValue" class="chartjs-chart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>    
    </div>
    <!-- row closed -->
</div>
<script>
    var absensiHarianByKetValue = @json($value_absensi_harian_by_ket);
    var absensiHarianByKetPercentage = @json($percentage_absensi_harian_by_ket);
    var kehadiranPerBulanValue = @json($bar_value_kehadiran_per_bulan);
    var kehadiranPerBulanPercentage = @json($bar_percentage_kehadiran_per_bulan);

    var barKehadiranPerBulanValue = null;
    var barKehadiranPerBulanPercentage = null;

    document.addEventListener("DOMContentLoaded", function() {
        createDoughnutValueAbsensiHarian('keteranganAbsensiValue', absensiHarianByKetValue);
        createDoughnutPercentageAbsensiHarian('keteranganAbsensiPercentage', absensiHarianByKetPercentage);
        createBarValueKehadiran('barKehadiranValue', kehadiranPerBulanValue);
        createBarPercentageKehadiran('barKehadiranPercentage', kehadiranPerBulanPercentage);
    });

    // Fungsi untuk membuat Doughnut Chart
    function createDoughnutValueAbsensiHarian(canvasId, data) {
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
            plugins: [ChartDataLabels] // Aktifkan plugin datalabels
        });
    }

    function createDoughnutPercentageAbsensiHarian(canvasId, data) {
        var ctx = document.getElementById(canvasId).getContext('2d');
        var labels = data.map(item => item.nama);
        var counts = data.map(item => item.count);
        var colors = data.map(item => item.color);

        // Hitung total untuk persentase
        var total = counts.reduce((sum, value) => sum + value, 0);

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


    function createBarValueKehadiran(canvasId, data) { 
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
                backgroundColor: data.find(item => item.data.find(k => k.nama === keterangan))?.data.find(k => k.nama === keterangan)?.color || 'rgba(200, 200, 200, 0.8)'
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
                        position: 'top',
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
            },
        });
    }

    function createBarPercentageKehadiran(canvasId, data) { 
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
                backgroundColor: data.find(item => item.data.find(k => k.nama === keterangan))?.data.find(k => k.nama === keterangan)?.color || 'rgba(200, 200, 200, 0.8)'
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
                        position: 'top',
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
            },
        });
    }

    document.addEventListener("DOMContentLoaded", function () {
        let selectedYearBtnBulan = document.getElementById('selectedYearKehadiranPerBulan');
        let yearOptionsBulan = document.querySelectorAll('.year-option[data-target="bulan"]');

        let graphBarValueKehadiranPerBulan = null;
        let graphBarPercentageKehadiranPerBulan = null;

        let selectedYearBulan = selectedYearBtnBulan.textContent.trim();

        // Event listener untuk memilih tahun pada grafik per bulan
        yearOptionsBulan.forEach(item => {
            item.addEventListener('click', function () {
                selectedYearBulan = this.getAttribute('data-year');
                selectedYearBtnBulan.textContent = selectedYearBulan;
                fetchChartDataPerBulan(selectedYearBulan, 'value');
                fetchChartDataPerBulan(selectedYearBulan, 'percentage');
            });
        });

        function fetchChartDataPerBulan(year, type) {
            let url = type === 'value' 
                ? `/get-kehadiran-data-value?year=${year}` 
                : `/get-kehadiran-data-percentage?year=${year}`;
            
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (type === 'value') {
                        updateChart(data, 'barKehadiranValue', graphBarValueKehadiranPerBulan, updatedChart => {
                            graphBarValueKehadiranPerBulan = updatedChart;
                        });
                    } else {
                        updateChart(data, 'barKehadiranPercentage', graphBarPercentageKehadiranPerBulan, updatedChart => {
                            graphBarPercentageKehadiranPerBulan = updatedChart;
                        }, true);
                    }
                })
                .catch(error => console.error('Error fetching data:', error));
        }

        function updateChart(data, canvasId, chartInstance, setChartInstance, isPercentage = false) {
            let canvas = document.getElementById(canvasId);

            if (chartInstance instanceof Chart) {
                chartInstance.destroy();
                chartInstance = null;
            }

            const canvasParent = canvas.parentNode;
            canvas.remove();
            const newCanvas = document.createElement('canvas');
            newCanvas.id = canvasId;
            canvasParent.appendChild(newCanvas);

            canvas = document.getElementById(canvasId);
            const ctx = canvas.getContext('2d');

            var labels = data.map(item => item.month_text);
            var keteranganTypes = [...new Set(data.flatMap(item => item.data.map(k => k.nama)))];

            var datasets = keteranganTypes.map(keterangan => ({
                label: keterangan,
                data: data.map(item => {
                    let found = item.data.find(k => k.nama === keterangan);
                    return found ? found.count : 0;
                }),
                backgroundColor: data.find(item => item.data.find(k => k.nama === keterangan))?.data.find(k => k.nama === keterangan)?.color || 'rgba(200, 200, 200, 0.8)'
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
                                label: function (tooltipItem) {
                                    let value = tooltipItem.raw;
                                    return `${tooltipItem.dataset.label}: ${value}%`;
                                }
                            }
                        } : {}
                    },
                    scales: {
                        x: { stacked: true },
                        y: { stacked: true }
                    }
                },
            });

            setChartInstance(newChart);
        }

        // Muat data awal berdasarkan tahun yang sudah dipilih di button
        fetchChartDataPerBulan(selectedYearBulan, 'value');
        fetchChartDataPerBulan(selectedYearBulan, 'percentage');
    });

    document.getElementById('applyFilter').addEventListener('click', function () {
        const dateRange = document.getElementById('date_range').value;
        const baseUrl = `/karyawan/dashboard/`; // Bangun URL dinamis

        let queryParams = [];
        if (dateRange) {
            queryParams.push(`date_range=${dateRange}`);
        }

        const queryString = queryParams.length > 0 ? `?${queryParams.join('&')}` : '';
        const finalUrl = baseUrl + queryString;

        // Redirect to the filtered URL
        window.location.href = finalUrl;
    });

    flatpickr("#date_range", {
        mode: "range",
        dateFormat: "Y-m-d",
        allowInput: true
    });
</script>

@endsection