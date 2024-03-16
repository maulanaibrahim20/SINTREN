@extends('index')
@section('title', 'Laporan Padi | Penyuluh')
@section('content')
    <div class="page-header d-sm-flex d-block">
        <ol class="breadcrumb mb-sm-0 mb-3">
            <!-- breadcrumb -->
            <li class="breadcrumb-item"><a href="{{ url('/penyuluh/dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Laporan Padi</li>
        </ol><!-- End breadcrumb -->
        <div class="ms-auto">
            <div>
                <a href="{{ url('/penyuluh/create/laporan_padi/create') }}" class="btn bg-primary-transparent"
                    data-bs-toggle="tooltip" title="Add New User" data-bs-placement="bottom">
                    <span>
                        <i class="fa fa-plus"></i>
                    </span>
                    Tambah Laporan Padi
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
                    <h3 class="card-title">Data Laporan Luas Tanaman Padi</h3>
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
                                    <th class="wd-20p border-bottom-0">Tanggal</th>
                                    <th class="wd-20p border-bottom-0 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($padi as $data)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $data->desa->name }}</td>
                                        <td>{{ $data->kecamatan->name }}</td>
                                        <td>{{ $data->nama_pengumpul }}</td>
                                        <td>{{ $data->created_at }}</td>
                                        <td class="text-center">
                                            <a href="{{ url('/penyuluh/create/laporan_padi/' . $data->id . '/edit') }}"
                                                class="btn btn-warning"><i class="fa fa-edit"></i></a>
                                            <a href="{{ url('/penyuluh/create/laporan_padi/' . $data->id) }}"
                                                class="btn btn-primary">
                                                <i class="ti ti-eye"></i></a>
                                            <form id="deleteForm{{ $data->id }}"
                                                action="{{ url('/penyuluh/create/laporan_padi/' . $data->id) }}"
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
