@extends('index')
@section('title', 'Data Stok Pangan | Pangan')
@section('content')
    <div class="page-header d-sm-flex d-block">
        <ol class="breadcrumb mb-sm-0 mb-3">
            <!-- breadcrumb -->
            <li class="breadcrumb-item1"><a href="{{ url('/pangan/dashboard') }}">{{ $breadcrumb }}</a></li>
            <li class="breadcrumb-item1 active">{{ $breadcrumb_active }}</li>
        </ol><!-- End breadcrumb -->
        {{-- <div class="ms-auto">
            <div>
                <a href="{{ url('/pangan/create/data_pangan/create') }}" class="btn bg-primary-transparent">
                    <span>
                        <i class="fa fa-plus"></i>
                    </span>
                    {{ $button_create }}
                </a>
            </div>
        </div> --}}
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
                                    <th class="wd-20p border-bottom-0 text-center">Action</th>
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
                                            @if ($data->status == '0')
                                                <span class="badge bg-danger-transparent text-danger fw-semibold">Belum Terkirim</span>
                                            @elseif ($data->status == '1')
                                                <span class="badge bg-success-transparent text-warning fw-semibold">Terkirim</span>
                                            @endif
                                        </td>
                                        <td>{{ $data->pasar ? $data->pasar->name : 'Pasar Tidak Ditemukan' }}</td>
                                        <td>{{ $data->name }}</td>
                                        <td>{{ $data->date }}</td>
                                        <td>{{ $data->kebutuhan }}</td>
                                        <td>{{ $data->ketersediaan }}</td>
                                        {{-- <td>{{ formatRibuan($data->neraca) }}</td> --}}
                                        <td>{{ $data->harga }} </td>
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

        $('.kirimBtn').on('click', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var statusForm = $('#statusForm' + id);

            Swal.fire({
                title: 'Anda yakin?',
                text: "Data akan dikirimkan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Kirimkan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    statusForm.submit();
                }
            });
        });

        // Filter button click event
        $('#filterButton').on('click', function() {
            var startDate = $('#start_date').val();
            var endDate = $('#end_date').val();
            var url = "{{ url('/pangan/create/data_pangan') }}?start_date=" + startDate + "&end_date=" + endDate;
            window.location.href = url;
        });
    </script>
@endsection
