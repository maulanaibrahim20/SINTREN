@extends('index')
@section('title', 'Role | Operator')
@section('content')
    <div class="page-header d-sm-flex d-block">
        <ol class="breadcrumb mb-sm-0 mb-3">
            <!-- breadcrumb -->
            <li class="breadcrumb-item"><a href="{{ url('/operator/dashbord') }}">Dashboard</a></li>
            <li class="breadcrumb-item" aria-current="page">Master</li>
            <li class="breadcrumb-item active" aria-current="page">Role</li>
        </ol><!-- End breadcrumb -->
        {{-- <div class="ms-auto">
            <div>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCenter">
                    <span>
                        <i class="fa fa-plus"></i>
                    </span>
                    Add New Role
                </button>
            </div>
        </div> --}}
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
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($role as $data)
                                    <tr>
                                        <td>{{ $data->id }}</td>
                                        <td>{{ $data->name }}</td>
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
