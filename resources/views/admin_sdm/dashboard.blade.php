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
                            <input type="text" class="form-control" id="date_range" name="date_range" value="{{ old('date_range', $default_range) }} placeholder="Pilih Range Tanggal">
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
                        <div class="tab-pane show active text-muted" id="kehadiran-percentage-per-hari"
                            role="tabpanel">
                            <canvas id="barKehadiranPercentagePerHari" class="chartjs-chart"></canvas>
                        </div>
                        <div class="tab-pane text-muted" id="kehadiran-value-per-hari" role="tabpanel">
                            <canvas id="barKehadiranValuePerHari" class="chartjs-chart"></canvas>
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
                    <canvas id="barAbsensi" class="chartjs-chart"></canvas>
                </div>
            </div>
        </div>
        {{-- <div class="col-md-12 col-lg-12 col-xl-7">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title mb-0">Order status</h4>
                        <a href="javascript:void(0);"
                            class="btn btn-icon btn-sm btn-light bg-transparent rounded-pill"
                            data-bs-toggle="dropdown"><i class="fe fe-more-horizontal"></i></a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="javascript:void(0);">Action</a>
                            <a class="dropdown-item" href="javascript:void(0);">Another
                                Action</a>
                            <a class="dropdown-item" href="javascript:void(0);">Something Else
                                Here</a>
                        </div>
                    </div>
                    <p class="fs-12 text-muted mb-0">Order Status and Tracking. Track your order from ship date to
                        arrival. To begin, enter your order number.</p>
                </div>
                <div class="card-body">
                    <div class="total-revenue">
                        <div>
                            <h4>120,750</h4>
                            <label><span class="bg-primary"></span>success</label>
                        </div>
                        <div>
                            <h4>56,108</h4>
                            <label><span class="bg-danger"></span>Pending</label>
                        </div>
                        <div>
                            <h4>32,895</h4>
                            <label><span class="bg-warning"></span>Failed</label>
                        </div>
                    </div>
                    <div id="Sales-bar" class="sales-bar mt-4"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-xl-5">
            <div class="card card-dashboard-map-one">
                <h4 class="card-title">Sales Revenue by Customers in USA</h4>
                <p class="fs-12 text-muted">Sales Performance of all states in the United States.</p>
                <div id="us-map1" class="pt-1"></div>
            </div>
        </div> --}}
    </div>
    <!-- row closed -->

    <!-- row opened -->
    {{-- <div class="row">
        <div class="col-xl-4 col-md-12 col-lg-12">
            <div class="card overflow-hidden">
                <div class="card-header pb-1">
                    <h3 class="card-title mb-2">Recent Customers</h3>
                    <p class="fs-12 mb-0 text-muted">A customer is an individual or business that purchases the
                        goods service has evolved to include real-time</p>
                </div>
                <div class="card-body p-0 customers mt-1">
                    <div class="list-group list-lg-group list-group-flush">
                        <div class="list-group-item list-group-item-action">
                            <div class="d-flex">
                                <img class="avatar avatar-md rounded-circle my-auto me-3"
                                    src="../assets/images/faces/3.jpg" alt="Image description">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center">
                                        <div class="mt-0">
                                            <h5 class="mb-1 fs-15">Samantha Melon</h5>
                                            <p class="mb-0 fs-13 text-muted">User ID: #1234 <span
                                                    class="text-success ms-2 d-inline-block">Paid</span></p>
                                        </div>
                                        <div class="ms-auto w-45 fs-16 mt-2">
                                            <div id="spark1" class="w-100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="list-group-item list-group-item-action br-t-1">
                            <div class="d-flex">
                                <img class="avatar avatar-md rounded-circle my-auto me-3"
                                    src="../assets/images/faces/11.jpg" alt="Image description">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center">
                                        <div class="mt-1">
                                            <h5 class="mb-1 fs-15">Jimmy Changa</h5>
                                            <p class="mb-0 fs-13 text-muted">User ID: #1234 <span
                                                    class="text-danger ms-2 d-inline-block">Pending</span></p>
                                        </div>
                                        <div class="ms-auto w-45 fs-16 mt-2">
                                            <div id="spark2" class="w-100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="list-group-item list-group-item-action br-t-1">
                            <div class="d-flex">
                                <img class="avatar avatar-md rounded-circle my-auto me-3"
                                    src="../assets/images/faces/17.jpg" alt="Image description">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center">
                                        <div class="mt-1">
                                            <h5 class="mb-1 fs-15">Gabe Lackmen</h5>
                                            <p class="mb-0 fs-13 text-muted">User ID: #1234 <span
                                                    class="text-danger ms-2 d-inline-block">Pending</span></p>
                                        </div>
                                        <div class="ms-auto w-45 fs-16 mt-2">
                                            <div id="spark3" class="w-100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="list-group-item list-group-item-action br-t-1">
                            <div class="d-flex">
                                <img class="avatar avatar-md rounded-circle my-auto me-3"
                                    src="../assets/images/faces/15.jpg" alt="Image description">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center">
                                        <div class="mt-1">
                                            <h5 class="mb-1 fs-15">Manuel Labor</h5>
                                            <p class="mb-0 fs-13 text-muted">User ID: #1234 <span
                                                    class="text-success ms-2 d-inline-block">Paid</span></p>
                                        </div>
                                        <div class="ms-auto w-45 fs-16 mt-2">
                                            <div id="spark4" class="w-100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="list-group-item list-group-item-action br-t-1 br-be-7 br-bs-7">
                            <div class="d-flex">
                                <img class="avatar avatar-md rounded-circle my-auto me-3"
                                    src="../assets/images/faces/6.jpg" alt="Image description">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center">
                                        <div class="mt-1">
                                            <h5 class="mb-1 fs-15">Sharon Needles</h5>
                                            <p class="b-0 fs-13 text-muted mb-0">User ID: #1234 <span
                                                    class="text-success ms-2 d-inline-block">Paid</span></p>
                                        </div>
                                        <div class="ms-auto w-45 fs-16 mt-2">
                                            <div id="spark5" class="w-100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-12 col-lg-6">
            <div class="card">
                <div class="card-header pb-1">
                    <h3 class="card-title mb-2">Sales Activity</h3>
                    <p class="fs-12 mb-0 text-muted">Sales activities are the tactics that salespeople use to
                        achieve their goals and objective</p>
                </div>
                <div class="product-timeline card-body pt-2 mt-1">
                    <ul class="timeline-1 mb-0">
                        <li class="mt-0"> <i
                                class="fe fe-pie-chart bg-primary-gradient text-fixed-white product-icon"></i> <span
                                class="fw-medium mb-4 fs-14">Total Products</span> <a href="javascript:void(0);"
                                class="float-end fs-11 text-muted">3 days ago</a>
                            <p class="mb-0 text-muted fs-12">1.3k New Products</p>
                        </li>
                        <li class="mt-0"> <i
                                class="fe fe-shopping-cart bg-danger-gradient text-fixed-white product-icon"></i>
                            <span class="fw-medium mb-4 fs-14">Total Sales</span> <a href="javascript:void(0);"
                                class="float-end fs-11 text-muted">35 mins ago</a>
                            <p class="mb-0 text-muted fs-12">1k New Sales</p>
                        </li>
                        <li class="mt-0"> <i
                                class="fe fe-bar-chart bg-success-gradient text-fixed-white product-icon"></i> <span
                                class="fw-medium mb-4 fs-14">Total Revenue</span> <a href="javascript:void(0);"
                                class="float-end fs-11 text-muted">50 mins ago</a>
                            <p class="mb-0 text-muted fs-12">23.5K New Revenue</p>
                        </li>
                        <li class="mt-0"> <i
                                class="fe fe-box bg-warning-gradient text-fixed-white product-icon"></i> <span
                                class="fw-medium mb-4 fs-14">Total Profit</span> <a href="javascript:void(0);"
                                class="float-end fs-11 text-muted">1 hour ago</a>
                            <p class="mb-0 text-muted fs-12">3k New profit</p>
                        </li>
                        <li class="mt-0"> <i class="fe fe-eye bg-purple-gradient text-fixed-white product-icon"></i>
                            <span class="fw-medium mb-4 fs-14">Customer Visits</span> <a href="javascript:void(0);"
                                class="float-end fs-11 text-muted">1 day ago</a>
                            <p class="mb-0 text-muted fs-12">15% increased</p>
                        </li>
                        <li class="mt-0 mb-0"> <i
                                class="fe fe-edit bg-primary-gradient text-fixed-white product-icon"></i> <span
                                class="fw-medium mb-4 fs-14">Customer Reviews</span> <a href="javascript:void(0);"
                                class="float-end fs-11 text-muted">1 day ago</a>
                            <p class="mb-0 text-muted fs-12">1.5k reviews</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-12 col-lg-6">
            <div class="card">
                <div class="card-header pb-0">
                    <h3 class="card-title mb-2">Recent Orders</h3>
                    <p class="fs-12 mb-0 text-muted">An order is an investor's instructions to a broker or brokerage
                        firm to purchase or sell</p>
                </div>
                <div class="card-body sales-info pb-0 pt-0">
                    <div id="chart" class="ht-150"></div>
                    <div class="row sales-infomation pb-0 mb-0 mx-auto w-100">
                        <div class="col-md-6 col">
                            <p class="mb-0 d-flex"><span class="legend bg-primary brround"></span>Delivered</p>
                            <h3 class="mb-1">5238</h3>
                            <div class="d-flex">
                                <p class="text-muted ">Last 6 months</p>
                            </div>
                        </div>
                        <div class="col-md-6 col">
                            <p class="mb-0 d-flex"><span class="legend bg-info brround"></span>Cancelled</p>
                            <h3 class="mb-1">3467</h3>
                            <div class="d-flex">
                                <p class="text-muted">Last 6 months</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card ">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center pb-2">
                                <p class="mb-0">Total Sales</p>
                            </div>
                            <h4 class="fw-bold mb-2">$7,590</h4>
                            <div class="progress progress-style progress-sm">
                                <div class="progress-bar bg-primary-gradient" style="width: 80%" role="progressbar"
                                    aria-valuenow="78" aria-valuemin="0" aria-valuemax="78"></div>
                            </div>
                        </div>
                        <div class="col-md-6 mt-4 mt-md-0">
                            <div class="d-flex align-items-center pb-2">
                                <p class="mb-0">Active Users</p>
                            </div>
                            <h4 class="fw-bold mb-2">$5,460</h4>
                            <div class="progress progress-style progress-sm">
                                <div class="progress-bar bg-danger-gradient" style="width: 45%" role="progressbar"
                                    aria-valuenow="45" aria-valuemin="0" aria-valuemax="45"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
    <!-- row close -->

    <!-- row opened -->
    {{-- <div class="row">
        <div class="col-md-12 col-lg-4 col-xl-4">
            <div class="card top-countries-card">
                <div class="card-header p-0">
                    <h6 class="card-title fs-13 mb-2">Your Top Countries</h6><span
                        class="d-block mg-b-10 text-muted fs-12 mb-2">Sales performance revenue based by
                        country</span>
                </div>
                <div class="list-group border">
                    <div class="list-group-item border-top-0" id="br-t-0">
                        <p>United States</p><span>$1,671.10</span>
                    </div>
                    <div class="list-group-item">
                        <p>Netherlands</p><span>$1,064.75</span>
                    </div>
                    <div class="list-group-item">
                        <p>United Kingdom</p><span>$1,055.98</span>
                    </div>
                    <div class="list-group-item">
                        <p>Canada</p><span>$1,045.49</span>
                    </div>
                    <div class="list-group-item">
                        <p>India</p><span>$1,930.12</span>
                    </div>
                    <div class="list-group-item border-bottom-0 mb-0">
                        <p>Australia</p><span>$1,042.00</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-lg-8 col-xl-8">
            <div class="card card-table">
                <div class=" card-header p-0 d-flex justify-content-between">
                    <h4 class="card-title mb-1">Your Most Recent Earnings</h4>
                    <a href="javascript:void(0);" class="btn btn-icon btn-sm btn-light bg-transparent rounded-pill"
                        data-bs-toggle="dropdown"><i class="fe fe-more-horizontal"></i></a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="javascript:void(0);">Action</a>
                        <a class="dropdown-item" href="javascript:void(0);">Another
                            Action</a>
                        <a class="dropdown-item" href="javascript:void(0);">Something Else
                            Here</a>
                    </div>
                </div>
                <span class="fs-12 text-muted mb-3 ">This is your most recent earnings for today's date.</span>
                <div class="table-responsive country-table">
                    <table class="table table-striped table-bordered mb-0 text-nowrap">
                        <thead>
                            <tr>
                                <th class="wd-lg-25p">Date</th>
                                <th class="wd-lg-25p">Sales Count</th>
                                <th class="wd-lg-25p">Earnings</th>
                                <th class="wd-lg-25p">Tax Witheld</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>05 Dec 2019</td>
                                <td class="fw-medium">34</td>
                                <td class="fw-medium">$658.20</td>
                                <td class="text-danger fw-medium">-$45.10</td>
                            </tr>
                            <tr>
                                <td>06 Dec 2019</td>
                                <td class="fw-medium">26</td>
                                <td class="fw-medium">$453.25</td>
                                <td class="text-danger fw-medium">-$15.02</td>
                            </tr>
                            <tr>
                                <td>07 Dec 2019</td>
                                <td class="fw-medium">34</td>
                                <td class="fw-medium">$653.12</td>
                                <td class="text-danger fw-medium">-$13.45</td>
                            </tr>
                            <tr>
                                <td>08 Dec 2019</td>
                                <td class="fw-medium">45</td>
                                <td class="fw-medium">$546.47</td>
                                <td class="text-danger fw-medium">-$24.22</td>
                            </tr>
                            <tr>
                                <td>09 Dec 2019</td>
                                <td class="fw-medium">31</td>
                                <td class="fw-medium">$425.72</td>
                                <td class="text-danger fw-medium">-$25.01</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div> --}}
    <!-- /row -->
</div>
<script>
    var absensiHarianByKetValue = @json($value_absensi_harian_by_ket);
    var absensiHarianByKetPercentage = @json($percentage_absensi_harian_by_ket);
    var pegawaiByJamMasuk = @json($bar_pegawai_by_jam_masuk);
    var kehadiranPerBulanValue = @json($bar_value_kehadiran_per_bulan);
    var kehadiranPerBulanPercentage = @json($bar_percentage_kehadiran_per_bulan);
    var kehadiranPerHariValue = @json($bar_value_kehadiran_per_hari);
    var kehadiranPerHariPercentage = @json($bar_percentage_kehadiran_per_hari);

    var barKehadiranPerBulanValue = null;
    var barKehadiranPerBulanPercentage = null;

    document.addEventListener("DOMContentLoaded", function() {
        createDoughnutValueAbsensiHarian('keteranganAbsensiValue', absensiHarianByKetValue);
        createDoughnutPercentageAbsensiHarian('keteranganAbsensiPercentage', absensiHarianByKetPercentage);
        createBarChart('barAbsensi', pegawaiByJamMasuk);
        createBarValueKehadiran('barKehadiranValue', kehadiranPerBulanValue);
        createBarPercentageKehadiran('barKehadiranPercentage', kehadiranPerBulanPercentage);
        createBarValueKehadiranPerDay('barKehadiranValuePerHari', kehadiranPerHariValue);
        createBarPercentageKehadiranPerDay('barKehadiranPercentagePerHari', kehadiranPerHariPercentage);
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

    function createBarChart(canvasId, data) { 
        var ctx = document.getElementById(canvasId).getContext('2d');

        var labels = data.map(item => item.nama);
        var times = data.map(item => item.count * 3600); // Konversi jam desimal ke detik
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
                    legend: { display: false },
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

    function createBarValueKehadiranPerDay(canvasId, data) { 
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
            },
        });
    }

    function createBarPercentageKehadiranPerDay(canvasId, data) { 
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
        let selectedYearBtnHari = document.getElementById('selectedYearKehadiranPerHari');

        let yearOptionsBulan = document.querySelectorAll('.year-option[data-target="bulan"]');
        let yearOptionsHari = document.querySelectorAll('.year-option[data-target="hari"]');

        let monthOptions = document.querySelectorAll('.month-option');

        let graphBarValueKehadiranPerBulan = null;
        let graphBarPercentageKehadiranPerBulan = null;
        let graphBarValueKehadiranPerHari = null;
        let graphBarPercentageKehadiranPerHari = null;

        let selectedYearBulan = selectedYearBtnBulan.textContent.trim();
        let selectedYearHari = selectedYearBtnHari.textContent.trim();
        let selectedMonth = null;

        // Event listener untuk memilih tahun pada grafik per bulan
        yearOptionsBulan.forEach(item => {
            item.addEventListener('click', function () {
                selectedYearBulan = this.getAttribute('data-year');
                selectedYearBtnBulan.textContent = selectedYearBulan;
                fetchChartDataPerBulan(selectedYearBulan, 'value');
                fetchChartDataPerBulan(selectedYearBulan, 'percentage');
            });
        });

        // Event listener untuk memilih tahun pada grafik per hari
        yearOptionsHari.forEach(item => {
            item.addEventListener('click', function () {
                selectedYearHari = this.getAttribute('data-year');
                selectedYearBtnHari.textContent = selectedYearHari;
                fetchChartDataPerHari(selectedYearHari, selectedMonth, 'value');
                fetchChartDataPerHari(selectedYearHari, selectedMonth, 'percentage');
            });
        });

            // Event listener untuk memilih bulan pada grafik per hari
            monthOptions.forEach(item => {
                item.addEventListener('click', function () {
                    selectedMonth = this.getAttribute('data-month');
                    fetchChartDataPerHari(selectedYearHari, selectedMonth, 'value');
                    fetchChartDataPerHari(selectedYearHari, selectedMonth, 'percentage');
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

            function fetchChartDataPerHari(year, month, type) {
                let url = type === 'value' 
                    ? `/get-kehadiran-data-value-per-hari?year=${year}&month=${month || ''}`
                    : `/get-kehadiran-data-percentage-per-hari?year=${year}&month=${month || ''}`;
                
                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        if (type === 'value') {
                            updateChart(data, 'barKehadiranValuePerHari', graphBarValueKehadiranPerHari, updatedChart => {
                                graphBarValueKehadiranPerHari = updatedChart;
                            });
                        } else {
                            updateChart(data, 'barKehadiranPercentagePerHari', graphBarPercentageKehadiranPerHari, updatedChart => {
                                graphBarPercentageKehadiranPerHari = updatedChart;
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

                var labels = data.map(item => item.day_text || item.month_text);
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
            fetchChartDataPerHari(selectedYearHari, selectedMonth, 'value');
            fetchChartDataPerHari(selectedYearHari, selectedMonth, 'percentage');
    });

    document.addEventListener("DOMContentLoaded", function () {
        let selectedMonthBtnHari = document.getElementById('selectedMonthKehadiranPerHari');
        let monthOptions = document.querySelectorAll('.month-option');

        let selectedYearHari = document.getElementById('selectedYearKehadiranPerHari').textContent.trim();
        let selectedMonth = selectedMonthBtnHari.textContent.trim();

        // Event listener untuk memilih bulan
        monthOptions.forEach(item => {
            item.addEventListener('click', function () {
                selectedMonth = this.getAttribute('data-month');
                selectedMonthBtnHari.textContent = this.textContent; // Perbarui teks dropdown

                // Panggil fungsi fetch untuk memperbarui grafik
                fetchChartDataPerHari(selectedYearHari, selectedMonth, 'value');
                fetchChartDataPerHari(selectedYearHari, selectedMonth, 'percentage');
            });
        });
    });


    document.getElementById('applyFilter').addEventListener('click', function () {
        const dateRange = document.getElementById('date_range').value;
        const baseUrl = `/admin_sdm/dashboard/`; // Bangun URL dinamis

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