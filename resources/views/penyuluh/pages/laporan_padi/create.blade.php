@extends('index')
@section('title', 'Tambah Laporan Padi | Penyuluh')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div
            class="card-header sticky-element bg-label-secondary d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row py-3 mb-4">
            <h5 class="card-title mb-sm-0 me-2"><span class="text-muted fw-light">Dashboard /</span> Tambah Laporan Padi
            </h5>
        </div>
        <div class="row">
            <!-- FormValidation -->
            <div class="col-12">
                <div class="card">
                    <h5 class="card-header">Form Tambah Data Laporan</h5>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form action="{{ url('/penyuluh/create/laporan_padi') }}" method="post" id="formValidationExamples"
                            class="row g-3" enctype="multipart/form-data">
                            @csrf
                            <div class="col-12">
                                <h6 class="fw-semibold">1. Wilayah</h6>
                                <hr class="mt-0" />
                            </div>
                            <div class="col-md-6">
                                <label for="select2Basic" class="form-label">Kecamatan</label>
                                <select id="kecamatan" class="selectpicker w-100" data-style="btn-default" name="kecamatan">
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
                            <div class="col-12">
                                <h6 class="fw-semibold">2. Uraian</h6>
                                <hr class="mt-0" />
                            </div>
                            <div class="col-md-6">
                                <label for="select2Basic" class="form-label">Jenis Bantuan</label>
                                <select id="selectpickerBasic" class="selectpicker w-100" data-style="btn-default"
                                    name="jenis_bantuan">
                                    <option value="">-- Pilih --</option>
                                    <option value="bantuan pemerintah">Bantuan Pemerintah</option>
                                    <option value="non bantuan pemerintah">Non Bantuan Pemerintah</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="select2Basic" class="form-label">Jenis Padi</label>
                                <select id="selectpickerBasic" name="jenis_padi" class="selectpicker w-100"
                                    data-style="btn-default">
                                    <option value="">-- pilih --</option>
                                    @foreach ($jenis_padi as $padi)
                                        <option value="{{ $padi->id }}" name="jenis_padi">
                                            {{ $padi->nama_padi }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12">
                                <h6 class="mt-2 fw-semibold">3. Laporan Lahan Sawah</h6>
                                <hr class="mt-0" />
                            </div>
                            <div class="col-md-6">
                                <label for="formValidationFile" class="form-label">Tanaman Akhir Bulan Lalu</label>
                                <input class="form-control" type="number" id="formValidationFile"
                                    name="tanaman_akhir_bulan" value="" />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="formValidationDob">Panen</label>
                                <input type="number" class="form-control flatpickr-validation" name="panen"
                                    id="formValidationDob" value="" required />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="formValidationDob">Tanam</label>
                                <input type="number" class="form-control flatpickr-validation" name="tanam"
                                    id="formValidationDob" value="" required />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="formValidationDob">Puso/Rusak</label>
                                <input type="number" class="form-control flatpickr-validation" name="rusak"
                                    id="formValidationDob" value="" required />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="formValidationDob">Tanaman Akhir Bulan Laporan</label>
                                <input type="number" class="form-control flatpickr-validation"
                                    name="tanam_akhir_bulan_laporan" id="formValidationDob" value="" required />
                            </div>
                            <div class="col-12 d-flex justify-content-end">
                                <a href="{{ url('/koordinator/create/dosen') }}" type="submit"
                                    class="btn btn-warning"><i class="fa fa-arrow-left"></i>Back</a>
                                <button type="submit" class="btn btn-primary ms-2">Submit</button>
                            </div>
                        </form>
                    </div>
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
