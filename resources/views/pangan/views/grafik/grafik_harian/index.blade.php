@extends('index')
@section('title', 'Grafik Harian Pangan | Pangan')
@section('content')
<div class="page-header d-sm-flex d-block">
    <ol class="breadcrumb mb-sm-0 mb-3">
        <li class="breadcrumb-item1"><a href="{{ url('/pangan/dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item1 active">Grafik Harian Pangan</li>
    </ol>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Grafik Harian Pangan</h4>
                <form method="GET" action="{{ route('grafik.harian.index') }}" class="d-flex align-items-center">
                    <div class="form-group mb-0 me-2">
                        <label for="date" class="form-label sr-only">Pilih Tanggal:</label>
                        <input type="date" id="date" name="date" class="form-control" value="{{ request('date', now()->toDateString()) }}">
                    </div>
                    <button type="submit" class="btn btn-primary">Filter</button>
                </form>
            </div>

            <div class="card-body">
                <div class="chartjs-wrapper-demo">
                    <canvas id="chartLine1" class="h-300"></canvas>
                </div>
            </div>
            <div class="card-footer text-center">
                <p>Grafik Harga Rata-rata pada tanggal: {{ \Carbon\Carbon::parse($selectedDate)->format('d-m-Y') }}</p>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(function() {
        var ctx8 = document.getElementById('chartLine1').getContext('2d');

        // Data dari controller
        var labels = @json($dataGroupedBySubjenis->pluck('name'));
        var data = @json($dataGroupedBySubjenis->pluck('avg_harga'));

        new Chart(ctx8, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Rata-rata Harga',
                    data: data,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                scales: {
                    x: {
                        ticks: {
                            fontSize: 10,
                            color: "black" // Warna teks subjenis pangan
                        },
                        title: {
                            display: false,
                            text: 'Subjenis Pangan',
                        },
                        grid: {
                            display: true,
                            color: 'rgba(180, 183, 197, 0.4)',
                            drawBorder: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {

                            fontSize: 10,
                            color: "black", // Warna teks rata-rata harga
                            stepSize: 1000,
                            min: 0
                        },
                        title: {
                            display: false,
                            text: 'Rata-rata Harga',
                        },
                        grid: {
                            display: true,
                            color: 'rgba(180, 183, 197, 0.4)',
                            drawBorder: false,
                            zeroLineColor: 'rgba(54, 162, 235, 1)',
                            zeroLineWidth: 2
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
