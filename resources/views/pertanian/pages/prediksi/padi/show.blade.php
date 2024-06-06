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
            @foreach ($labels as $key => $label)
                <li>Bulan: {{ $label }}, Nilai: {{ $targets[$key] }}</li>
            @endforeach
        </ul>
    </div>
    <div class="container">
        <h2>Prediksi Nilai Padi</h2>

        <h4>Proses Perhitungan</h4>
        <ol>
            <li>
                <strong>Input Data:</strong>
                <ul>
                    <li>Tipe Data: {{ $tipeData }}</li>
                    <li>Dari Bulan: {{ array_search($dariBulan, $bulanKeAngka) }}</li>
                    <li>Dari Tahun: {{ $dariTahun }}</li>
                    <li>Sampai Bulan: {{ array_search($sampaiBulan, $bulanKeAngka) }}</li>
                    <li>Sampai Tahun: {{ $sampaiTahun }}</li>
                </ul>
            </li>
            <li>
                <strong>Konversi Bulan ke Format Angka:</strong>
                <ul>
                    @foreach ($bulanKeAngka as $bulan => $angka)
                        <li>{{ ucfirst($bulan) }}: {{ $angka }}</li>
                    @endforeach
                </ul>
            </li>
            <li>
                <strong>Membuat Tanggal Awal dan Akhir:</strong>
                <ul>
                    <li>Dari Tanggal: {{ $dariTanggal }}</li>
                    <li>Sampai Tanggal: {{ $sampaiTanggal }}</li>
                </ul>
            </li>
            <li>
                <strong>Pengambilan Data dari Database:</strong>
                <ul>
                    <li>Total Data Ditemukan: {{ $groupedData->flatten()->count() }}</li>
                </ul>
            </li>
            <li>
                <strong>Mengelompokkan Data Berdasarkan Bulan dan Tahun:</strong>
                <ul>
                    @foreach ($groupedData as $month => $data)
                        <li>{{ $month }}: {{ $data->count() }} entri</li>
                    @endforeach
                </ul>
            </li>
            <li>
                <strong>Membuat Sample dan Target untuk Model:</strong>
                <ul>
                    @foreach ($samples as $index => $sample)
                        <li>
                            Bulan: {{ $sample[0] }}, Tahun: {{ $sample[1] }}, Rata-rata Nilai:
                            {{ number_format($targets[$index], 2) }}
                        </li>
                    @endforeach
                </ul>
            </li>
            <li>
                <strong>Melatih Model Regresi Linier:</strong>
                <p>
                    Proses pelatihan model regresi linier dilakukan sebagai berikut:
                </p>
                <ol>
                    <li>
                        Pertama, kita siapkan data yang akan digunakan untuk melatih model. Data ini terdiri dari sampel
                        (bulan dan tahun) sebagai fitur dan rata-rata nilai sebagai target. Contoh data sampel dan target
                        yang digunakan adalah:
                        <ul>
                            @foreach ($samples as $index => $sample)
                                <li>
                                    Sampel: [Bulan: {{ $sample[0] }}, Tahun: {{ $sample[1] }}], Target:
                                    {{ number_format($targets[$index], 2) }}
                                </li>
                            @endforeach
                        </ul>
                    </li>
                    <li>
                        Kedua, kita menggunakan algoritma regresi linier untuk menemukan hubungan antara fitur (bulan dan
                        tahun) dengan target (rata-rata nilai). Proses ini disebut pelatihan model. Pada pelatihan ini,
                        algoritma regresi linier mencoba mencari garis terbaik yang dapat memprediksi nilai target
                        berdasarkan fitur yang diberikan.
                    </li>
                    <li>
                        Setelah model dilatih, model ini akan digunakan untuk membuat prediksi nilai untuk bulan dan tahun
                        berikutnya. Proses pelatihan model dilakukan menggunakan pustaka `LeastSquares` yang menyediakan
                        metode `train` untuk melatih model dengan data sampel dan target.
                    </li>
                </ol>
            </li>
            <li>
                <strong>Prediksi untuk Bulan Berikutnya:</strong>
                <ul>
                    <li>Bulan Prediksi: {{ $bulanPrediksi }}</li>
                    <li>Tahun Prediksi: {{ $tahunPrediksi }}</li>
                    <li>Nilai Prediksi: {{ number_format($predictedValue, 2) }}</li>
                </ul>
            </li>
        </ol>

        <h4>Hasil Prediksi</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Bulan</th>
                    <th>Nilai</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($labels as $index => $label)
                    <tr>
                        <td>{{ $label }}</td>
                        <td>{{ number_format($targets[$index], 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div>
            <canvas id="prediksiChart"></canvas>
        </div>
    </div>

@endsection
@section('script')
    {{-- <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            // Data prediksi dari controller
            const labels = @json($labels);
            const targets = @json($targets);

            // Inisialisasi data label
            const data = {
                labels: labels,
                datasets: [{
                    label: 'Prediksi Nilai Bulanan',
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
    </script> --}}
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            // Data prediksi dari controller
            const labels = @json($labels);
            const targets = @json($targets);

            // Inisialisasi array untuk menyimpan warna garis chart
            const lineColors = [];

            // Mendapatkan nilai bulan akhir
            const lastIndex = targets.length - 1;
            const lastValue = targets[lastIndex];

            // Lakukan pengecekan perbandingan nilai bulan sebelumnya dengan bulan akhir
            for (let i = 0; i < lastIndex; i++) {
                // Jika nilai bulan sebelumnya lebih rendah dari bulan akhir
                if (targets[i] < lastValue) {
                    // Warna garis chart diatur menjadi merah
                    lineColors.push('red');
                } else {
                    // Warna garis chart diatur menjadi biru
                    lineColors.push('blue');
                }
            }

            // Untuk bulan akhir, warna garis chart akan tetap berdasarkan logika perbandingan dengan bulan sebelumnya

            // Inisialisasi data label
            const data = {
                labels: labels,
                datasets: [{
                    label: 'Prediksi Nilai Bulanan',
                    data: targets,
                    borderColor: lineColors, // Gunakan warna yang telah ditentukan untuk garis chart
                    borderWidth: 2,
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
