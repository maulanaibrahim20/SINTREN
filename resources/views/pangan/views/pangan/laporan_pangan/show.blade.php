@extends('index')
@section('title', 'View Data Stok Pangan')
@section('content')
    <div class="page-header d-sm-flex d-block">
        <ol class="breadcrumb1 br-7">
            <li class="breadcrumb-item1"><a href="{{ url('/pangan/dashboard') }}">{{ $breadcrumb }}</a></li>
            <li class="breadcrumb-item1"><a href="{{ url('/pangan/create/data_pangan') }}">{{ $breadcrumb_1 }}</a></li>
            <li class="breadcrumb-item1 active">{{ $breadcrumb_active }}</li>
        </ol>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Laporan Petugas</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" style="width: 100%">
                            <tr>
                                <td class="text-right">Tanggal Input</td>
                                <td>:</td>
                                <td>{{ $laporanpangan->date }}</td>
                            </tr>
                            <tr>
                                <td class="text-right">Status</td>
                                <td>:</td>
                                <td>
                                    @if ($laporanpangan->status)
                                        <span class="badge bg-success-transparent text-success fw-semibold">Terkirim</span>
                                    @else
                                        <span class="badge bg-danger-transparent text-danger fw-semibold">Belum Terkirim</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="text-right">Nama Petugas</td>
                                <td>:</td>
                                <td>{{ $laporanpangan->user->name }}</td>
                            </tr>
                            <tr>
                                <td class="text-right">Pasar</td>
                                <td>:</td>
                                <td>{{ $laporanpangan->pasar->name }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="card-header">
                    <h3 class="card-title">Laporan Data Pangan</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" style="width: 100%">
                            <tr>
                                <td class="text-right">Jenis Pangan</td>
                                <td>:</td>
                                <td>{{ $laporanpangan->kategori_pangan->name }}</td>
                            </tr>
                            <tr>
                                <td class="text-right">Nama Pangan</td>
                                <td>:</td>
                                <td>{{ $laporanpangan->name }}</td>
                            </tr>
                            <tr>
                                <td class="text-right">Kebutuhan</td>
                                <td>:</td>
                                <td>{{ $laporanpangan->kebutuhan }}</td>
                            </tr>
                            <tr>
                                <td class="text-right">Ketersediaan</td>
                                <td>:</td>
                                <td>{{ $laporanpangan->ketersediaan }}</td>
                            </tr>
                            <tr>
                                <td class="text-right">Neraca</td>
                                <td>:</td>
                                <td>{{ $laporanpangan->neraca }}</td>
                            </tr>
                            <tr>
                                <td class="text-right">Harga</td>
                                <td>:</td>
                                <td>{{ $laporanpangan->harga }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ url('/pangan/create/data_pangan') }}" class="btn btn-warning">
                        <i class="fa fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
