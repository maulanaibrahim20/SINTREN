@extends('index')

@section('title', 'Data Stok Pangan | Pangan')

@section('content')
<div class="page-header d-sm-flex d-block">
    <ol class="breadcrumb mb-sm-0 mb-3">
        <li class="breadcrumb-item1"><a href="{{ url('/pangan/dashboard') }}">{{ $breadcrumb }}</a></li>
        <li class="breadcrumb-item1 active">{{ $breadcrumb_active }}</li>
    </ol>
</div>
<!-- Filter Tanggal Mulai dan Akhir -->
<div class="row mb-3">
    <div class="col-md-2">
        <label for="start_date" class="form-label"><b>Tanggal Mulai</b></label>
        <input type="date" class="form-control" id="start_date" name="start_date" placeholder="Tanggal Mulai"
            value="{{ request()->get('start_date') ?? now()->format('Y-m-d') }}">
    </div>
    <div class="col-md-2">
        <label for="end_date" class="form-label"><b>Tanggal Akhir</b></label>
        <input type="date" class="form-control" id="end_date" name="end_date" placeholder="Tanggal Akhir"
            value="{{ request()->get('end_date') ?? now()->format('Y-m-d') }}">
    </div>

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

    <div class="col-md-2 d-flex align-items-end">
        <button class="btn btn-primary" id="filterButton">Filter</button>
    </div>
</div>

<!-- Tabel Data -->
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
                                <th class="wd-20p border-bottom-0">Stok (Kg)</th>
                                <th class="wd-20p border-bottom-0">Harga (Rp/Kg)</th>
                                <th class="wd-20p border-bottom-0 text-center">Action</th>
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
                                    <img src="{{ asset('storage/' . $data->jenis_pangan->gambar) }}" alt="{{ $data->name }}" class="img-fluid" style="max-width: 100px;">
                                    @else
                                    <span>No Image</span>
                                    @endif
                                </td>
                                <td>{{ $data->jenis_pangan ? $data->jenis_pangan->name : 'Jenis Pangan Tidak Ditemukan' }}</td>
                                <td>{{ $data->subjenis_pangan ? $data->subjenis_pangan->name : 'Subjenis Pangan Tidak Ditemukan' }}</td>
                                <td>{{ $data->date }}</td>
                                <td>{{ formatRibuan($data->stok) }}</td>
                                <td>{{ formatRibuan($data->harga) }}</td>
                                <td class="text-center">
                                    <a href="{{ url('/pangan/create/data_pangan/' . $data->id . '/edit') }}" class="btn btn-warning"><i class="fa fa-edit"></i></a>
                                    <a href="{{ url('/pangan/create/data_pangan/' . $data->id) }}" class="btn btn-primary"><i class="ti ti-eye"></i></a>
                                    <form id="deleteForm{{ $data->id }}" action="{{ url('/pangan/create/data_pangan/' . $data->id) }}" style="display: inline;" method="POST">
                                        @method('DELETE')
                                        @csrf
                                        <button type="button" class="btn btn-danger deleteBtn" data-id="{{ $data->id }}"><i class="ti ti-trash"></i></button>
                                    </form>
                                </td>
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
                                <th colspan="6" class="text-center">Total</th>
                                <th>{{ formatRibuan($totalStok) }}</th>
                                <th>{{ formatRibuan($totalHarga) }}</th>
                                <th></th>
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
        const startDate = document.getElementById('start_date').value;
        const endDate = document.getElementById('end_date').value;
        const pasarId = document.getElementById('pasar_id').value;

        const url = new URL(window.location.href);
        url.searchParams.set('start_date', startDate);
        url.searchParams.set('end_date', endDate);
        url.searchParams.set('pasar_id', pasarId);

        window.location.href = url.toString();
    });

</script>
@endsection
