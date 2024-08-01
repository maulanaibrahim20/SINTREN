@extends('index')
@section('title', 'Tambah Laporan Padi | Penyuluh')
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
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ url('/penyuluh/create/laporan_padi') }}" method="POST" enctype="multipart/form-data">
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
                                    @error('date')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="select2Basic" class="form-label">Desa Penugasan</label>
                                    <select id="desa" class="form-control select2 form-select"
                                        aria-label="Default select example" data-placeholder="Pilih Desa" name="desa">
                                        <option value="">-- Pilih Desa --</option>
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
                                        <option value="sawah">Lahan Sawah</option>
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
                                <div class="form-group" style="display: none">
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
                                    <input class="form-control" type="number" name="nilai">
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
    <script>
        $(document).ready(function() {
            // Ketika nilai jenis lahan berubah
            $('#jenis_lahan').change(function() {
                // Ambil nilai jenis lahan yang dipilih
                var jenisLahan = $(this).val();

                // Jika jenis lahan adalah 'lahan sawah'
                if (jenisLahan === 'sawah') {
                    // Tampilkan opsi jenis pengairan
                    $('#jenis_pengairan').parent().show();
                } else {
                    // Sembunyikan opsi jenis pengairan
                    $('#jenis_pengairan').parent().hide();
                }
            });
        });
    </script>
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
