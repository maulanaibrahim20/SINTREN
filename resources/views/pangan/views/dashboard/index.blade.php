@extends('index')
@section('title', 'Dashboard Pangan')
@section('content')
<div class="page-header d-sm-flex d-block">
    <ol class="breadcrumb mb-sm-0 mb-3">
        <li class="breadcrumb-item"><a href="{{ url('/pangan/dashboard') }}">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
    </ol>
</div>
<div class="row">
    <div class="row">
        <!-- Grafik Harian -->
        <div class="col-sm-12 col-lg-6 col-md-6 col-xxl-3">
            <div class="card overflow-hidden">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="mb-0 fw-semibold text-dark lh-1 mb-2">Grafik Harian</p>
                                    <div class="fs-12 text-muted mb-3">
                                        <a href="{{ url('/pangan/grafik/harian') }}">Lihat</a>
                                    </div>
                                </div>
                                <div class="text-end d-flex flex-column align-items-center">
                                    <span class="text-secondary lh-1 fs-40">
                                        <i class="fa fa-bar-chart"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Stok Pangan -->
        <div class="col-sm-12 col-lg-6 col-md-6 col-xxl-3">
            <div class="card overflow-hidden">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="mb-0 fw-semibold text-dark lh-1 mb-2">Data Stok Pangan</p>
                                    <div class="fs-12 text-muted mb-3">
                                        <a href="{{ url('/pangan/create/data_pangan') }}">Lihat</a>
                                    </div>
                                    <div class="fs-12 text-muted mb-1">Jumlah Data</div>
                                    <div class="fs-30 fw-semibold mb-0 lh-1">
                                        {{ $jumlahDataPangan }}
                                    </div>
                                </div>
                                <div class="text-end d-flex flex-column align-items-center">
                                    <span class="text-warning lh-1 fs-40"><i class="fe fe-file-text"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">{{ $title }} | Tanggal Hari Ini: {{ now()->toDateString() }}</h3>
                    <div>
                        <a href="{{ route('export.laporan.harian') }}" class="btn btn-success">Export to Excel</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-nowrap border-bottom" id="responsive-datatable">
                            <thead>
                                <tr>
                                    <th class="wd-15p border-bottom-0">No</th>
                                    <th class="wd-20p border-bottom-0">Gambar</th>
                                    <th class="wd-20p border-bottom-0">Jenis Pangan</th>
                                    <th class="wd-20p border-bottom-0">Nama Pangan</th>
                                    <th class="wd-20p border-bottom-0">Tanggal</th>
                                    <th class="wd-20p border-bottom-0">Jumlah Stok</th>
                                    <th class="wd-20p border-bottom-0">Harga Rata-rata</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $totalStok = 0;
                                $totalHarga = 0;
                                $no = 1; // Penomoran manual
                                @endphp

                                @foreach ($dataGroupedBySubjenis as $subjenisId => $data)
                                @if ($data['total_stok'] != 0 && $data['avg_harga'] != 0)
                                <tr>
                                    <td>{{ $no++ }}</td> <!-- Penomoran manual -->
                                    <td>
                                        @if ($data['gambar'])
                                        <img src="{{ asset('storage/' . $data['gambar']) }}" alt="{{ $data['name'] }}" class="img-fluid" style="max-width: 100px;">
                                        @else
                                        <span>No Image</span>
                                        @endif
                                    </td>
                                    <td>{{ $data['jenis_pangan_name'] }}</td>
                                    <td>{{ $data['name'] }}</td>
                                    <td>{{ $data['latest_date'] }}</td>
                                    <td>{{ formatRibuan($data['total_stok']) }}</td>
                                    <td>{{ formatRibuan($data['avg_harga']) }}</td>
                                </tr>
                                @php
                                $totalStok += $data['total_stok'];
                                $totalHarga += $data['avg_harga'];
                                @endphp
                                @endif

                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>
@endsection
