@extends('index')
@section('title', 'Luas Lahan Wilayah | UPTD')
@section('content')
    <div class="page-header d-sm-flex d-block">
        <ol class="breadcrumb mb-sm-0 mb-3">
            <!-- breadcrumb -->
            <li class="breadcrumb-item"><a href="{{ url('/operator/dashboard') }}">{{ $breadcrumb }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $breadcrumb_active }}</li>
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
                    <h3 class="card-title">{{ $title }}</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-nowrap border-bottom" id="responsive-datatable">
                            <thead>
                                <tr>
                                    <th class="wd-15p border-bottom-0">No.</th>
                                    <th class="wd-15p border-bottom-0">Kecamatan</th>
                                    <th class="wd-15p border-bottom-0">Jenis Lahan</th>
                                    <th class="wd-15p border-bottom-0">Luas Lahan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($luas_wilayah as $data)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $data->getKecamatan->name }}</td>
                                        <td>{{ $data->jenis_lahan }}</td>
                                        <td>{{ number_format($data->luas_lahan_wilayah, 0, ',', '.') }} ha</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" style="text-align: center;">
                                            <p>Data belum tersedia untuk akun Anda.</p>
                                        </td>
                                    </tr>
                                @endforelse
                                <tr>
                                    <td colspan="3" style="text-align: right;"><strong>Lahan Yang Sudah Disuluh:</strong>
                                    </td>
                                    <td>
                                        @if ($getTanamanAkhirBulanLaporan)
                                            {{ $getTanamanAkhirBulanLaporan }} ha
                                        @else
                                            Data tidak tersedia
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" style="text-align: right;"><strong>Lahan Yang Belum Disuluh:</strong>
                                    </td>
                                    <td>
                                        @if ($selisih)
                                            {{ $selisih }} ha
                                        @else
                                            Data tidak tersedia
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" style="text-align: right;"><strong>Total Luas Lahan:</strong></td>
                                    <td>
                                        @if ($luas_wilayah->sum('luas_lahan_wilayah'))
                                            {{ number_format($luas_wilayah->sum('luas_lahan_wilayah'), 0, ',', '.') }} ha
                                        @else
                                            Data tidak tersedia
                                        @endif
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
