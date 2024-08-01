<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Sintrenayu</title>
    <meta content="" name="description">
    <meta content="" name="keywords">
    @include('landing.component.style_css')
</head>

<body>

    <!-- ======= Header ======= -->
    @include('landing.header')
    <!-- End Header -->

    <!-- ======= Hero Section ======= -->
    @include('landing.hero')
    <!-- End Hero -->

    <main id="main">

        <!-- ======= About Us Section ======= -->
        @include('layout_landing.aboutus')
        <!-- End About Us Section -->
        @include('layout_landing.prediksi')

        @include('layout_landing.trend_pangan')

        @include('layout_landing.ctamobile')

        <!-- ======= Contact Section ======= -->
        @include('layout_landing.contactus')
        <!-- End Contact Section -->
    </main>
    <!-- End #main -->

    <!-- ======= Footer ======= -->
    @include('landing.footer')
    <!-- End Footer -->

    <div id="preloader"></div>
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>
    @include('landing.component.style_js')
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script>
        Highcharts.chart('container', {
            chart: {
                type: 'spline'
            },
            title: {
                text: 'Trend Pertanian Padi'
            },
            subtitle: {
                text: 'Sumber: Dinas Ketahanan Pangan Dan Pertanian Kabupaten Indramayu'
            },
            xAxis: {
                categories: {!! json_encode($labels) !!},
                accessibility: {
                    description: 'Tahun'
                }
            },
            yAxis: {
                title: {
                    text: 'Produksi (ton)'
                },
                labels: {
                    format: '{value}'
                },
                min: 0 // Mulai dari nilai 0 pada sumbu y
            },
            tooltip: {
                crosshairs: true,
                shared: true,
                valueSuffix: ' ton'
            },
            plotOptions: {
                spline: {
                    marker: {
                        radius: 4,
                        lineColor: '#666666',
                        lineWidth: 1
                    }
                }
            },
            series: [{
                name: 'Aktual',
                marker: {
                    symbol: 'square'
                },
                color: '#FF5733', // Warna oranye untuk seri 'Aktual'
                data: {!! json_encode(array_values($actualData)) !!}
            }, {
                name: 'Prediksi',
                marker: {
                    symbol: 'diamond'
                },
                color: '#337AFF', // Warna biru untuk seri 'Prediksi'
                data: {!! json_encode($predictedData) !!}
            }]
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    @if (session('success'))
        <script type="text/javascript">
            Swal.fire({
                title: "Berhasil",
                text: "{{ session('success') }}",
                icon: "success"
            });
        </script>
    @endif
    @if (session('error'))
        <script type="text/javascript">
            Swal.fire({
                title: "Gagal",
                text: "{{ session('error') }}",
                icon: "error"
            });
        </script>
    @endif
</body>

</html>
