@extends('index')
@section('title', 'Create Laporan Palawija')
@section('content')
    <div class="page-header d-sm-flex d-block">
        <ol class="breadcrumb mb-sm-0 mb-3">
            <!-- breadcrumb -->
            <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Laporan Palawija</li>
        </ol><!-- End breadcrumb -->
    </div>
    <div class="row row-cards">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0 card-title">Tambah Data Laporan Palawija</h3>
                </div>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ url('/penyuluh/create/laporan_palawija') }}" method="POST" id="myForm">
                    <div class="card-body">
                        @csrf
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
                                    <label for="select2Basic" class="form-label">Penugasan Desa</label>
                                    <select id="desa" class="form-control form-select select2"
                                        aria-label="Default select example" name="desa">
                                        <option value="">-- Pilih --</option>
                                        @foreach ($penugasanPenyuluh as $item)
                                            <option value="{{ $item->desa_id }}" name="desa">
                                                {{ $item->desa->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="select2Basic" class="form-label">Pilih Jenis Lahan</label>
                                    <select id="jenis_lahan" class="form-control form-select select2"
                                        aria-label="Default select example" name="jenis_lahan">
                                        <option value="">-- Pilih --</option>
                                        <option value="sawah">Lahan Sawah</option>
                                        <option value="non sawah">Bukan Sawah/Non Sawah</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="select2Basic" class="form-label">Pilih Jenis Bantuan</label>
                                    <select id="jenis_bantuan" class="form-control form-select select2"
                                        aria-label="Default select example" name="jenis_bantuan">
                                        <option value="">-- Pilih --</option>
                                        <option value="bantuan pemerintah">Bantuan Pemerintah</option>
                                        <option value="non bantuan pemerintah">Bukan Bantuan Pemerintah/Non Bantuan
                                            Pemerintah</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="select2Basic" class="form-label">Pilih Jenis Palawija</label>
                                    <select id="jenis_palawija" class="form-control form-select select2"
                                        aria-label="Default select example" name="jenis_palawija">
                                        <option value="">-- Pilih --</option>
                                        @foreach ($jenisPalawija as $palawija)
                                            <option value="{{ $palawija->id }}">{{ $palawija->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="select2Basic" class="form-label">Pilih Jenis Data/Inputan</label>
                                    <select id="jenis_data" class="form-control form-select select2"
                                        aria-label="Default select example" name="jenis_data">
                                        <option value="">-- Pilih --</option>
                                        <option value="panen">Panen</option>
                                        <option value="tanam">Tanam</option>
                                        <option value="puso/rusak">Puso/Rusak</option>
                                        <option value="panen muda">Panen Muda</option>
                                        <option value="panen hijauan pakan ternak">Panen Hijauan Pakan Ternak</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Nilai</label>
                                    <input type="number" class="form-control" name="nilai">
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
    @if ($errors->any())
        <script type="text/javascript">
            let errorMessages = '';
            @foreach ($errors->all() as $error)
                errorMessages += '{{ $error }}\n';
            @endforeach
            Swal.fire({
                title: "Gagal",
                text: errorMessages,
                icon: "error"
            });
        </script>
    @endif
@endsection
