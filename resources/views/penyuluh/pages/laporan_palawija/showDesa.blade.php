@extends('index')
@section('title', 'Detail Desa Laporan Padi')
@section('content')
    <div class="page-header d-sm-flex d-block">
        <ol class="breadcrumb mb-sm-0 mb-3">
            <!-- breadcrumb -->
            <li class="breadcrumb-item"><a href="{{ url('/penyuluh/dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Laporan Padi</li>
        </ol><!-- End breadcrumb -->
        <div class="ms-auto">
            <div>
                <a href="{{ url('/penyuluh/create/laporan_padi/create') }}" class="btn bg-primary-transparent">
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
                <div class="card-header">
                    <h3 class="card-title">Data Laporan Luas Tanaman Padi Desa {{ $desa->desa->name }}</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-nowrap border-bottom" id="responsive-datatable">
                            <thead>
                                <tr>
                                    <th class="wd-15p border-bottom-0">No</th>
                                    <th class="wd-15p border-bottom-0">Alamat</th>
                                    <th class="wd-20p border-bottom-0">Tanggal Input</th>
                                    <th class="wd-20p border-bottom-0">Jenis Lahan</th>
                                    <th class="wd-20p border-bottom-0">Verifikasi</th>
                                    <th class="wd-20p border-bottom-0 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($showDesa as $data)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="d-flex contact-image">
                                                <div class="d-flex mt-1 flex-column ms-2">
                                                    <h6 class="mb-0 fs-14 fw-semibold text-dark">Kecamatan : <span
                                                            class="badge bg-primary me-1 my-1">{{ $data->kecamatan->name }}</span>
                                                    </h6>
                                                    <span class="fs-12 text-muted">Desa : <span
                                                            class="badge bg-info me-1 my-1">{{ $data->desa->name }}</span></span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            {{ $data->date }}
                                        </td>
                                        <td>{{ $data->jenis_lahan }}</td>
                                        <td>
                                            @if ($data->verify)
                                                @if ($data->verify->status == 'tolak')
                                                    <span class="badge bg-danger me-1 my-1">Tolak</span>
                                                @elseif($data->verify->status == 'tunggu')
                                                    <span class="badge bg-success me-1 my-1">Tunggu</span>
                                                @elseif($data->verify->status == 'terima')
                                                    <span class="badge bg-primary me-1 my-1">Terima</span>
                                                @endif
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($data->verify->status == 'tunggu')
                                                <a href="{{ url('/penyuluh/create/laporan_palawija/' . $data->id . '/edit') }}"
                                                    class="btn btn-warning"><i class="fa fa-edit"></i></a>
                                                <a href="{{ url('/penyuluh/create/laporan_palawija/' . $data->id) }}"
                                                    class="btn btn-primary">
                                                    <i class="ti ti-eye"></i></a>
                                                <form id="deleteForm{{ $data->id }}"
                                                    action="{{ url('/penyuluh/create/laporan_palawija/' . $data->id) }}"
                                                    style="display: inline;" method="POST">
                                                    @method('DELETE')
                                                    @csrf
                                                    <button type="button" class="btn btn-danger deleteBtn"
                                                        data-id="{{ $data->id }}"><i class="ti ti-trash"></i></button>
                                                </form>
                                            @elseif($data->verify->status == 'tolak')
                                                <a href="{{ url('/penyuluh/create/laporan_palawija/' . $data->id . '/edit') }}"
                                                    class="btn btn-warning"><i class="fa fa-edit"></i></a>
                                                <a href="{{ url('/penyuluh/create/laporan_palawija/' . $data->id) }}"
                                                    class="btn btn-primary">
                                                    <i class="ti ti-eye"></i></a>
                                                <form id="deleteForm{{ $data->id }}"
                                                    action="{{ url('/penyuluh/create/laporan_palawija/' . $data->id) }}"
                                                    style="display: inline;" method="POST">
                                                    @method('DELETE')
                                                    @csrf
                                                    <button type="button" class="btn btn-danger deleteBtn"
                                                        data-id="{{ $data->id }}"><i class="ti ti-trash"></i></button>
                                                </form>
                                            @elseif($data->verify->status == 'terima')
                                                <p>Laporan Anda Sudah Diterima</p>
                                            @endif
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
