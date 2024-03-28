@extends('index')
@section('title', 'Detail Laporan Padi')
@section('content')
    <div class="page-header d-sm-flex d-block">
        <ol class="breadcrumb mb-sm-0 mb-3">
            <!-- breadcrumb -->
            <li class="breadcrumb-item"><a href="{{ url('/penyuluh/dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item" aria-current="page">Laporan Padi</li>
            <li class="breadcrumb-item active" aria-current="page">Detail Laporan Padi</li>
        </ol><!-- End breadcrumb -->
        <div class="ms-auto">
            {{-- <div>
                <a href="{{ url('/penyuluh/create/laporan_padi/create') }}" class="btn bg-primary-transparent"
                    data-bs-toggle="tooltip" title="Add New User" data-bs-placement="bottom">
                    <span>
                        <i class="fa fa-plus"></i>
                    </span>
                    Tambah Laporan Padi
                </a>
            </div> --}}
        </div>
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
                    <h3 class="card-title">Detail Laporan Tanaman Padi</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-nowrap border-bottom" id="responsive-datatable">
                            <thead>
                                <tr>
                                    <th class="wd-15p border-bottom-0">No.</th>
                                    <th class="wd-15p border-bottom-0">ID Laporan</th>
                                    <th class="wd-20p border-bottom-0">Jenis Padi</th>
                                    <th class="wd-15p border-bottom-0">Jenis Bantuan</th>
                                    <th class="wd-15p border-bottom-0">Tanaman Akhir Bulan Lalu</th>
                                    <th class="wd-20p border-bottom-0">Panen</th>
                                    <th class="wd-20p border-bottom-0">Tanam</th>
                                    <th class="wd-20p border-bottom-0">Puso/Rusak</th>
                                    <th class="wd-20p border-bottom-0">Tanaman Akhir Bulan Laporan</th>
                                    <th class="wd-20p border-bottom-0">Hari/Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($detail_padi as $data)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $data->id_laporan_padi }}</td>
                                        <td>{{ $data->padi->name }}</td>
                                        <td>{{ $data->jenis_bantuan }}</td>
                                        <td>{{ $data->tanaman_akhir_bulan_lalu }}</td>
                                        <td>{{ $data->panen }}</td>
                                        <td>{{ $data->tanam }}</td>
                                        <td>{{ $data->puso_rusak }}</td>
                                        <td>{{ $data->tanaman_akhir_bulan_laporan }}</td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($data->created_at)->isoFormat('dddd, D MMMM YYYY') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
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
                    <h3 class="card-title">Detail Laporan Pengairan</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-nowrap border-bottom" id="responsive-datatable">
                            <thead>
                                <tr>
                                    <th class="wd-15p border-bottom-0">No.</th>
                                    <th class="wd-15p border-bottom-0">ID laporan Padi</th>
                                    <th class="wd-15p border-bottom-0">Jenis Pengairan</th>
                                    <th class="wd-15p border-bottom-0">Tanaman Akhir Bulan Lalu</th>
                                    <th class="wd-20p border-bottom-0">Panen</th>
                                    <th class="wd-20p border-bottom-0">Tanam</th>
                                    <th class="wd-20p border-bottom-0 text-center">Puso/Rusak</th>
                                    <th class="wd-20p border-bottom-0 text-center">Tanaman Akhir Bulan Laporan</th>
                                    <th class="wd-20p border-bottom-0 text-center">Hari/Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($detail_pengairan as $peng)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $peng->id_laporan_padi }}</td>
                                        <td>{{ $peng->pengairan->name }}</td>
                                        <td>{{ $peng->tanaman_akhir_bulan_lalu }}</td>
                                        <td>{{ $peng->panen }}</td>
                                        <td>{{ $peng->tanam }}</td>
                                        <td>{{ $peng->puso_rusak }}</td>
                                        <td>{{ $peng->tanaman_akhir_bulan_laporan }}</td>
                                        <td>{{ \Carbon\Carbon::parse($peng->created_at)->isoFormat('dddd, D MMMM YYYY') }}
                                        </td>
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
