@extends('index')
@section('title', 'Laporan Pangan | Pangan')
@section('content')
    <div class="page-header d-sm-flex d-block">
        <ol class="breadcrumb mb-sm-0 mb-3">
            <!-- breadcrumb -->
            <li class="breadcrumb-item1"><a href="{{ url('/pangan/dashboard') }}">{{ $breadcrumb }}</a></li>
            <li class="breadcrumb-item1 active">{{ $breadcrumb_active }}</li>
        </ol><!-- End breadcrumb -->
    </div>

    <!-- Filter Tanggal Mulai dan Akhir -->
    <div class="row mb-3">
        <div class="col-md-2">
            <label for="start_date" class="form-label"><b>Tanggal Mulai</b></label>
            <input type="date" class="form-control" id="start_date" name="start_date" placeholder="Tanggal Mulai">
        </div>
        <div class="col-md-2">
            <label for="end_date" class="form-label"><b>Tanggal Akhir</b></label>
            <input type="date" class="form-control" id="end_date" name="end_date" placeholder="Tanggal Akhir">
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button class="btn btn-primary" id="filterButton">Filter</button>
        </div>
        <div class="col-md-6 d-flex align-items-end justify-content-end">
            <a href="{{ route('export.laporan.pangan', ['start_date' => 'your_start_date', 'end_date' => 'your_end_date']) }}" class="btn btn-success">Export to Excel</a>
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
                    <h3 class="card-title">{{ $title }}</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-nowrap border-bottom" id="responsive-datatable">
                            <thead>
                                <tr>
                                    <th class="wd-15p border-bottom-0">No</th>
                                    <th class="wd-15p border-bottom-0">Status</th>
                                    <th class="wd-15p border-bottom-0">Pasar</th>
                                    <th class="wd-20p border-bottom-0">Nama Pangan</th>
                                    <th class="wd-20p border-bottom-0">Tanggal</th>
                                    <th class="wd-20p border-bottom-0">Kebutuhan(Ton)</th>
                                    <th class="wd-20p border-bottom-0">Ketersediaan(Ton)</th>
                                    {{-- <th class="wd-20p border-bottom-0">Neraca(Ton)</th> --}}
                                    <th class="wd-20p border-bottom-0">Harga(Rp/Kg)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalKebutuhan = 0;
                                    $totalKetersediaan = 0;
                                    // $totalNeraca = 0;
                                    $totalHarga = 0;
                                @endphp
                                @foreach ($datapangan as $data)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            @if ($data->status == '1')
                                                <span class="badge bg-success-transparent text-warning fw-semibold">Terkirim</span>
                                            @endif
                                        </td>
                                        <td>{{ $data->pasar ? $data->pasar->name : 'Pasar Tidak Ditemukan' }}</td>
                                        <td>{{ $data->name }}</td>
                                        <td>{{ $data->date }}</td>
                                        <td>{{ $data->kebutuhan }}</td>
                                        <td>{{ $data->ketersediaan }}</td>
                                        {{-- <td>{{ formatRibuan($data->neraca) }}</td> --}}
                                        <td>{{ $data->harga }}</td>
                                    </tr>
                                    @php
                                        $totalKebutuhan += $data->kebutuhan;
                                        $totalKetersediaan += $data->ketersediaan;
                                        // $totalNeraca += $data->neraca;
                                        $totalHarga += $data->harga;
                                    @endphp
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="5" class="text-center">Total</th>
                                    <th>{{ $totalKebutuhan }}</th>
                                    <th>{{ $totalKetersediaan }}</th>
                                    {{-- <th>{{ formatRibuan($totalNeraca) }}</th> --}}
                                    <th>{{ $totalHarga }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        // Filter button click event
        $('#filterButton').on('click', function() {
            var startDate = $('#start_date').val();
            var endDate = $('#end_date').val();
            var url = "{{ url('/pangan/data/laporan_pangan') }}?start_date=" + startDate + "&end_date=" + endDate;
            window.location.href = url;
        });
    </script>
@endsection
