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
                    <div class="dropdown ms-auto">
                        @if (empty(session('filtering')))
                        @else
                            <form style="display: inline;" action="{{ url('/pertanian/data_padi/exportPdf') }}"
                                method="get" target="_blank">
                                @if (session('filterKecamatanData') && session('filterDesaData'))
                                    <input type="hidden" name="filterKecamatan"
                                        value="{{ session('filterKecamatanData') }}">
                                    <input type="hidden" name="filterDesa" value="{{ session('filterDesaData') }}">
                                    <input type="hidden" name="filterDate" value="{{ session('filterDateData') }}">
                                @endif
                                <button class="btn btn-outline-default fw-bold text-primary" type="submit">
                                    <i class="fa fa-print fw-semibold"></i> Export PDF
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <form action="{{ url('/pertanian/data_padi/filter') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <div class="row">
                                    <label class="form-label col-sm-1" style="margin-top: 5px;"> Filter : </label>
                                    <div class="col-md-2">
                                        <select name="filterKecamatan" class="form-control select2 form-select"
                                            data-placeholder="Pilih Kecamatan" id="filterKecamatan" required>
                                            <option value="">- Pilih Kecamatan -</option>
                                            {{-- @foreach ($filterKecamatan as $kec)
                                                <option value="{{ $kec->id }}"
                                                    @if (session('filtering') && session('filtering')->contains('kecamatan_id', $kec->id)) selected @endif>
                                                    {{ $kec->name }}
                                                </option>
                                            @endforeach --}}
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <select name="filterDesa" class="form-control select2 form-select"
                                            data-placeholder="Pilih Desa" id="filterDesa" required>
                                            <option value="">- Pilih Desa -</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="input-group">
                                            <div class="input-group-text">
                                                <i class="typcn typcn-calendar-outline tx-24 lh--9 op-6"></i>
                                            </div>
                                            <input type="text" name="dateRange" class="form-control" id="daterange"
                                                placeholder="Pilih Range Tanggal" value="{{ session('dateRange') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-1">
                                        <input type="submit" class="btn btn-pill btn-primary" value="FILTER">
                                    </div>
                                </div>
                            </div>
                        </form>
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
                                        <td>{{ $data->month_year }}</td>
                                        <td>{{ $data->total_nilai }}</td>
                                        <td class="text-center">
                                            <a href="{{ url('/uptd/laporan/padi/showDetailLaporan/' . $data->desa_id) }}"
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
