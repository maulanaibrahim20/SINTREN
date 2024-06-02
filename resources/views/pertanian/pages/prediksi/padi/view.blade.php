@extends('index')
@section('title', 'Hasil')
@section('content')
    <div>
        <p>Tipe Data: {{ $tipeData }}</p>
        <canvas id="myChart" width="200" height="100"></canvas>
    </div>
    <div>
        <h3>Detail Perhitungan Nilai:</h3>
        <ul>
            @foreach ($detailedPredictions as $description)
                <li>{{ $description }}</li>
            @endforeach
        </ul>
    </div>
@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            // Data prediksi dari controller
            const labels = @json($labels);
            const targets = @json($targets);

            // Inisialisasi data label
            const data = {
                labels: labels,
                datasets: [{
                    label: 'Prediksi Nilai Tahunan',
                    data: targets,
                    fill: false,
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                }]
            };

            // Konfigurasi grafik
            const config = {
                type: 'line',
                data: data,
                options: {}
            };

            // Render grafik ke canvas
            const myChart = new Chart(
                document.getElementById('myChart'),
                config
            );
        });
    </script>
@endsection
