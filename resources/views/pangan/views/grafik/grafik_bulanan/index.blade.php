@extends('index')
@section('title', 'Grafik Bulanan Pangan | Pangan')
@section('content')
<div class="page-header d-sm-flex d-block">
    <ol class="breadcrumb mb-sm-0 mb-3">
        <li class="breadcrumb-item1"><a href="{{ url('/pangan/dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item1 active">Grafik Bulanan Pangan</li>
    </ol>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Grafik Bulanan Pangan</h4>
                <form method="GET" action="{{ route('grafik.bulanan.index') }}" class="d-flex align-items-center">
                    <div class="form-group mb-0 me-3">
                        <label for="year" class="form-label mb-0">Pilih Tahun:</label>
                        <input type="number" id="year" name="year" class="form-control" value="{{ $selectedYear }}" min="2000" max="{{ date('Y') }}">
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Filter</button>
                </form>
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
        var dataGroupedBySubjenis = @json($dataGroupedBySubjenis);

        // Mendapatkan bulan saat ini
        var currentMonth = new Date().getMonth() + 1; // Bulan saat ini (1-indexed)

        // Membuat array bulan hingga bulan saat ini
        var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'].slice(0, currentMonth);

        // Fungsi untuk menghasilkan warna acak
        function getRandomColor() {
            var letters = '0123456789ABCDEF';
            var color = '#';
            for (var i = 0; i < 6; i++) {
                color += letters[Math.floor(Math.random() * 16)];
            }
            return color;
        }

        var datasets = Object.keys(dataGroupedBySubjenis).map((subjenisId) => {
            return {
                label: dataGroupedBySubjenis[subjenisId].name,
                data: months.map((_, monthIndex) => dataGroupedBySubjenis[subjenisId].data[monthIndex + 1] || 0),
                backgroundColor: 'rgba(0, 0, 0, 0)',
                borderColor: getRandomColor(),
                borderWidth: 2,
                fill: false
            };
        });

        new Chart(ctx8, {
            type: 'line',
            data: {
                labels: months,
                datasets: datasets
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                scales: {
                    x: {
                        ticks: {
                            font: {
                                size: 10
                            },
                            color: "black"
                        },
                        title: {
                            display: false,
                            text: 'Bulan'
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
                            font: {
                                size: 10
                            },
                            color: "black",
                            stepSize: 5000,
                            min: 0
                        },
                        title: {
                            display: false,
                            text: 'Rata-rata Harga'
                        },
                        grid: {
                            display: true,
                            color: 'rgba(180, 183, 197, 0.4)',
                            drawBorder: false
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
