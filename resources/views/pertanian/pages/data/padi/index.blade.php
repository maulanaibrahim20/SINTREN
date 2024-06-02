@extends('index')
@section('title', 'Data Padi')
@section('content')
    <div class="page-header d-sm-flex d-block">
        <ol class="breadcrumb mb-sm-0 mb-3">
            <!-- breadcrumb -->
            <li class="breadcrumb-item"><a href="{{ url('/penyuluh/dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Laporan Padi</li>
        </ol><!-- End breadcrumb -->
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
                    <div class="dropdown ms-auto">
                        <form style="display: inline;" action="{{ url('/pertanian/data_padi/exportPdf') }}" method="get"
                            target="_blank">
                            @if (session('filterKecamatan' && 'filterDesa'))
                                <input type="hidden" name="filterKecamatan" value="{{ session('filterKecamatan') }}">
                                <input type="hidden" name="filterDesa" value="{{ session('filterDesa') }}">
                                <input type="hidden" name="filterDate" value="{{ session('filterDate') }}">
                            @endif
                            <button class="btn btn-outline-default fw-bold text-primary" type="submit">
                                <i class="fa fa-print fw-semibold"></i> Export PDF
                            </button>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <form action="{{ url('/pertanian/data_padi/filter') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <div class="row">
                                    <label class="form-label col-sm-1" style="margin-top: 5px;"> Filter : </label>
                                    <div class="col-md-2">
                                        <select name="filterKecamatan" class="form-control select2 form-select"
                                            data-placeholder="Pilih Kecamatan" id="filterKecamatan" required>
                                            <option value="">- Pilih Kecamatan -</option>
                                            @foreach ($filterKecamatan as $kec)
                                                <option value="{{ $kec->id }}"
                                                    @if (session('filtering') && session('filtering')->contains('kecamatan_id', $kec->id)) selected @endif>
                                                    {{ $kec->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <select name="filterDesa" class="form-control select2 form-select"
                                            data-placeholder="Pilih Desa" id="filterDesa" required>
                                            <option value="">- Pilih Desa -</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="input-group">
                                            <div class="input-group-text">
                                                <i class="typcn typcn-calendar-outline tx-24 lh--9 op-6"></i>
                                            </div>
                                            <input type="text" name="dateRange" class="form-control" id="daterange"
                                                placeholder="Pilih Range Tanggal" value="{{ session('dateRange') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-1">
                                        <input type="submit" class="btn btn-pill btn-primary" value="FILTER">
                                    </div>
                                </div>
                            </div>
                        </form>
                        <table class="table table-bordered text-nowrap border-bottom" id="responsive-datatable">
                            <thead>
                                <tr>
                                    <th class="wd-15p border-bottom-0">No</th>
                                    <th class="wd-15p border-bottom-0">Alamat</th>
                                    <th class="wd-15p border-bottom-0">Jenis Lahan</th>
                                    <th class="wd-20p border-bottom-0">Tanggal Input</th>
                                    <th class="wd-20p border-bottom-0 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (empty(session('filtering')))
                                    @foreach ($laporanPadi as $data)
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
                                            <td>{{ $data->jenis_lahan }}</td>
                                            <td>{{ $data->date }}</td>
                                            <td class="text-center">
                                                <a href="{{ url('/penyuluh/create/laporan_padi/' . $data->id . '/edit') }}"
                                                    class="btn btn-warning"><i class="fa fa-edit"></i></a>
                                                <a href="{{ url('/penyuluh/create/laporan_padi/' . $data->id) }}"
                                                    class="btn btn-primary">
                                                    <i class="ti ti-eye"></i></a>
                                                <form id="deleteForm{{ $data->id }}"
                                                    action="{{ url('/penyuluh/create/laporan_padi/' . $data->id) }}"
                                                    style="display: inline;" method="POST">
                                                    @method('DELETE')
                                                    @csrf
                                                    <button type="button" class="btn btn-danger deleteBtn"
                                                        data-id="{{ $data->id }}"><i class="ti ti-trash"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    @foreach (session('filtering') as $filter)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <div class="d-flex contact-image">
                                                    <div class="d-flex mt-1 flex-column ms-2">
                                                        <h6 class="mb-0 fs-14 fw-semibold text-dark">Kecamatan : <span
                                                                class="badge bg-primary me-1 my-1">{{ $filter->kecamatan->name }}</span>
                                                        </h6>
                                                        <span class="fs-12 text-muted">Desa : <span
                                                                class="badge bg-info me-1 my-1">{{ $filter->desa->name }}</span></span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $filter->jenis_lahan }}</td>
                                            <td>{{ $filter->date }}</td>
                                            <td class="text-center">
                                                <a href="{{ url('/penyuluh/create/laporan_padi/' . $filter->id . '/edit') }}"
                                                    class="btn btn-warning"><i class="fa fa-edit"></i></a>
                                                <a href="{{ url('/penyuluh/create/laporan_padi/' . $filter->id) }}"
                                                    class="btn btn-primary">
                                                    <i class="ti ti-eye"></i></a>
                                                <form id="deleteForm{{ $filter->id }}"
                                                    action="{{ url('/penyuluh/create/laporan_padi/' . $filter->id) }}"
                                                    style="display: inline;" method="POST">
                                                    @method('DELETE')
                                                    @csrf
                                                    <button type="button" class="btn btn-danger deleteBtn"
                                                        data-id="{{ $filter->id }}"><i
                                                            class="ti ti-trash"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $("#filterKecamatan").change(function() {
                let filterKecamatan = $("#filterKecamatan").val();
                $.ajax({
                    url: "{{ url('/ambil_desa_filtering') }}",
                    type: "GET",
                    data: {
                        kecamatan: filterKecamatan
                    },
                    success: function(res) {
                        $("#filterDesa").html(res);
                        let selectedDesa = "{{ session('filtering.desa_id') }}";
                        if (selectedDesa) {
                            $("#filterDesa").val(selectedDesa).trigger('change');
                        }
                    },
                    error: function(error) {
                        alert('Gagal mengambil data desa.');
                    }
                });
            });

            let selectedKecamatan = "{{ session('filtering.kecamatan_id') }}";
            if (selectedKecamatan) {
                $("#filterKecamatan").val(selectedKecamatan).trigger('change');
            }
        });
    </script>
@endsection
