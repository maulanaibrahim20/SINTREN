@extends('index')
@section('title', 'Detail Penyuluh')
@section('content')
    <div class="page-header d-sm-flex d-block">
        <ol class="breadcrumb mb-sm-0 mb-3">
            <!-- breadcrumb -->
            <li class="breadcrumb-item"><a href="{{ url('/uptd/user/penyuluh') }}">{{ $breadcrumb }}
                </a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $breadcrumb_active }}</li>
        </ol><!-- End breadcrumb -->
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detail Penyuluh</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" style="width: 100%">
                            <tr>
                                <td class="text-right">Nama Penyuluh</td>
                                <td>:</td>
                                <td>
                                    {{ $show->user->name }}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-right">Alamat</td>
                                <td>:</td>
                                <td>
                                    {{ $show->alamat }}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-right">No Telepon</td>
                                <td>:</td>
                                <td>
                                    {{ $show->no_telp }}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-right">Dibuat Oleh</td>
                                <td>:</td>
                                <td>
                                    {{ $show->createBy->name }}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="card-header">
                    <h3 class="card-title">Detail Penugasan Penyuluh</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" style="width: 100%">
                            <tr>
                                <td class="text-right">Desa Penugasan</td>
                                <td>:</td>
                                @foreach ($penugasan as $penugasan_item)
                                    <td>
                                        <ul>
                                            <li>
                                                <span class="badge bg-primary">{{ $penugasan_item->desa->name }}</span>
                                            </li>
                                        </ul>
                                    </td>
                                @endforeach
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
