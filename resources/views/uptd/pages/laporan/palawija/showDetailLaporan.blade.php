@extends('index')
@section('title', 'Detail Laporan Palawija')
@section('content')
    <div class="page-header d-sm-flex d-block">
        <ol class="breadcrumb mb-sm-0 mb-3">
            <!-- breadcrumb -->
            <li class="breadcrumb-item"><a href="{{ url('/penyuluh/dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Laporan Palawija</li>
        </ol><!-- End breadcrumb -->
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Data Laporan Luas Tanaman Palawija Desa {{ $desa->desa->name }}</h3>
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
                                    <th class="wd-20p border-bottom-0 text-center">Status Verifikasi</th>
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
                                            {{ \Carbon\Carbon::parse($data->date)->translatedFormat('l, d F Y') }}
                                        </td>
                                        <td>{{ $data->jenis_lahan }}</td>
                                        <td class="text-center">
                                            @if ($data->verify)
                                                @if ($data->verify->status == 'tolak')
                                                    <span class="badge bg-danger me-1 my-1">Tolak</span>
                                                @elseif($data->verify->status == 'tunggu')
                                                    <span class="badge bg-warning me-1 my-1">Tunggu</span>
                                                @elseif($data->verify->status == 'terima')
                                                    <span class="badge bg-success me-1 my-1">Terima</span>
                                                @endif
                                                </form>
                                            @else
                                                <span class="badge bg-secondary me-1 my-1">Belum Diverifikasi</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a class="btn btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#largeModal{{ $data->id }}">
                                                <i class="ti ti-eye"></i>
                                            </a>
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

    @foreach ($showDesa as $itemShow)
        <div id="largeModal{{ $itemShow->id }}" class="modal fade">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content ">
                    <form action="{{ url('/uptd/laporan/palawija/changeStatus/' . $itemShow->id) }}" method="POST">
                        @csrf
                        <div class="modal-header pd-x-20">
                            <h6 class="modal-title">Detail Laporan Palawija</h6>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="table-responsive">
                                <table class="table table-striped" style="width: 100%">
                                    <tr>
                                        <td class="text-right">Tanggal Input</td>
                                        <td>:</td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($itemShow->date)->translatedFormat('l, d F Y') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-right">Jenis Lahan</td>
                                        <td>:</td>
                                        <td>
                                            {{ $itemShow->jenis_lahan }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-right">Nama Penyuluh</td>
                                        <td>:</td>
                                        <td>
                                            {{ $itemShow->user_id }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-right">Desa</td>
                                        <td>:</td>
                                        <td>
                                            {{ $itemShow->desa->name }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-right">Kecamatan</td>
                                        <td>:</td>
                                        <td>
                                            {{ $itemShow->kecamatan->name }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-right">Status</td>
                                        <td>:</td>
                                        <td>
                                            @if ($itemShow->verify)
                                                @if ($itemShow->verify->status == 'tolak')
                                                    <span class="badge bg-danger me-1 my-1">Tolak</span>
                                                @elseif($itemShow->verify->status == 'tunggu')
                                                    <span class="badge bg-warning me-1 my-1">Tunggu</span>
                                                @elseif($itemShow->verify->status == 'terima')
                                                    <span class="badge bg-success me-1 my-1">Terima</span>
                                                @endif
                                            @else
                                                <span class="badge bg-secondary me-1 my-1">Belum Diverifikasi</span>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            @if ($itemShow->verify->status == 'tunggu')
                                <div class="form-group mt-5">
                                    <label class="form-label">Catatan</label>
                                    <textarea name="catatan" class="form-control"></textarea> <!-- Ganti 'catatan' dengan nama field yang sesuai -->
                                </div>
                            @elseif ($itemShow->verify->status == 'tolak')

                            @elseif ($itemShow->verify->status == 'terima')
                            @endif
                        </div>
                        <div class="modal-footer">
                            @if ($itemShow->verify)
                                @if ($itemShow->verify->status == 'tolak')
                                @elseif($itemShow->verify->status == 'tunggu')
                                    <button class="btn btn-primary" type="submit" name="status"
                                        value="terima">Terima</button>
                                    <button class="btn btn-danger" type="submit" name="status"
                                        value="tolak">Tolak</button>
                                @elseif($itemShow->verify->status == 'terima')
                                @endif
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endsection
