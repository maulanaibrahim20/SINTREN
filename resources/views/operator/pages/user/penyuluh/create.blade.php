@extends('index')
@section('title', 'Create User Penyuluh | Operator')
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
                    <h3 class="card-title">Buat Akun Penyuluh</h3>
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
                        <form action="{{ url('/operator/user/penyuluh') }}" method="post" class="needs-validation"
                            novalidate>
                            @csrf
                            <div class="form-row">
                                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mb-3">
                                    <label for="validationCustom011">Nama Lengkap</label>
                                    <input type="text" class="form-control" id="validationCustom011" name="name"
                                        value="{{ old('name') }}" required>
                                    <div class="valid-feedback">Looks good!</div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mb-3">
                                    <label for="validationCustom12">Email</label>
                                    <input type="email" name="email" class="form-control" id="validationCustom12"
                                        value="{{ old('email') }}" value="Otto" required>
                                    <div class="valid-feedback">Looks good!</div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mb-3">
                                    <label for="validationCustom13">Alamat</label>
                                    <input type="text" class="form-control" id="validationCustom13" name="alamat"
                                        value="{{ old('alamat') }}" required>
                                    <div class="invalid-feedback">Please provide a valid address.</div>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 mb-3">
                                    <label for="validationCustom15">Nomor Telepon</label>
                                    <input type="number" class="form-control" id="validationCustom15" name="no_telp"
                                        value="{{ old('no_telp') }}" required>
                                    <div class="invalid-feedback">Please provide a valid zip.</div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 mb-3">
                                    <label for="validationCustom15">Kecamatan</label>
                                    <select name="kecamatan" id="kecamatan" class="form-control form-select select2">
                                        <option value="">-- pilih --</option>
                                        @foreach ($kecamatan as $data)
                                            @php
                                                $isdisabled = in_array($data->id, $selected); // Menggunakan in_array untuk memeriksa apakah id ada dalam array $selected
                                            @endphp
                                            <option value="{{ $data->id }}" {{ $isdisabled ? 'disabled' : '' }}>
                                                {{ $data->name }}</option>
                                        @endforeach
                                    </select>
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
