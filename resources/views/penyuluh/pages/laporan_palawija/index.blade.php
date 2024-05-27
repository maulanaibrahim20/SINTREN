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
                                    <th class="wd-15p border-bottom-0">alamat</th>
                                    <th class="wd-20p border-bottom-0">jenis lahan</th>
                                    <th class="wd-20p border-bottom-0">Tanggal input</th>
                                    <th class="wd-20p border-bottom-0 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($palawija as $data)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
<<<<<<< HEAD
                                            @if ($data->status == 'terkirim')
                                                <span class="badge bg-success-transparent text-warning fw-semibold">Terkirim
                                                </span>
                                            @elseif ($data->status == '_terkirim')
                                                <span class="badge bg-danger-transparent text-danger fw-semibold">
                                                    Terkirim
                                                </span>
                                            @endif
=======
                                            <div class="d-flex contact-image">
                                                <div class="d-flex mt-1 flex-column ms-2">
                                                    <h6 class="mb-0 fs-14 fw-semibold text-dark">Kecamatan : <span
                                                            class="badge bg-primary me-1 my-1">{{ $data->kecamatan->name }}</span>
                                                    </h6>
                                                    <span class="fs-12 text-muted">Desa :  <span
                                                        class="badge bg-info me-1 my-1">{{ $data->desa->name }}</span></span>
                                                </div>
                                            </div>
>>>>>>> ac05a750b36994399cfd86af854b2d7ca9ef60b8
                                        </td>
                                        <td>{{ $data->jenis_lahan }}</td>
                                        <td>{{ $data->date }}</td>
                                        <td class="text-center">
                                            <a href="{{ url('/penyuluh/create/laporan_palawija/' . $data->id . '/edit') }}"
                                                class="btn btn-warning"><i class="fa fa-edit"></i></a>
                                            <a href="{{ url('/penyuluh/create/laporan_palawija/' . $data->id) }}"
                                                class="btn btn-info">
                                                <i class="ti ti-eye"></i>
                                            </a>
<<<<<<< HEAD
                                            @if ($data->status == 'terkirim')
                                            @elseif($data->status == '_terkirim')
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#largeModal{{ $data->id }}">
                                                    <i class="fa fa-paper-plane"></i>
                                                </button>
                                            @endif
=======
>>>>>>> ac05a750b36994399cfd86af854b2d7ca9ef60b8
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
