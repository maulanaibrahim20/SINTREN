<section id="pangan" class="about">
    <div class="container">
        <div class="section-title">
            <h2>Trend Pangan</h2>
        </div>

        <div class="row content">
            <div class="col-lg-12">
                <!-- Container untuk Grafik -->
                <div class="chart-container">
                    <canvas id="chartLine1"></canvas>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Menyertakan script di luar div container -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(function() {
        var ctx8 = document.getElementById('chartLine1').getContext('2d');

        // Data dari controller
        var years = @json($years);
        var datasets = @json($datasets);

        new Chart(ctx8, {
            type: 'line',
            data: {
                labels: years,
                datasets: datasets
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                scales: {
                    x: {
                        ticks: {
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
                            stepSize: 2000,
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
                        },
                        suggestedMin: 0 // Menyediakan nilai minimum sumbu Y
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            title: function(context) {
                                return 'Tahun: ' + context[0].label;
                            },
                            label: function(context) {
                                return 'Nama Pangan: ' + context.dataset.label + ' | Harga Rata-rata: ' + context.raw;
                            }
                        }
                    }
                }
            }
        });
    });
</script>

<!-- CSS untuk mengatur ukuran grafik -->
<style>
    .chart-container {
        position: relative;
        width: 100%;
        height: 400px; /* Sesuaikan dengan kebutuhan */
    }
</style>
