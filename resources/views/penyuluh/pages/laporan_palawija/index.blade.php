@extends('index')
@section('title', 'Laporan Palawija')
@section('content')
    <div class="page-header d-sm-flex d-block">
        <ol class="breadcrumb mb-sm-0 mb-3">
            <!-- breadcrumb -->
            <li class="breadcrumb-item"><a href="{{ url('/penyuluh/dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Laporan Palawija</li>
        </ol><!-- End breadcrumb -->
        <div class="ms-auto">
            <div>
                <a href="{{ url('/penyuluh/create/laporan_palawija/create') }}" class="btn bg-primary-transparent"
                    data-bs-toggle="tooltip" title="Add New Laporan Palawija" data-bs-placement="bottom">
                    <span>
                        <i class="fa fa-plus"></i>
                    </span>
                    Tambah Laporan Palawija
                </a>
            </div>
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
                    <h3 class="card-title">Data Laporan Luas Tanaman Palawija</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-nowrap border-bottom" id="responsive-datatable">
                            <thead>
                                <tr>
                                    <th class="wd-15p border-bottom-0">No</th>
                                    <th class="wd-15p border-bottom-0">Desa</th>
                                    <th class="wd-15p border-bottom-0">Kecamatan</th>
                                    <th class="wd-20p border-bottom-0">Nama Pengumpul</th>
                                    <th class="wd-20p border-bottom-0">Status</th>
                                    <th class="wd-20p border-bottom-0">Tanggal</th>
                                    <th class="wd-20p border-bottom-0 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($palawija as $data)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $data->desa->name }}</td>
                                        <td>{{ $data->kecamatan->name }}</td>
                                        <td>{{ $data->nama_pengumpul }}</td>
                                        <td>
                                            @if ($data->status == 'terkirim')
                                                <span class="badge bg-success-transparent text-warning fw-semibold">Terkirim
                                                </span>
                                            @elseif ($data->status == '_terkirim')
                                                <span class="badge bg-danger-transparent text-danger fw-semibold">
                                                    Terkirim
                                                </span>
                                            @endif
                                        </td>
                                        <td>{{ $data->created_at }}</td>
                                        <td class="text-center">
                                            <a href="{{ url('/penyuluh/create/laporan_palawija/' . $data->id . '/edit') }}"
                                                class="btn btn-warning"><i class="fa fa-edit"></i></a>
                                            <a href="{{ url('/penyuluh/create/laporan_palawija/' . $data->id) }}"
                                                class="btn btn-info">
                                                <i class="ti ti-eye"></i>
                                            </a>
                                            @if ($data->status == 'terkirim')
                                            @elseif($data->status == '_terkirim')
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#largeModal{{ $data->id }}">
                                                    <i class="fa fa-paper-plane"></i>
                                                </button>
                                            @endif
                                            <form id="deleteForm{{ $data->id }}"
                                                action="{{ url('/penyuluh/create/laporan_palawija/' . $data->id) }}"
                                                style="display: inline;" method="POST">
                                                @method('DELETE')
                                                @csrf
                                                <button type="button" class="btn btn-danger deleteBtn"
                                                    data-id="{{ $data->id }}"><i class="ti ti-trash"></i></button>
                                            </form>
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

    {{-- start modal --}}
    @foreach ($detailLaporan as $item)
        <div id="largeModal{{ $item->id }}" class="modal fade">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content ">
                    <div class="modal-header pd-x-20">
                        <h6 class="modal-title">Detail Data Untuk Dikirimkan</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <form action="{{ url('/penyuluh/create/laporan_palawija/kirim') }}" enctype="multipart/form-data"
                        method="post">
                        @csrf
                        <input type="hidden" name="id" value="{{ $item->id }}">
                        <div class="modal-body pd-20">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-4">Laporan Palawija</div>
                                    <div class="table-responsive">
                                        <table class="table table-striped" style="width: 100%">
                                            <tr>
                                                <td class="text-right">Jenis Lahan</td>
                                                <td>:</td>
                                                <td>
                                                    {{ $item->laporanPalawija->jenis_lahan }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-right">Jenis Bantuan</td>
                                                <td>:</td>
                                                <td>
                                                    {{ $item->jenis_bantuan }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-right">Nama Pengumpul</td>
                                                <td>:</td>
                                                <td>
                                                    {{ $item->laporanPalawija->nama_pengumpul }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-right">Desa</td>
                                                <td>:</td>
                                                <td>
                                                    {{ $item->laporanPalawija->desa->name }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-right">kecamatan</td>
                                                <td>:</td>
                                                <td>
                                                    {{ $item->laporanPalawija->kecamatan->name }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-right">Jenis Palawija</td>
                                                <td>:</td>
                                                <td>
                                                    {{ $item->jenisPalawija->name }}
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="table-responsive">
                                        <table class="table table-bordered text-nowrap border-bottom"
                                            id="responsive-datatable">
                                            <tbody>
                                                <tr>
                                                    <td class="wd-15p border-bottom-0">Tanaman Akhir Bulan Lalu</td>
                                                    <td>{{ $item->tanaman_akhir_bulan_lalu }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="wd-15p border-bottom-0">Panen</td>
                                                    <td>{{ $item->panen }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="wd-15p border-bottom-0">Panen Muda</td>
                                                    <td>{{ $item->panen_muda }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="wd-10p border-bottom-0">Panen Pakan Ternak</td>
                                                    <td>{{ $item->panen_pakan_ternak }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="wd-10p border-bottom-0">Tanam</td>
                                                    <td>{{ $item->tanam }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="wd-10p border-bottom-0">Puso/Rusak</td>
                                                    <td>{{ $item->puso_rusak }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="wd-10p border-bottom-0">Tanaman Akhir Bulan Laporan
                                                    </td>
                                                    <td>{{ $item->tanaman_akhir_bulan_laporan }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary br-7">Kirim</button>
                                <button type="button" class="btn btn-secondary br-7" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </form>
                </div><!-- modal-dialog -->
            </div>
        </div>
    @endforeach
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
    </script>
@endsection
