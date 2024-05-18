@extends('index')
@section('title', 'Edit Laporan Padi | Penyuluh')
@section('content')
    <div class="main-container container-fluid">
        <div class="page-header d-sm-flex d-block">
            <ol class="breadcrumb mb-sm-0 mb-3">
                <!-- breadcrumb -->
                <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Laporan Padi</li>
                <!-- End breadcrumb -->
            </ol>
        </div>
    </div>
    <div class="row row-cards">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0 card-title">Tambah Data Laporan Padi</h3>
                </div>
                <form action="{{ url('/penyuluh/create/laporan_padi/' . $edit['id']) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="select2Basic" class="form-label">Desa Penugasan</label>
                                    <select id="desa" class="form-control select2 form-select"
                                        aria-label="Default select example" data-placeholder="Pilih Desa" name="desa">
                                        <option value="">-- Pilih Desa --</option>
                                        @foreach ($penugasanDesa as $item)
                                            <option value="{{ $item->desa_id }}"
                                                {{ $edit['desa_id'] == $item->desa_id ? 'selected' : '' }}>
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
                                        <option value="lahan_sawah"
                                            {{ $edit['jenis_lahan'] == 'lahan_sawah' ? 'selected' : '' }}>Lahan Sawah
                                        </option>
                                        <option value="non_sawah"
                                            {{ $edit['jenis_lahan'] == 'non_sawah' ? 'selected' : '' }}>Bukan Sawah/Non
                                            Sawah</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="select2Basic" class="form-label">Pilih Jenis Bantuan</label>
                                    <select id="jenis_bantuan" class="form-control select2 form-select"
                                        data-placeholder="Pilih Jenis Bantuan" aria-label="Default select example"
                                        name="jenis_bantuan">
                                        <option value="">-- Pilih --</option>
                                        <option value="bantuan_pemerintah"
                                            {{ $edit['jenis_bantuan'] == 'bantuan_pemerintah' ? 'selected' : '' }}>Bantuan
                                            Pemerintah</option>
                                        <option value="non_bantuan_pemerintah"
                                            {{ $edit['jenis_bantuan'] == 'non_bantuan_pemerintah' ? 'selected' : '' }}>Bukan
                                            Bantuan Pemerintah/Non Bantuan Pemerintah</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="select2Basic" class="form-label">Jenis Pengairan</label>
                                    <select id="jenis_pengairan" class="form-control select2 form-select"
                                        aria-label="Default select example" data-placeholder="Pilih Jenis Pengairan"
                                        name="jenis_pengairan">
                                        <option value="">-- Pilih Pengairan --</option>
                                        @foreach ($pengairan as $peng)
                                            <option value="{{ $peng['id'] }}"
                                                {{ $edit['id_jenis_pengairan'] == $peng['id'] ? 'selected' : '' }}>
                                                {{ $peng['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="select2Basic" class="form-label">Pilih Inputan</label>
                                    <select id="jenis_data" class="form-control select2 form-select"
                                        data-placeholder="Pilih Jenis Data" aria-label="Default select example"
                                        name="jenis_data">
                                        <option value="">-- Pilih --</option>
                                        <option value="tanam" {{ $edit['tipe_data'] == 'tanam' ? 'selected' : '' }}>Tanam
                                        </option>
                                        <option value="panen" {{ $edit['tipe_data'] == 'panen' ? 'selected' : '' }}>Panen
                                        </option>
                                        <option value="puso_rusak"
                                            {{ $edit['jenis_data'] == 'puso_rusak' ? 'selected' : '' }}>Puso/Rusak</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="select2Basic" class="form-label">Nilai</label>
                                    <input class="form-control" type="text" name="nilai" value="{{ $edit['nilai'] }}">
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
