@extends('index')
@section('title', 'Edit Tanaman Palawija')
@section('content')
    <div class="page-header d-sm-flex d-block">
        <ol class="breadcrumb mb-sm-0 mb-3">
            <!-- breadcrumb -->
            <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
            <li class="breadcrumb-item" aria-current="page">Kategori Tanaman</li>
            <li class="breadcrumb-item" aria-current="page">Tanaman Palawija</li>
            <li class="breadcrumb-item active" aria-current="page">Edit Tanaman Palawija</li>
        </ol><!-- End breadcrumb -->
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Data Palawija</h3>
                </div>
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
                    <form action="{{ url('/operator/tanaman/palawija/' . $palawija->id) }}" method="post"
                        enctype="multipart/form-data" class="needs-validation" novalidate>
                        @csrf
                        @method('PUT')
                        <div class="form-row">
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mb-3">
                                <label for="validationCustom011">Nama Tanaman Palawija</label>
                                <input type="text" class="form-control" id="validationCustom011" name="name"
                                    value="{{ $palawija->name }}" required>
                                <div class="valid-feedback">Looks good!</div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mb-3">
                                <label for="select2Basic" class="form-label">Kategori Tanaman</label>
                                <select id="category" class="form-select" aria-label="Default select example"
                                    name="category">
                                    <option value="">-- Pilih --</option>
                                    @foreach ($kategori as $item)
                                        <option value="{{ $item->id }}"
                                            {{ $palawija->category == $item->id ? 'selected' : '' }}>
                                            {{ $item->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-12">
                                <label for="validationCustom15">Deskripsi</label>
                                <textarea class="form-control" name="description" id="exampleFormControlTextarea1" rows="3"
                                    placeholder="Write a large text here ...">{{ $palawija->description }}</textarea>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mb-3 mt-3">
                                <label for="select2Basic" class="form-label">Masa Panen Dalam</label>
                                <select class="form-select w-100" data-style="btn-default" name="masa_panen">
                                    <option value="">-- Pilih --</option>
                                    <option value="3-6 bulan" {{ $palawija->musim_panen == '3-6 bulan' ? 'selected' : '' }}>
                                        3-6 bulan</option>
                                    <option value="6-9 bulan" {{ $palawija->musim_panen == '6-9 bulan' ? 'selected' : '' }}>
                                        6-9 bulan</option>
                                    <option value="60-90 hari"
                                        {{ $palawija->musim_panen == '60-90 hari' ? 'selected' : '' }}>60-90 hari</option>
                                    <option value="70-90 hari"
                                        {{ $palawija->musim_panen == '70-90 hari' ? 'selected' : '' }}>70-90 hari</option>
                                    <option value="90-120 hari"
                                        {{ $palawija->musim_panen == '90-120 hari' ? 'selected' : '' }}>90-120 hari
                                    </option>
                                    <option value="100-150 hari"
                                        {{ $palawija->musim_panen == '100-150 hari' ? 'selected' : '' }}>100-150 hari
                                    </option>
                                </select>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mb-3 mt-3">
                                <label for="validationCustom12">Kegunaan</label>
                                <input type="text" name="kegunaan" class="form-control" id="validationCustom12"
                                    value="{{ $palawija->kegunaan }}"
                                    placeholder="Dimakan langsung, bahan baku untuk minyak, bahan dasar makanan." required>
                                <div class="valid-feedback">Looks good!</div>
                            </div>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-3">
                            <label for="validationCustom12">Upload Image</label>
                            <input type="file" class="dropify" name="image" data-height="200">
                            @if ($palawija->gambar)
                                <div class="mt-3">
                                    <label for="currentImage">Gambar Saat Ini:</label><br>
                                    <img src="{{ asset($palawija->gambar) }}" alt="Current Image"
                                        style="max-height: 200px;">
                                </div>
                            @endif
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
