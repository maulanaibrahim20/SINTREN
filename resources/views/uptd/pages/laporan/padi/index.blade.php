@extends('index')
@section('title', 'Laporan UPTD Padi')
@section('content')
    <div class="page-header d-sm-flex d-block">
        <ol class="breadcrumb mb-sm-0 mb-3">
            <!-- breadcrumb -->
            <li class="breadcrumb-item"><a href="{{ url('/penyuluh/dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Laporan Padi</li>
        </ol><!-- End breadcrumb -->
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Data Laporan Luas Tanaman Padi</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-nowrap border-bottom" id="responsive-datatable">
                            <thead>
                                <tr>
                                    <th class="wd-15p border-bottom-0">No</th>
                                    <th class="wd-15p border-bottom-0">Desa</th>
                                    <th class="wd-15p border-bottom-0">Tanggal</th>
                                    <th class="wd-20p border-bottom-0">Total Nilai Laporan</th>
                                    <th class="wd-20p border-bottom-0 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($laporanPadi as $data)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $data->name }}</td>
                                        <td>{{ \Carbon\Carbon::parse($data->month_year)->translatedFormat('F Y') }}</td>
                                        <td>{{ $data->total_nilai }}</td>
                                        <td class="text-center">
                                            <a href="{{ url('/uptd/laporan/padi/showDetailLaporan/' . $data->desa_id . '/' . $data->month_year) }}"
                                                class="btn btn-primary">
                                                <i class="ti ti-eye"></i>
                                            </a>
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
