@extends('index')
@section('title', 'View Detail Palawija')
@section('content')
    <div class="page-header d-sm-flex d-block">
        <ol class="breadcrumb mb-sm-0 mb-3">
            <!-- breadcrumb -->
            <li class="breadcrumb-item"><a href="{{ url('/operator/user/pertanian') }}">{{ $breadcrumb }}</a></li>
            <li class="breadcrumb-item" aria-current="page">{{ $breadcrumb_1 }}</li>
            <li class="breadcrumb-item active" aria-current="page">{{ $breadcrumb_active }}</li>
        </ol><!-- End breadcrumb -->
    </div>
    <div class="row">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detail Palawija : {{ $palawija->name }}</h3>
                </div>
                <div class="card-body">
                    <table class="table table-striped" style="width: 100%">
                        <tr>
                            <td class="text-right">Nama</td>
                            <td>:</td>
                            <td>
                                {{ $palawija->name }}
                            </td>
                        </tr>
                        <tr>
                            <td class="text-right">Kategori</td>
                            <td>:</td>
                            <td>
                                {{ $palawija->kategori->name }}
                            </td>
                        </tr>
                        <tr>
                            <td class="text-right">Deskripsi</td>
                            <td>:</td>
                            <td>
                                {{ $palawija->description }}
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="card-footer">
                    <a href="{{ url('/operator/tanaman/palawija') }}" class="btn btn-warning">
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
                            <a href="blog-details.html" class="card-recent-post cover-image">
                                <img src="{{ asset('' . $palawija->gambar) }}" class="br-7" alt="image">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
