@extends('index')
@section('title', 'Edit Data Stok Pangan')
@section('content')
    <div class="page-header d-sm-flex d-block">
        <ol class="breadcrumb1 br-7">
            <li class="breadcrumb-item1"><a href="{{ url('/pangan/dashboard') }}">{{ $breadcrumb }}</a></li>
            <li class="breadcrumb-item1"><a href="{{ url('/pangan/create/data_pangan') }}">{{ $breadcrumb_1 }}</a></li>
            <li class="breadcrumb-item1 active">{{ $breadcrumb_active }}</li>
        </ol>
    </div>
    <div class="row row-cards">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0 card-title">Edit Data Stok Pangan</h3>
                </div>
                <form action="{{ url('/pangan/create/data_pangan/' . $editPangan->id) }}" method="POST" id="myForm">
                    <div class="card-body">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            {{-- <div class="col-md-4">
                                <div class="form-group">
                                    <label for="select2Basic" class="form-label">Tanggal Input</label>
                                    <div class="input-group">
                                        <div class="input-group-text">
                                            <i class="typcn typcn-calendar-outline tx-24 lh--9 op-6"></i>
                                        </div>
                                        <input type="text" class="form-control" name="date" id="date" placeholder="Pilih Tanggal" value="{{ $editPangan->date }}" readonly>
                                    </div>
                                </div>
                            </div> --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="select2Basic" class="form-label">Pasar</label>
                                    <select id="pasar" class="form-control form-select select2" aria-label="Default select example" name="pasar_id" disabled>
                                        <option value="">-- Pilih --</option>
                                        @foreach ($pasar as $item)
                                            <option value="{{ $item->id }}" {{ $editPangan->pasar_id == $item->id ? 'selected' : '' }}>
                                                {{ $item->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="jenispangan" class="form-label">Jenis Pangan</label>
                                    <select id="jenispangan" class="form-control form-select select2" aria-label="Default select example" name="jenis_pangan_id" disabled>
                                        <option value="">-- Pilih --</option>
                                        @foreach ($jenispangan as $item)
                                            <option value="{{ $item->id }}" {{ $editPangan->jenis_pangan_id == $item->id ? 'selected' : '' }}>
                                                {{ $item->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="subjenispangan" class="form-label">Nama Pangan</label>
                                    <select id="subjenispangan" class="form-control form-select select2" aria-label="Default select example" name="subjenis_pangan_id" disabled>
                                        <option value="">-- Pilih --</option>
                                        @foreach ($subjenispangan as $item)
                                            <option value="{{ $item->id }}" {{ $editPangan->subjenis_pangan_id == $item->id ? 'selected' : '' }}>
                                                {{ $item->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Stok</label>
                                    <input type="number" class="form-control" name="stok" value="{{ $editPangan->stok }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Harga</label>
                                    <input type="number" class="form-control" name="harga" value="{{ $editPangan->harga }}" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="col-md-12 d-flex justify-content-end">
                            <a href="{{ url('/pangan/create/data_pangan') }}" class="btn ripple btn-warning mr-2">Cancel</a>
                            <button type="submit" class="btn ripple btn-success">Kirim</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
