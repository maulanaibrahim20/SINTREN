@extends('index')
@section('title', 'Dashboard ')
@section('content')
    <div class="page-header d-sm-flex d-block">
        <ol class="breadcrumb br-7">
            <li class="breadcrumb-item1 active">Dashboard</li>
        </ol>
    </div>
    <div id="container"></div>
    <div class="row">
        @forelse ($penugasan as $tugas)
            @php
                $desaId = $tugas->desa_id;
                $perbandingan = $perbandinganNilai[$desaId] ?? ['sawah' => 0, 'non_sawah' => 0, 'has_value' => false];
            @endphp
            <div class="col-xl-4 col-md-6 col-lg-6 col-sm-6 m-b-3"> <!-- Adjust col size for smaller cards -->
                <div class="card">
                    <div class="">
                        <div class="row">
                            <div class="col-12">
                                <div class="p-2 bg-primary br-tr-7 br-tl-7">
                                    <div class="text-center text-white social mt-3">
                                        <h4>Desa {{ $tugas->desa->name }}</h4>
                                        @if ($perbandingan['has_value'])
                                            <p>Data Tersedia</p>
                                        @else
                                            <p>Data Tidak Tersedia</p>
                                        @endif
                                    </div>
                                </div>
                                @if ($perbandingan['has_value'])
                                    <div id="container-{{ $desaId }}" style="width:100%; height:300px;"></div>
                                    <!-- Adjust height for smaller chart -->
                                @else
                                    <div class="card-body mt-4">
                                        <p>Belum ada data laporan padi untuk desa ini.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <p>Belum ada penugasan</p>
        @endforelse
    </div>

@endsection

@section('script')
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            @foreach ($penugasan as $tugas)
                @php
                    $desaId = $tugas->desa_id;
                    $perbandingan = $perbandinganNilai[$desaId] ?? ['sawah' => 0, 'non_sawah' => 0, 'has_value' => false];
                @endphp

                @if ($perbandingan['has_value'])
                    // Konfigurasi grafik pai untuk setiap desa
                    Highcharts.chart('container-{{ $desaId }}', {
                        chart: {
                            type: 'pie'
                        },
                        title: {
                            text: 'Perbandingan Sawah dan Non Sawah di Desa {{ $tugas->desa->name }}'
                        },
                        tooltip: {
                            valueSuffix: '%'
                        },
                        plotOptions: {
                            series: {
                                allowPointSelect: true,
                                cursor: 'pointer',
                                dataLabels: {
                                    enabled: true,
                                    format: '{point.percentage:.1f}%',
                                    distance: -30,
                                    filter: {
                                        operator: '>',
                                        property: 'percentage',
                                        value: 4
                                    }
                                }
                            }
                        },
                        series: [{
                            name: 'Persentase',
                            colorByPoint: true,
                            data: [{
                                name: 'Sawah',
                                y: {{ $perbandingan['sawah'] }}
                            }, {
                                name: 'Non Sawah',
                                y: {{ $perbandingan['non_sawah'] }}
                            }]
                        }]
                    });
                @else
                    console.log('Belum ada data laporan padi untuk desa {{ $tugas->desa->name }}.');
                @endif
            @endforeach
        });
    </script>
@endsection
