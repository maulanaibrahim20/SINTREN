@extends('index')
@section('title', 'Tambah Data Stok Pangan | Pangan')
@section('content')
    <div class="main-container container-fluid">
        <div class="page-header d-sm-flex d-block">
            <ol class="breadcrumb mb-sm-0 mb-3">
                <!-- breadcrumb -->
                <li class="breadcrumb-item1"><a href="{{ url('/pangan/dashboard') }}">{{ $breadcrumb }}</a></li>
                <li class="breadcrumb-item1 active">{{ $breadcrumb_active }}</li>
                <!-- End breadcrumb -->
            </ol>
        </div>
    </div>
    <div class="row row-cards">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $title }}</h3>
                </div>
                <form action="{{ url('/pangan/create/data_pangan') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="select2Basic" class="form-label">Pilih Tanggal</label>
                                    <div class="input-group">
                                        <div class="input-group-text">
                                            <i class="typcn typcn-calendar-outline tx-24 lh--9 op-6"></i>
                                        </div>
                                        <input type="text" class="form-control" name="date" id="date"
                                            placeholder="Pilih Tanggal">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="select2Basic" class="form-label">Pasar Penugasan</label>
                                    <select id="desa" class="form-control select2 form-select"
                                        aria-label="Default select example" data-placeholder="Pilih Desa" name="desa">
                                        <option value="">-- Pilih Pasar --</option>
                                        @foreach ($penugasanDesa as $item)
                                            <option value="{{ $item->desa_id }}">
                                                {{ $item->desa->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="select2Basic" class="form-label">Pilih Jenis Lahan</label>
                                    <select id="jenis_lahan" class="form-control select2 form-select"
                                        data-placeholder="Pilih Jenis Lahan" aria-label="Default select example"
                                        name="jenis_lahan">
                                        <option value="">-- Pilih --</option>
                                        <option value="lahan sawah">Lahan Sawah</option>
                                        <option value="non sawah">Bukan Sawah/Non Sawah</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="select2Basic" class="form-label">Pilih Jenis Bantuan</label>
                                    <select id="jenis_bantuan" class="form-control select2 form-select"
                                        data-placeholder="Pilih Jenis Bantuan" aria-label="Default select example"
                                        name="jenis_bantuan">
                                        <option value="">-- Pilih --</option>
                                        <option value="bantuan pemerintah">Bantuan Pemerintah</option>
                                        <option value="non bantuan pemerintah">Bukan Bantuan Pemerintah/Non Bantuan
                                            Pemerintah
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="select2Basic" class="form-label">Jenis Padi</label>
                                    <select id="jenis_padi" class="form-control select2 form-select"
                                        aria-label="Default select example" data-placeholder="Pilih Jenis Padi"
                                        name="jenis_padi">
                                        <option value="">-- Pilih Padi --</option>
                                        @foreach ($jenis_padi as $padi)
                                            <option value="{{ $padi['id'] }}">{{ $padi['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="select2Basic" class="form-label">Jenis Pengairan</label>
                                    <select id="jenis_pengairan" class="form-control select2 form-select"
                                        aria-label="Default select example" data-placeholder="Pilih Jenis Pengairan"
                                        name="jenis_pengairan">
                                        <option value="">-- Pilih Pengairan --</option>
                                        @foreach ($pengairan as $peng)
                                            <option value="{{ $peng['id'] }}">{{ $peng['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="select2Basic" class="form-label">Pilih Inputan</label>
                                    <select id="jenis_data" class="form-control select2 form-select"
                                        data-placeholder="Pilih Jenis Data" aria-label="Default select example"
                                        name="jenis_data">
                                        <option value="">-- Pilih --</option>
                                        <option value="tanam">Tanam</option>
                                        <option value="panen">Panen</option>
                                        <option value="puso/rusak">Puso/Rusak</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="select2Basic" class="form-label">Nilai</label>
                                    <input class="form-control" type="text" name="nilai">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="col-md-12">
                            <button type="reset" class="btn ripple btn-warning mr-3">Cancel</button>
                            <button type="submit" class="btn ripple btn-success mr-3">Kirim</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script></script>
@endsection
