@extends('layouts.main')

@section('content')

<div class="container-fluid">
    <!-- row -->
    <div class="row">
        <div class="card-body">
            <form action="" method="GET" class="ms-auto" style="max-width: 500px;">
                <div class="row g-3 align-items-end">
                    <div class="col-md-5">
                        <select class="form-select" id="month" name="month">
                            @foreach ($months as $key => $name)
                                <option value="{{ $key }}" {{ $key == $month ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select" name="year">
                            @foreach ($years as $y)
                                <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>
                                    {{ $y }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" id="applyFilter" class="btn btn-primary w-100">Filter</button>
                    </div>
                </div>
            </form>   
        </div> 
        <div class="col-lg-6">
            <div class="card bg-teal-gradient text-fixed-white">
                <div class="card-body text-fixed-white">
                    <div class="row">
                            <div class="mt-0 text-center">
                                <span class="text-fixed-white">{{ $widget_gaji[0]->nama }}</span>
                                <h3 class="text-fixed-white mt-3">Rp. {{ number_format($widget_gaji[0]->count ?? 0, 0, ',', '.') }}</h3>
                            </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card bg-danger-gradient text-fixed-white">
                <div class="card-body text-fixed-white">
                    <div class="row">
                            <div class="mt-0 text-center">
                                <span class="text-fixed-white">{{ $widget_gaji[1]->nama }}</span>
                                <h3 class="text-fixed-white mt-3">Rp. {{ number_format($widget_gaji[1]->count ?? 0, 0, ',', '.') }}</h3>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- row closed -->
</div>
<script>
    document.getElementById('applyFilter').addEventListener('click', function () {
        const year = document.getElementById('year').value;
        const month = document.getElementById('month').value;
        const baseUrl = `/admin_sdm/dashboard_gaji`; // Bangun URL dinamis

        let queryParams = [];
        if (year) {
            queryParams.push(`year=${year}`);
        }
        if (month) {
            queryParams.push(`month=${month}`);
        }

        const queryString = queryParams.length > 0 ? `?${queryParams.join('&')}` : '';
        const finalUrl = baseUrl + queryString;

        // Redirect to the filtered URL
        window.location.href = finalUrl;
    });
</script>

@endsection