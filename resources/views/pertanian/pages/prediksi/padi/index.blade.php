@extends('index')
@section('title', 'Prediksi Padi')
@section('content')
    <div class="page-header d-sm-flex d-block">
        <ol class="breadcrumb mb-sm-0 mb-3">
            <!-- breadcrumb -->
            <li class="breadcrumb-item"><a href="index.html">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
        </ol><!-- End breadcrumb -->
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $title }}</h3>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ url('/uptd/pengguna/penyuluh') }}" method="post" class="needs-validation" novalidate>
                        @csrf
                        <div class="form-row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">Pilih Jenis Data</label>
                                    <select class="form-control select2 form-select" data-placeholder="Choose one">
                                        <option label="Choose one"></option>
                                        <option value="1">Chuck Testa</option>
                                        <option value="2">Sage Cattabriga-Alosa</option>
                                        <option value="3">Nikola Tesla</option>
                                        <option value="4">Cattabriga-Alosa</option>
                                        <option value="5">Nikola Alosa</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Dari Bulan</label>
                                    <select class="form-control select2 form-select" data-placeholder="Choose one">
                                        <option label="Choose one"></option>
                                        <option value="1">Chuck Testa</option>
                                        <option value="2">Sage Cattabriga-Alosa</option>
                                        <option value="3">Nikola Tesla</option>
                                        <option value="4">Cattabriga-Alosa</option>
                                        <option value="5">Nikola Alosa</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Tahun</label>
                                    <select class="form-control select2 form-select" data-placeholder="Choose one">
                                        <option label="Choose one"></option>
                                        <option value="1">Chuck Testa</option>
                                        <option value="2">Sage Cattabriga-Alosa</option>
                                        <option value="3">Nikola Tesla</option>
                                        <option value="4">Cattabriga-Alosa</option>
                                        <option value="5">Nikola Alosa</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Sampai Bulan</label>
                                    <select class="form-control select2 form-select" data-placeholder="Choose one">
                                        <option label="Choose one"></option>
                                        <option value="1">Chuck Testa</option>
                                        <option value="2">Sage Cattabriga-Alosa</option>
                                        <option value="3">Nikola Tesla</option>
                                        <option value="4">Cattabriga-Alosa</option>
                                        <option value="5">Nikola Alosa</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Tahun</label>
                                    <select class="form-control select2 form-select" data-placeholder="Choose one">
                                        <option label="Choose one"></option>
                                        <option value="1">Chuck Testa</option>
                                        <option value="2">Sage Cattabriga-Alosa</option>
                                        <option value="3">Nikola Tesla</option>
                                        <option value="4">Cattabriga-Alosa</option>
                                        <option value="5">Nikola Alosa</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary mt-3" type="submit"><i class="fa fa-sign-in"></i>Trend
                            Projection</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
