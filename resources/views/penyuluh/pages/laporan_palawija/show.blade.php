@extends('index')
@section('title', 'Detail Laporan Palawija')
@section('content')
    <div class="page-header d-sm-flex d-block">
        <ol class="breadcrumb mb-sm-0 mb-3">
            <!-- breadcrumb -->
            <li class="breadcrumb-item"><a href="{{ url('/penyuluh/dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item" aria-current="page">Laporan Palawija</li>
            <li class="breadcrumb-item active" aria-current="page">Detail Laporan Palawija</li>
        </ol><!-- End breadcrumb -->
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
                <div class="card-header">
                    <h3 class="card-title">Laporan Tanaman Palawija</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" style="width: 100%">
                            <tr>
                                <td class="text-right">Jenis Lahan</td>
                                <td>:</td>
                                <td>
                                    {{ $laporanPalawija->jenis_lahan }}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-right">Nama Pengumpul</td>
                                <td>:</td>
                                <td>
                                    {{ $laporanPalawija->nama_pengumpul }}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-right">Desa</td>
                                <td>:</td>
                                <td>
                                    {{ $laporanPalawija->desa->name }}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-right">kecamatan</td>
                                <td>:</td>
                                <td>
                                    {{ $laporanPalawija->kecamatan->name }}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card">
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
                <div class="card-header">
                    <h3 class="card-title">Detail Laporan Palawija</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-nowrap border-bottom table table-striped"
                            id="responsive-datatable">
                            <thead>
                                <tr>
                                    <th class="wd-15p border-bottom-0">ID laporan Palawija</th>
                                    <th class="wd-15p border-bottom-0">Jenis Palawija</th>
                                    <th class="wd-15p border-bottom-0">Jenis Bantuan</th>
                                    <th class="wd-20p border-bottom-0">Tanaman Akhir Bulan Lalu</th>
                                    <th class="wd-20p border-bottom-0">Panen</th>
                                    <th class="wd-20p border-bottom-0 text-center">Panen Muda</th>
                                    <th class="wd-20p border-bottom-0 text-center">Panen Pakan Ternak</th>
                                    <th class="wd-20p border-bottom-0 text-center">Tanam</th>
                                    <th class="wd-20p border-bottom-0 text-center">Puso/Rusak</th>
                                    <th class="wd-20p border-bottom-0 text-center">Tanaman Akhir Bulan Laporan</th>
                                    <th class="wd-20p border-bottom-0 text-center">Total Produksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($detailPalawija as $peng)
                                    <tr>
                                        <td>{{ $peng->id_laporan_palawija }}</td>
                                        <td>{{ $peng->jenisPalawija->name }}</td>
                                        <td>{{ $peng->jenis_bantuan }}</td>
                                        <td>{{ $peng->tanaman_akhir_bulan_lalu }}</td>
                                        <td>{{ $peng->panen }}</td>
                                        <td>{{ $peng->panen_muda }}</td>
                                        <td>{{ $peng->panen_pakan_ternak }}</td>
                                        <td>{{ $peng->tanam }}</td>
                                        <td>{{ $peng->puso_rusak }}</td>
                                        <td>{{ $peng->tanaman_akhir_bulan_laporan }}</td>
                                        <td>{{ $peng->total_produksi }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
