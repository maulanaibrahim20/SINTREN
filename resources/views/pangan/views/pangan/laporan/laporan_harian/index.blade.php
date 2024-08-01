@extends('index')

@section('title', 'Laporan Harian | Pangan')

@section('content')
<div class="page-header d-sm-flex d-block">
    <ol class="breadcrumb mb-sm-0 mb-3">
        <li class="breadcrumb-item1"><a href="{{ url('/pangan/dashboard') }}">{{ $breadcrumb }}</a></li>
        <li class="breadcrumb-item1 active">{{ $breadcrumb_active }}</li>
    </ol>
</div>

<div class="row mb-3">
    <div class="col-md-4 d-flex align-items-center">
        <div class="form-group mb-0">
            <label for="date" class="form-label">Tanggal:</label>
            <input type="date" id="date" name="date" class="form-control" value="{{ request('date', now()->toDateString()) }}">
        </div>
    </div>
    <div class="col-md-8 d-flex align-items-center justify-content-end">
        <button id="filterButton" class="btn btn-primary mr-2">Filter</button>
        <a href="#" id="exportButton" class="btn btn-success">Export to Excel</a>
    </div>
</div>

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

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ $title }}</h3>
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
@endsection

@section('script')
<script>
    document.getElementById('filterButton').addEventListener('click', function() {
        const selectedDate = document.getElementById('date').value;

        const url = new URL(window.location.href);
        url.searchParams.set('date', selectedDate);

        window.location.href = url.toString();
    });

    document.getElementById('exportButton').addEventListener('click', function() {
        const selectedDate = document.getElementById('date').value;
        const exportUrl = "{{ route('export.laporan.harian') }}?date=" + selectedDate;

        window.location.href = exportUrl;
    });
</script>
@endsection
