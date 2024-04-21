@extends('index')
@section('title', 'Edit Luas Lahan Wilayah')
@section('content')
    <div class="page-header d-sm-flex d-block">
        <ol class="breadcrumb mb-sm-0 mb-3">
            <!-- breadcrumb -->
            <li class="breadcrumb-item"><a href="index.html">{{ $breadcrumb }}</a></li>
            <li class="breadcrumb-item" aria-current="page">{{ $breadcrumb_1 }}</li>
            <li class="breadcrumb-item active" aria-current="page">{{ $breadcrumb_active }}</li>
        </ol><!-- End breadcrumb -->
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $title }}</h3>
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
                        <form action="{{ url('/operator/master/luas_lahan_wilayah/' . $luas_wilayah->id) }}" method="post"
                            class="needs-validation" novalidate>
                            @method('PUT')
                            @csrf
                            <div class="form-row">
                                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mb-3">
                                    <label for="validationCustom13">Jenis Lahan</label>
                                    <select name="jenis_lahan" id="jenis_lahan" class="form-control form-select select2">
                                        <option value="">-- pilih --</option>
                                        <option value="sawah"
                                            {{ $luas_wilayah->jenis_lahan == 'sawah' ? 'selected' : '' }}>
                                            Sawah</option>
                                        <option value="non_sawah"
                                            {{ $luas_wilayah->jenis_lahan == 'non_sawah' ? 'selected' : '' }}>Non Sawah
                                        </option>
                                    </select>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mb-3">
                                    <label for="validationCustom15">Luas Lahan</label>
                                    <input type="number" class="form-control"
                                        value="{{ $luas_wilayah->luas_lahan_wilayah }}" id="validationCustom15"
                                        name="luas_lahan" value="{{ old('luas_lahan') }}" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 mb-3">
                                    <label for="validationCustom15">Kecamatan</label>
                                    <select name="kecamatan" id="kecamatan" class="form-control form-select select2">
                                        <option value="">-- pilih --</option>
                                        @foreach ($kecamatan as $data)
                                            <option value="{{ $data->id }}"
                                                {{ $luas_wilayah->kecamatan_id == $data->id ? 'selected' : '' }}>
                                                {{ $data->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <button class="btn btn-primary" type="submit">Submit </button>
                        </form>
                </div>
            </div>
        </div>
    </div>
@endsection
