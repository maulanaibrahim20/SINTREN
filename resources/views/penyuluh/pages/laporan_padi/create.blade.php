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
                                <h6 class="fw-semibold">1. Laporan Luas Tanah Tanaman Padi</h6>
                                <hr class="mt-0" />
                            </div>
                            <div class="col-md-6">
                                <label for="select2Basic" class="form-label">Jenis Bantuan</label>
                                <select id="select2Basic" name="jenis_bantuan" class="select2 form-select form-select-lg"
                                    data-allow-clear="true">
                                    <option value="">-- Pilih --</option>
                                    <option value="bantuan pemerintah">Bantuan Pemerintah</option>
                                    <option value="non bantuan pemerintah">Non Bantuan Pemerintah</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="select2Basic" class="form-label">Jenis Padi</label>
                                <select id="select2Basic" name="jenis_padi" class="select2 form-select form-select-lg"
                                    data-allow-clear="true">
                                    @foreach ($jenis_padi as $padi)
                                        <option value="{{ $padi->id }}" name="jenis_padi">
                                            {{ $padi->nama_padi }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12">
                                <h6 class="mt-2 fw-semibold">2. Personal Info</h6>
                                <hr class="mt-0" />
                            </div>
                            <div class="col-md-6">
                                <label for="formValidationFile" class="form-label">NIP</label>
                                <input class="form-control" type="number" id="formValidationFile" name="nip"
                                    value="{{ old('nip') }}" />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="formValidationDob">NIDN</label>
                                <input type="number" class="form-control flatpickr-validation" name="nidn"
                                    id="formValidationDob" value="{{ old('nidn') }}" required />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="formValidationDob">FOTO</label>
                                <input type="file" class="form-control flatpickr-validation" name="image"
                                    id="formValidationDob" required />
                            </div>
                            <div class="col-md-6">
                                <label class="d-block form-label">Jenis Kelamin</label>
                                <div class="form-check mb-2">
                                    <input type="radio" id="bs-validation-radio-male" value="laki-laki" name="jk"
                                        class="form-check-input" {{ old('jk') == 'laki-laki' ? 'checked' : '' }} required />
                                    <label class="form-check-label" for="bs-validation-radio-male">Laki Laki</label>
                                </div>
                                <div class="form-check">
                                    <input type="radio" id="bs-validation-radio-female" value="perempuan" name="jk"
                                        class="form-check-input" {{ old('jk') == 'perempuan' ? 'checked' : '' }} required />
                                    <label class="form-check-label" for="bs-validation-radio-female">Perempuan</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="formValidationLang">Jabatan</label>
                                <input type="text" value="{{ old('jabatan') }}" class="form-control" name="jabatan"
                                    id="formValidationLang" />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="formValidationTech">Prodi</label>
                                <input class="form-control typeahead" type="text" id="formValidationTech" name="prodi"
                                    value="{{ old('prodi') }}" autocomplete="off" />
                            </div>
                            <div class="col-md-12">
                                <label class="form-label" for="select2Basic">Kode Warna</label>
                                <input class="form-control" type="color" name="kode_warna"
                                    value="{{ old('kode_warna') }}" />
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
