@extends('index')
@section('title', 'Laporan Padi | Penyuluh')
@section('content')
    <div class="page-header d-sm-flex d-block">
        <ol class="breadcrumb mb-sm-0 mb-3">
            <!-- breadcrumb -->
            <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Laporan Padi</li>
        </ol><!-- End breadcrumb -->
        <div class="ms-auto">
            <div>
                <a href="{{ url('/penyuluh/create/laporan_padi/create') }}" class="btn bg-primary-transparent"
                    data-bs-toggle="tooltip" title="Add New User" data-bs-placement="bottom">
                    <span>
                        <i class="fa fa-plus"></i>
                    </span>
                    Add New User
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
                                    <th class="wd-15p border-bottom-0">Nama</th>
                                    <th class="wd-20p border-bottom-0">Email</th>
                                    <th class="wd-15p border-bottom-0">Role</th>
                                    <th class="text-center wd-10p border-bottom-0">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- @foreach ($users as $data)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $data->user->name }}</td>
                                        <td>{{ $data->user->email }}</td>
                                        <td>{{ $data->user->getAkses->name }}</td>
                                        <td class="text-center">
                                            <a href="{{ url('/operator/user/penyuluh/' . $data->id . '/edit') }}"
                                                class="btn btn-warning"><i class="fa fa-edit"></i></a>
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#modalCenter{{ $data->id }}">
                                                <i class="ti ti-eye"></i></button>
                                            <form id="deleteForm{{ $data->id }}"
                                                action="{{ url('/operator/user/penyuluh/' . $data->id) }}"
                                                style="display: inline;" method="POST">
                                                @method('DELETE')
                                                @csrf
                                                <button type="button" class="btn btn-danger deleteBtn"
                                                    data-id="{{ $data->id }}"><i class="ti ti-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
