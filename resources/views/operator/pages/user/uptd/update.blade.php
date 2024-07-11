@extends('index')
@section('title', 'Update User UPTD')
@section('content')
    <div class="page-header d-sm-flex d-block">
        <ol class="breadcrumb1 br-7">
            <li class="breadcrumb-item1"><a href="{{ url('/operator/dashboard') }}">{{ $breadcrumb }}</a></li>
            <li class="breadcrumb-item1"><a href="{{ url('/operator/user/uptd') }}">{{ $breadcrumb_1 }}</a></li>
            <li class="breadcrumb-item1 active">{{ $breadcrumb_active }}</li>
        </ol>
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
                    <form action="{{ url('/operator/user/uptd/' . encrypt($user->id)) }}" method="post">
                        @csrf
                        @method('PUT')
                        <div class="form-row">
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mb-3">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" name="name" value="{{ $user->user->name }}"
                                    required>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" value="{{ $user->user->email }}"
                                    required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mb-3">
                                <label class="form-label">Alamat</label>
                                <input type="text" class="form-control" name="alamat" value="{{ $user->alamat }}"
                                    required>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mb-3">
                                <label class="form-label">Nomor Telepon</label>
                                <input type="number" class="form-control" name="no_telp" value="{{ $user->no_telp }}"
                                    required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-3">
                                <label class="form-label">Pilih Kecamatan</label>
                                <select class="form-control select2 form-select" name="kecamatan"
                                    data-placeholder="Pilih Kecamatan">
                                    <option label="Pilih Kecamatan"></option>
                                    @foreach ($kecamatan as $kec)
                                        <option value="{{ $kec->id }}"
                                            {{ $user->kecamatan_id == $kec->id ? 'selected' : '' }}>
                                            {{ $kec->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @include('template.component.button')
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
