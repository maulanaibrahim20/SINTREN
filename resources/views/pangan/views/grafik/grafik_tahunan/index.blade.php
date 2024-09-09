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
                <form action="{{ route('grafik.tahunan.index') }}" method="GET" class="mb-4">
                    <div class="row">
                        <div class="col-md-4">
                            <select name="subjenis_pangan" class="form-control">
                                <option value="">-- Pilih Subjenis Pangan --</option>
                                @foreach($subjenisPangan as $subjenis)
                                <option value="{{ $subjenis->id }}" {{ $selectedSubjenisPangan == $subjenis->id ? 'selected' : '' }}>
                                    {{ $subjenis->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 ">
                            <button type="submit" class="btn btn-primary">Filter</button>
                        </div>
                    </div>
                </form>
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
        var years = @json($dataGroupedByYear);
        var datasets = @json($datasets);

        new Chart(ctx8, {
            type: 'line',
            data: {
                labels: years.map(year => year.toString()), // Ubah tahun menjadi string untuk label sumbu X
                datasets: datasets.map(function(dataset) {
                    return {
                        label: dataset.label,
                        data: dataset.data.map(function(point) {
                            return {
                                x: point.year, // Tahun untuk sumbu X
                                y: point.avg_harga, // Harga rata-rata untuk sumbu Y
                                stok: point.total_stok, // Stok untuk ditampilkan di tooltip
                                subjenis: dataset.label // Nama subjenis pangan
                            };
                        }),
                        borderColor: dataset.borderColor,
                        backgroundColor: dataset.backgroundColor,
                        borderWidth: dataset.borderWidth,
                        fill: false // Hindari pengisian area di bawah garis
                    };
                })
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Tahun',
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
                            stepSize: 2000, // Anda bisa menyesuaikan langkah ini sesuai kebutuhan
                            min: 0
                        },
                        title: {
                            display: true,
                            text: 'Rata-rata Harga',
                        },
                        grid: {
                            display: true,
                            color: 'rgba(180, 183, 197, 0.4)',
                            drawBorder: false
                        },
                        suggestedMin: 0 // Menyediakan nilai minimum sumbu Y
                    }
                },
                plugins: {
                    legend: {
                        display: false // Menonaktifkan legenda
                    },
                    tooltip: {
                        callbacks: {
                            title: function(context) {
                                return 'Tahun: ' + context[0].label;
                            },
                            label: function(context) {
                                var point = context.raw;
                                return `Subjenis: ${point.subjenis}\nHarga Rata-rata: ${point.y.toLocaleString()}\nStok: ${point.stok.toLocaleString()}`;
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
