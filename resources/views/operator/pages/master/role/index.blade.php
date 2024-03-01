@extends('index')
@section('title', 'Master Role | Operator')
@section('content')
    <div class="page-header d-sm-flex d-block">
        <ol class="breadcrumb mb-sm-0 mb-3">
            <!-- breadcrumb -->
            <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
            <li class="breadcrumb-item" aria-current="page">Data Pengguna</li>
            <li class="breadcrumb-item active" aria-current="page">Pengguna Penyuluh</li>
        </ol><!-- End breadcrumb -->
        <div class="ms-auto">
            <div>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCenter">
                    <span>
                        <i class="fa fa-plus"></i>
                    </span>
                    Add New Role
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
                                    <th class="wd-15p border-bottom-0">ID Role</th>
                                    <th class="wd-15p border-bottom-0">Nama</th>
                                    <th class="text-center wd-10p border-bottom-0">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($role as $data)
                                    <tr>
                                        <td>{{ $data->id }}</td>
                                        <td>{{ $data->name }}</td>
                                        <td class="text-center">
                                            <a href="{{ url('/operator/master/role/' . $data->id . '/edit') }}"
                                                class="btn btn-warning"><i class="fa fa-edit"></i></a>
                                            <form id="deleteForm{{ $data->id }}"
                                                action="{{ url('/operator/master/role/' . $data->id) }}"
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

    {{-- start modal tambah Jenis Padi --}}
    <div class="modal fade" id="modalCenter" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="{{ url('/operator/master/role') }}" method="post">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCenterTitle">Masukan Nama Role</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="col mb-3">
                            <label for="nameBasic" class="form-label">Nama</label>
                            <input type="text" name="name" class="form-control"
                                placeholder="Masukkan Nama Role/Peran" />
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
    {{-- end modal tambah Jenis Padi --}}
@endsection
