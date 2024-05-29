@extends('index')
@section('title', 'Data Stok Pangan | Pangan')
@section('content')
    <div class="page-header d-sm-flex d-block">
        <ol class="breadcrumb mb-sm-0 mb-3">
            <!-- breadcrumb -->
            <li class="breadcrumb-item1"><a href="{{ url('/pangan/dashboard') }}">{{ $breadcrumb }}</a></li>
            <li class="breadcrumb-item1 active">{{ $breadcrumb_active }}</li>
        </ol><!-- End breadcrumb -->
        <div class="ms-auto">
            <div>
                <a href="{{ url('/pangan/create/data_pangan') }}" class="btn bg-primary-transparent"
                    data-bs-toggle="tooltip" title="Add New User" data-bs-placement="bottom">
                    <span>
                        <i class="fa fa-plus"></i>
                    </span>
                    {{ $button_create }}
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
                    <h3 class="card-title">{{ $title }}</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-nowrap border-bottom" id="responsive-datatable">
                            <thead>
                                <tr>
                                    <th class="wd-15p border-bottom-0">No</th>
                                    <th class="wd-15p border-bottom-0">Status</th>
                                    {{--  <th class="wd-15p border-bottom-0">Nama Petugas</th> --}}
                                    <th class="wd-15p border-bottom-0">Pasar</th>
                                    <th class="wd-20p border-bottom-0">Nama Pangan</th>
                                    <th class="wd-20p border-bottom-0">Tanggal</th>
                                    {{-- <th class="wd-20p border-bottom-0">Kategori Pangan</th> --}}
                                    <th class="wd-20p border-bottom-0">Kebutuhan(Ton)</th>
                                    <th class="wd-20p border-bottom-0">Ketersediaan(Ton)</th>
                                    <th class="wd-20p border-bottom-0">Neraca(Ton)</th>
                                    <th class="wd-20p border-bottom-0">Harga(Rp/Kg)</th>
                                    <th class="wd-20p border-bottom-0 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($datapangan as $data)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="d-flex contact-image">
                                                <div class="d-flex mt-1 flex-column ms-2">
                                                    <h6 class="mb-0 fs-14 fw-semibold text-dark">Pasar : <span
                                                            class="badge bg-primary me-1 my-1">{{ $data->pasar->name }}</span>
                                                    </h6>
                                                    {{-- <span class="fs-12 text-muted">Desa : <span
                                                            class="badge bg-info me-1 my-1">{{ $data->desa->name }}</span></span> --}}
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $data->kategori_pangan }}</td>
                                        <td>{{ $data->date }}</td>
                                        <td class="text-center">
                                            <a href="{{ url('/pangan/create/data_pangan/' . $data->id . '/edit') }}"
                                                class="btn btn-warning"><i class="fa fa-edit"></i></a>
                                            <a href="{{ url('/pangan/create/data_pangan/' . $data->id) }}"
                                                class="btn btn-primary">
                                                <i class="ti ti-eye"></i></a>
                                            <form id="deleteForm{{ $data->id }}"
                                                action="{{ url('/pangan/create/data_pangan/' . $data->id) }}"
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
