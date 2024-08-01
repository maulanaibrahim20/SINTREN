@extends('index')
@section('title', 'Tambah Pengguna Penyuluh')
@section('content')
    <div class="page-header d-sm-flex d-block">
        <ol class="breadcrumb1 br-7">
            <li class="breadcrumb-item1"><a href="{{ url('/operator/dashboard') }}">{{ $breadcrumb }}</a></li>
            <li class="breadcrumb-item1"><a href="{{ url('/operator/user/penyuluh') }}">{{ $breadcrumb_1 }}</a></li>
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
                    <form action="{{ url('/operator/user/penyuluh') }}" method="post">
                        @csrf
                        <div class="form-row">
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mb-3">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" name="name" value="{{ old('name') }}"
                                    required>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email') }}"
                                    required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mb-3">
                                <label class="form-label">Alamat</label>
                                <input type="text" class="form-control" name="alamat" value="{{ old('alamat') }}"
                                    required>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mb-3">
                                <label class="form-label">Nomor Telepon</label>
                                <input type="number" class="form-control" name="no_telp" value="{{ old('no_telp') }}"
                                    required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 mb-3">
                                <label class="form-label">Kecamatan</label>
                                <select name="kecamatan" id="kecamatan" class="form-control select2 form-select"
                                    data-placeholder="Pilih Kecamatan">
                                    <option value="">-- pilih --</option>
                                    @foreach ($kecamatan as $data)
                                        <option value="{{ $data->id }}"
                                            {{ old('kecamatan') == $data->id ? 'selected' : '' }}>
                                            {{ $data->name }}
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

@section('script')
    <script>
        $(document).ready(function() {
            $("#kecamatan").change(function() {
                let kecamatan = $("#kecamatan").val();
                $.ajax({
                    url: "{{ url('/ambil_desa') }}",
                    type: "GET",
                    data: {
                        kecamatan: kecamatan
                    },
                    success: function(res) {
                        $("#desa").html(res);
                    },
                    error: function(error) {
                        alert('Gagal mengambil data kecamatan.');
                    }
                });
            });
        });
    </script>
@endsection
