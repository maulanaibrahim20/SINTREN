@extends('index')
@section('title', 'Tambah Laporan Padi | Penyuluh')
@section('content')
    <div class="page-header d-sm-flex d-block">
        <ol class="breadcrumb mb-sm-0 mb-3">
            <!-- breadcrumb -->
            <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
            <li class="breadcrumb-item" aria-current="page">Data Pengguna</li>
            <li class="breadcrumb-item" aria-current="page">Pengguna Penyuluh</li>
            <li class="breadcrumb-item active" aria-current="page">Buat Akun Penyuluh</li>
        </ol><!-- End breadcrumb -->
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Form Tambah Data Laporan</h3>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                        @endif @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form action="{{ url('/penyuluh/create/laporan_padi') }}" method="post" class="needs-validation"
                            enctype="multipart/form-data" novalidate>
                            @csrf
                            <div class="form-row">
                                <div class="col-12">
                                    <h6 class="fw-semibold">1. Wilayah</h6>
                                    <hr class="mt-0" />
                                </div>
                                <div class="col-md-6">
                                    <label for="select2Basic" class="form-label">Kecamatan</label>
                                    <select id="kecamatan" class="form-select" aria-label="Default select example"
                                        name="kecamatan">
                                        <option value="">-- Pilih --</option>
                                        @foreach ($kecamatan as $kec)
                                            <option value="{{ $kec->id }}" name="kecamatan">
                                                {{ $kec->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="select2Basic" class="form-label">Desa</label>
                                    <select id="desa" class="form-select" for="desa" name="desa">
                                        <option value="">-- Pilih --</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-12">
                                    <h6 class="fw-semibold">2. Uraian</h6>
                                    <hr class="mt-0" />
                                </div>
                                <div class="col-md-6">
                                    <label for="select2Basic" class="form-label">Jenis Bantuan</label>
                                    <select class="form-select w-100" data-style="btn-default" name="jenis_bantuan">
                                        <option value="">-- Pilih --</option>
                                        <option value="bantuan pemerintah">Bantuan Pemerintah</option>
                                        <option value="non bantuan pemerintah">Non Bantuan Pemerintah</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="select2Basic" class="form-label">Jenis Padi</label>
                                    <select id="selectpickerBasic" name="jenis_padi" class="form-select w-100"
                                        data-style="btn-default">
                                        <option value="">-- pilih --</option>
                                        @foreach ($jenis_padi as $padi)
                                            <option value="{{ $padi->id }}" name="jenis_padi">
                                                {{ $padi->nama_padi }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-12">
                                    <h6 class="mt-2 fw-semibold">3. Laporan Lahan Sawah</h6>
                                    <hr class="mt-0" />
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mb-3">
                                    <label for="validationCustom011">Tanaman Akhir Bulan Lalu</label>
                                    <input type="number" class="form-control" id="validationCustom011"
                                        name="tanaman_akhir_bulan" value="" required>
                                    <div class="valid-feedback">Looks good!</div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mb-3">
                                    <label for="validationCustom12">Panen</label>
                                    <input type="number" name="panen" class="form-control" id="validationCustom12"
                                        value="" required>
                                    <div class="valid-feedback">Looks good!</div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mb-3">
                                    <label for="validationCustom13">Tanam</label>
                                    <input type="number" class="form-control" id="validationCustom13" name="tanam"
                                        value="" required>
                                    <div class="invalid-feedback">Please provide a valid address.</div>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 mb-3">
                                    <label for="validationCustom15">Puso/Rusak</label>
                                    <input type="number" class="form-control" id="validationCustom15" name="rusak"
                                        value="" required>
                                    <div class="invalid-feedback">Please provide a valid zip.</div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 mb-3">
                                    <label for="validationCustom15">Tanaman Akhir Bulan Laporan</label>
                                    <input type="number" class="form-control" id="validationCustom15"
                                        name="tanam_akhir_bulan_laporan" value="" required>
                                    <div class="invalid-feedback">Please provide a valid zip.</div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="ckbox d-flex align-items-center">
                                    <input type="checkbox" id="invalidCheck3" required>
                                    <span>I agree terms and conditions</span>
                                </label>
                            </div>
                            <button class="btn btn-primary" type="submit">Submit </button>
                        </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $("#kecamatan").change(function() {
                let kecamatan = $("#kecamatan").val();
                $.ajax({
                    url: "{{ url('/penyuluh/getDesa') }}",
                    type: "GET",
                    data: {
                        kecamatan: kecamatan
                    },
                    success: function(res) {
                        $("#desa").html(res);
                        console.log(res);
                    },
                    error: function() {
                        alert('Gagal mengambil data kelurahan.');
                    }
                });
            });
        });
    </script>
@endsection
