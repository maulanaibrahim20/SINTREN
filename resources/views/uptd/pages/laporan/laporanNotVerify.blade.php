@extends('index')
@section('title', 'Laporan Belum Diverifikasi')
@section('content')
    <div class="page-header d-sm-flex d-block">
        <ol class="breadcrumb mb-sm-0 mb-3">
            <!-- breadcrumb -->
            <li class="breadcrumb-item"><a href="{{ url('/uptd/dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Laporan Belum Diverifikasi</li>
        </ol><!-- End breadcrumb -->
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Data Laporan Yang Belum Diverifikasi</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-nowrap border-bottom" id="responsive-datatable">
                            <thead>
                                <tr>
                                    <th class="wd-15p border-bottom-0">No</th>
                                    <th class="wd-15p border-bottom-0">Jenis Tanaman</th>
                                    <th class="wd-20p border-bottom-0">Jenis Bantuan</th>
                                    <th class="wd-20p border-bottom-0">Jenis Input</th>
                                    <th class="wd-20p border-bottom-0 text-center">Status Verifikasi</th>
                                    <th class="wd-20p border-bottom-0">Nilai</th>
                                    <th class="wd-20p border-bottom-0 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($laporanBelumDiverifikasi as $data)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        @if ($data->laporanPadi)
                                            <td>{{ $data->laporanPadi->padi->name }}</td>
                                            <td>{{ $data->laporanPadi->jenis_bantuan }}</td>
                                            <td>{{ $data->laporanPadi->tipe_data }}</td>
                                        @elseif($data->laporanPalawija)
                                            <td>{{ $data->laporanPalawija->palawija->name }}</td>
                                            <td>{{ $data->laporanPalawija->jenis_bantuan }}</td>
                                            <td>{{ $data->laporanPalawija->tipe_data }}</td>
                                        @endif
                                        <td class="text-center">
                                            @if ($data->status == 'tolak')
                                                <span class="badge bg-danger me-1 my-1">Tolak</span>
                                            @elseif($data->status == 'tunggu')
                                                <span class="badge bg-warning me-1 my-1">Tunggu</span>
                                            @elseif($data->status == 'terima')
                                                <span class="badge bg-success me-1 my-1">Terima</span>
                                            @endif
                                        </td>
                                        @if ($data->laporanPalawija)
                                            <td>{{ $data->laporanPalawija->nilai }} Hektar</td>
                                        @elseif ($data->laporanPadi)
                                            <td>{{ $data->laporanPadi->nilai }} Hektar</td>
                                        @endif
                                        <td class="text-center">
                                            <form action="{{ url('/uptd/laporanNotVerify/changeStatus/' . $data->id) }}"
                                                method="POST" style="display: inline;">
                                                @csrf
                                                <button class="btn btn-success" name="status" value="terima">
                                                    <i class="fa fa-check"></i>Terima
                                                </button>
                                                <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                                    data-bs-target="#modalTolak{{ $data->id }}">
                                                    <i class="fa fa-times"></i>Tolak
                                                </button>
                                            </form>

                                            <!-- Modal -->
                                            <div class="modal fade" id="modalTolak{{ $data->id }}" tabindex="-1"
                                                aria-labelledby="modalTolakLabel{{ $data->id }}" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="modalTolakLabel{{ $data->id }}">Tolak
                                                                Laporan</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <form
                                                            action="{{ url('/uptd/laporanNotVerify/changeStatus/' . $data->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label for="catatan" class="form-label">Catatan</label>
                                                                    <textarea class="form-control" id="catatan" name="catatan" rows="3"></textarea>
                                                                </div>
                                                                <input type="hidden" name="status" value="tolak">
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Batal</button>
                                                                <button type="submit" class="btn btn-danger">Tolak</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
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
