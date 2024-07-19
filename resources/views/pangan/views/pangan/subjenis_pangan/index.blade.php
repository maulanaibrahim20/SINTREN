@extends('index')
@section('title', 'Subjenis Pangan')
@section('content')
<div class="page-header d-sm-flex d-block">
    <ol class="breadcrumb mb-sm-0 mb-3">
        <!-- breadcrumb -->
        <li class="breadcrumb-item"><a href="{{ url('/pangan/dashboard') }}">{{ $breadcrumb }}</a></li>
        <li class="breadcrumb-item active">{{ $breadcrumb_active }}</li>
    </ol><!-- End breadcrumb -->
    <div class="ms-auto">
        <div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCenter">
                <span>
                    <i class="fa fa-plus"></i>
                </span>
                {{ $button_create }}
            </button>
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
                                <th class="wd-15p border-bottom-0">No.</th>
                                <th class="wd-15p border-bottom-0">Gambar</th>
                                <th class="wd-15p border-bottom-0">Jenis Pangan</th>
                                <th class="wd-15p border-bottom-0">Subjenis Pangan</th>
                                <th class="text-center wd-10p border-bottom-0">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($subjenisPangan as $data)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    @if ($data->jenis_pangan && $data->jenis_pangan->gambar)
                                    <img src="{{ asset('storage/' . $data->jenis_pangan->gambar) }}" alt="{{ $data->jenis_pangan->name }}" class="img-fluid" style="max-width: 100px;">
                                    @else
                                    <span>No Image</span>
                                    @endif
                                </td>
                                <td>{{ $data->jenis_pangan->name }}</td>
                                <td>{{ $data->name }}</td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                    data-bs-target="#modalCenter1{{ $data->id }}"><i
                                    class="fa fa-edit"></i></button>
                                    <form id="deleteForm{{ $data->id }}"
                                        action="{{ url('/pangan/create/subjenis_pangan/' . $data->id) }}"
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

{{-- start modal tambah Subjenis Pangan --}}
<div class="modal fade" id="modalCenter" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="{{ url('/pangan/create/subjenis_pangan') }}" enctype="multipart/form-data" method="post">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">Tambah Subjenis Pangan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="col mb-3">
                        <label for="jenis_pangan_id" class="form-label">Jenis Pangan</label>
                        <select name="jenis_pangan_id" class="form-control">
                            @foreach ($jenisPangan as $jp)
                            <option value="{{ $jp->id }}">{{ $jp->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col mb-3">
                        <label for="name" class="form-label">Nama Subjenis Pangan</label>
                        <input type="text" name="name" class="form-control"
                        placeholder="Masukkan Nama Subjenis Pangan" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- end modal tambah Subjenis Pangan --}}

{{-- start modal edit Subjenis Pangan --}}
@foreach ($subjenisPangan as $item)
    <div class="modal fade" id="modalCenter1{{ $item->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="{{ url('pangan/create/subjenis_pangan/' . $item->id) }}" enctype="multipart/form-data" method="post">
                    @method('PUT')
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCenterTitle">Edit Subjenis Pangan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="col mb-3">
                            <label for="jenis_pangan_id" class="form-label">Jenis Pangan</label>
                            <select name="jenis_pangan_id" class="form-control">
                                @foreach ($jenisPangan as $jp)
                                    <option value="{{ $jp->id }}" {{ $item->jenis_pangan_id == $jp->id ? 'selected' : '' }}>
                                        {{ $jp->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col mb-3">
                            <label for="name" class="form-label">Nama Subjenis Pangan</label>
                            <input type="text" value="{{ $item->name }}" name="name" class="form-control" placeholder="Masukkan Nama Subjenis Pangan" />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

    {{-- end modal edit Subjenis Pangan --}}

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
