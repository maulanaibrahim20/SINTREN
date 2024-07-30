@extends('index')

@section('title', 'Laporan Tahun | Pangan')

@section('content')
<div class="page-header d-sm-flex d-block">
    <ol class="breadcrumb mb-sm-0 mb-3">
        <li class="breadcrumb-item1">
            <a href="{{ url('/pangan/dashboard') }}">{{ $breadcrumb }}</a>
        </li>
        <li class="breadcrumb-item1 active">{{ $breadcrumb_active }}</li>
    </ol>
</div>

<div class="row mb-3">
    <form action="{{ route('laporan_tahunan.tahunan') }}" method="GET">
        <div class="form-row align-items-center">
            <!-- Tahun Filter -->
            <div class="col-md-2 mb-2">
                <div class="form-group">
                    <label for="year" class="form-label">Tahun:</label>
                    <select id="year" name="year" class="form-control" required>
                        @foreach (range(now()->year - 5, now()->year) as $y)
                            <option value="{{ $y }}" {{ $y == request('year', now()->year) ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Tombol Filter -->
            <div class="col-md-2 align-items-center">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>

            <!-- Tombol Export -->
            <div class="col-md-8 d-flex align-items-center justify-content-end mb-2">
                <a href="{{ route('export.laporan.tahunan', ['year' => request('year', now()->year)]) }}" class="btn btn-success">Export to Excel</a>
            </div>
        </div>
    </form>
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
                @if (empty($dataGroupedBySubjenis))
                    <p>Tidak ada data untuk bulan dan tahun yang dipilih.</p>
                @else
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
                                    $no = 1; // Penomoran manual$no = 1; // Penomoran manual
                                @endphp

                                @foreach ($dataGroupedBySubjenis as $subjenisId => $data)
                                    @if (!empty($data['name']) && !empty($data['total_stok']) && !empty($data['avg_harga']))
                                        <tr>
                                            {{-- <td>{{ $loop->iteration }}</td> --}}
                                            <td>{{ $no++ }}</td> <!-- Penomoran manual -->
                                            <td>
                                                @if ($data['gambar'])
                                                    <img src="{{ asset('storage/' . $data['gambar']) }}"
                                                         alt="{{ $data['name'] }}"
                                                         class="img-fluid"
                                                         style="max-width: 100px;">
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
                            {{-- <tfoot>
                                <tr>
                                    <th colspan="4" class="text-center">Total</th>
                                    <th>{{ formatRibuan($totalStok) }}</th>
                                    <th>{{ formatRibuan($totalHarga) }}</th>
                                    <th></th>
                                </tr>
                            </tfoot> --}}
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $('.deleteBtn').on('click', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        var deleteForm = $('#deleteForm' + id);

        Swal.fire({
            title: 'Anda yakin?',
            text: "Data akan dihapus secara permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                deleteForm.submit();
            }
        });
    });

    document.getElementById('filterButton').addEventListener('click', function() {
        const pasarId = document.getElementById('pasar_id').value;
        const subjenisPanganId = document.getElementById('subjenis_pangan_id').value;

        const url = new URL(window.location.href);
        url.searchParams.set('pasar_id', pasarId);
        url.searchParams.set('subjenis_pangan_id', subjenisPanganId);

        window.location.href = url.toString();
    });
</script>
@endsection
