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
    <div class="col-md-2">
        <label for="pasar_id" class="form-label"><b>Pasar</b></label>
        <select class="form-control" id="pasar_id" name="pasar_id">
            <option value="">Semua</option>
            @foreach($pasarList as $pasar)
                <option value="{{ $pasar->id }}" {{ request()->get('pasar_id') == $pasar->id ? 'selected' : '' }}>
                    {{ $pasar->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-2">
        <label for="subjenis_pangan_id" class="form-label"><b>Nama Pangan</b></label>
        <select class="form-control" id="subjenis_pangan_id" name="subjenis_pangan_id">
            <option value="1" {{ request()->get('subjenis_pangan_id') == 1 ? 'selected' : '' }}>Bawang Merah</option>
            @foreach($subjenisPangan as $subjenispangan)
                <option value="{{ $subjenispangan->id }}" {{ request()->get('subjenis_pangan_id') == $subjenispangan->id ? 'selected' : '' }}>
                    {{ $subjenispangan->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4 d-flex align-items-end">
        <button class="btn btn-primary" id="filterButton">Filter</button>
    </div>

    <div class="col-md-4 d-flex align-items-end justify-content-end">
        <a href="{{ route('export.laporan.pangan', ['pasar_id' => request()->get('pasar_id'), 'subjenis_pangan_id' => request()->get('subjenis_pangan_id')]) }}" class="btn btn-success">Export to Excel</a>
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
                                <th class="wd-20p border-bottom-0">Gambar</th>
                                <th class="wd-20p border-bottom-0">Jenis Pangan</th>
                                <th class="wd-20p border-bottom-0">Nama Pangan</th>
                                <th class="wd-20p border-bottom-0">Tanggal</th>
                                <th class="wd-20p border-bottom-0">Stok</th>
                                <th class="wd-20p border-bottom-0">Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $totalStok = 0;
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
                                <td>
                                    @if ($data->jenis_pangan)
                                    <img src="{{ asset('storage/' . $data->jenis_pangan->gambar) }}"
                                        alt="{{ $data->name }}" class="img-fluid" style="max-width: 100px;">
                                    @else
                                    <span>No Image</span>
                                    @endif
                                </td>
                                <td>{{ $data->jenis_pangan ? $data->jenis_pangan->name : 'Jenis Pangan Tidak Ditemukan' }}</td>
                                <td>{{ $data->subjenis_pangan ? $data->subjenis_pangan->name : 'Subjenis Pangan Tidak Ditemukan' }}</td>
                                <td>{{ $data->date }}</td>
                                <td>{{ formatRibuan($data->stok) }}</td>
                                <td>{{ formatRibuan($data->harga) }}</td>
                            </tr>
                            @php
                            $totalStok += $data->stok;
                            $totalHarga += $data->harga;
                            @endphp
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th></th>
                                <th></th>
                                <th colspan="5" class="text-center">Total</th>
                                <th>{{ formatRibuan($totalStok) }}</th>
                                <th>{{ formatRibuan($totalHarga) }}</th>
                            </tr>
                            @if ($groupedData && count($groupedData) > 0)
                            @php
                                $defaultSubjenisPanganId = 1; // Ganti dengan ID subjenis pangan default yang Anda inginkan
                                $filteredGroupedData = $groupedData->where('subjenis_pangan_id', $defaultSubjenisPanganId);
                                $averagePrice = $filteredGroupedData->avg('rata_rata_harga');
                            @endphp
                            @if ($filteredGroupedData->count() > 0)
                            <tr>
                                <th></th>
                                <th colspan="3" class="text-center">Rata-rata Harga Pangan</th>
                                <th></th>
                                <td>
                                    @foreach ($filteredGroupedData as $group)
                                        {{ $group->subjenis_pangan->name }}
                                    @endforeach
                                </td>
                                <th></th>
                                <th></th>
                                <td>
                                    @foreach ($filteredGroupedData as $group)
                                        {{ formatRibuan($averagePrice) }}
                                    @endforeach
                                </td>
                            </tr>
                            @else
                                <tr>
                                    <td colspan="5" class="text-center">Data rata-rata harga tidak tersedia untuk subjenis pangan ini.</td>
                                </tr>
                            @endif
                        @endif
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
