@extends('index')
@section('title', 'View Pengguna Penyuluh')
@section('content')
    <div class="page-header d-sm-flex d-block">
        <ol class="breadcrumb1 br-7">
            <li class="breadcrumb-item1"><a href="{{ url('/operator/dashboard') }}">{{ $breadcrumb }}</a></li>
            <li class="breadcrumb-item1"><a href="{{ url('/operator/user/penyuluh') }}">{{ $breadcrumb_1 }}</a></li>
            <li class="breadcrumb-item1 active">{{ $breadcrumb_active }}</li>
        </ol>
    </div>
    <div class="row">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detail User : {{ $user->user->name }}</h3>
                </div>
                <div class="card-body">
                    <table class="table table-striped" style="width: 100%">
                        <tr>
                            <td class="text-right">Nama</td>
                            <td>:</td>
                            <td>
                                {{ $user->user->name }}
                            </td>
                        </tr>
                        <tr>
                            <td class="text-right">Kecamatan</td>
                            <td>:</td>
                            <td><span class="badge bg-primary">{{ $user->kecamatan->name }}</span></td>
                        </tr>
                        <tr>
                            <td class="text-right">Username</td>
                            <td>:</td>
                            <td>
                                {{ $user->user->username }}
                            </td>
                        </tr>
                        <tr>
                            <td class="text-right">Email</td>
                            <td>:</td>
                            <td>
                                {{ $user->user->email }}
                            </td>
                        </tr>
                        <tr>
                            <td class="text-right">Role</td>
                            <td>:</td>
                            <td>
                                {{ $user->user->getAkses->name }}
                            </td>
                        </tr>
                        <tr>
                            <td class="text-right">Alamat</td>
                            <td>:</td>
                            <td>
                                {{ $user->alamat }}
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="card-footer">
                    <a href="{{ url('/operator/user/penyuluh') }}" class="btn btn-warning">
                        <i class="fa fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Gambar</h3>
                </div>
                <div class="card-body">
                    <div class="">
                        <div class="d-flex overflow-visible">
                            <div class="card-recent-post cover-image">
                                <img src="{{ url('/assets') }}/images/photos/9.jpg" style="height: 200px; width: auto"
                                    class="br-7" alt="image">
                            </div>
                            <div class="ps-3 flex-column">
                                <span class="badge bg-primary me-1 mb-1 mt-1">{{ $user->user->name }}</span>
                                <h6 class="fw-semibold"><a href=""> Role
                                        {{ $user->user->getAkses->name }}</a>
                                </h6>
                                <div class="text-muted-dark">{{ $user->user->alamat }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
