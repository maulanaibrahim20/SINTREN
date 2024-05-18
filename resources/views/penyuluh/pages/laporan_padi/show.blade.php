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
                                    <th class="wd-15p border-bottom-0">Nama Pengumpul</th>
                                    <th class="wd-20p border-bottom-0">Alamat</th>
                                    <th class="wd-15p border-bottom-0">Jenis Lahan</th>
                                    <th class="wd-15p border-bottom-0">Jenis Bantuan</th>
                                    <th class="wd-20p border-bottom-0">Panen</th>
                                    <th class="wd-20p border-bottom-0">Tanam</th>
                                    <th class="wd-20p border-bottom-0">Puso/Rusak</th>
                                    <th class="wd-20p border-bottom-0">Tanaman Akhir Bulan Laporan</th>
                                    <th class="wd-20p border-bottom-0">Hari/Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $show->user->name }}</td> <!-- Jika user memiliki relasi dengan user -->
                                    <td>
                                        <div class="d-flex contact-image">
                                            <div class="d-flex mt-1 flex-column ms-2">
                                                <h6 class="mb-0 fs-14 fw-semibold text-dark">{{ $show->desa->name }}
                                                </h6>
                                                <span class="fs-12 text-muted">{{ $show->kecamatan->name }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $show->jenis_lahan }}</td>
                                    <td>{{ $show->jenis_bantuan }}</td>
                                    <td>{{ $show->pengairan->name ?? 'N/A' }}</td>
                                    <td>{{ $show->tipe_data }}</td>
                                    <td>{{ $show->nilai }}</td>
                                    <td>{{ \Carbon\Carbon::parse($show->created_at)->isoFormat('dddd, D MMMM YYYY') }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
