@extends('index')
@section('title', 'Grafik Tahunan Pangan | Pangan')
@section('content')
<div class="page-header d-sm-flex d-block">
    <ol class="breadcrumb mb-sm-0 mb-3">
        <li class="breadcrumb-item1"><a href="{{ url('/pangan/dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item1 active">Grafik Tahunan Pangan</li>
    </ol>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Grafik Tahunan Pangan</h4>
            </div>
            <div class="card-body">
                <div class="chartjs-wrapper-demo">
                    <canvas id="chartLine1" class="h-300"></canvas>
                </div>
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
        var dataGroupedByYear = @json($dataGroupedByYear);

        var labels = [];
        var data = [];
        var subjenisNames = [];

        for (var year in dataGroupedByYear) {
            labels.push(year);
            data.push(dataGroupedByYear[year].avg_harga);
            subjenisNames.push(dataGroupedByYear[year].subjenis_pangan_name);
        }

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
                            beginAtZero: true,
                            fontSize: 10,
                            color: "black" // Warna teks tahun
                        },
                        title: {
                            display: false,
                            text: 'Tahun',
                        },
                        grid: {
                            display: true,
                            color: 'rgba(180, 183, 197, 0.4)',
                            drawBorder: false
                        }
                    },
                    y: {
                        ticks: {
                            beginAtZero: true,
                            fontSize: 10,
                            color: "black", // Warna teks rata-rata harga
                            stepSize: 10,
                            min: 0
                        },
                        title: {
                            display: false,
                            text: 'Rata-rata Harga',
                        },
                        grid: {
                            display: true,
                            color: 'rgba(180, 183, 197, 0.4)',
                            drawBorder: false
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            afterLabel: function(context) {
                                var yearIndex = context.dataIndex;
                                return subjenisNames[yearIndex] ? 'Subjenis: ' + subjenisNames[yearIndex] : '';
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
