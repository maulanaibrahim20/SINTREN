@extends('index')
@section('title', 'Tambah Pengguna Penyuluh')
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
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ url('/uptd/pengguna/penyuluhUptd') }}" method="post" class="needs-validation"
                        novalidate>
                        @csrf
                        <div class="form-row">
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mb-3">
                                <label for="validationCustom011">Nama Lengkap</label>
                                <input type="text" class="form-control" id="validationCustom011" name="name"
                                    value="{{ old('name') }}" required>
                                <div class="valid-feedback">Looks good!</div>
                                <div class="invalid-feedback">Nama lengkap wajib diisi.</div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mb-3">
                                <label for="validationCustom12">Email</label>
                                <input type="email" name="email" class="form-control" id="validationCustom12"
                                    value="{{ old('email') }}" required>
                                <div class="valid-feedback">Looks good!</div>
                                <div class="invalid-feedback">Email wajib diisi dengan format yang benar.</div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mb-3">
                                <label for="validationCustom13">Alamat</label>
                                <input type="text" class="form-control" id="validationCustom13" name="alamat"
                                    value="{{ old('alamat') }}" required>
                                <div class="valid-feedback">Looks good!</div>
                                <div class="invalid-feedback">Alamat wajib diisi.</div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mb-3">
                                <label for="validationCustom15">Nomor Telepon</label>
                                <input type="number" class="form-control" id="validationCustom15" name="no_telp"
                                    value="{{ old('no_telp') }}" required>
                                <div class="valid-feedback">Looks good!</div>
                                <div class="invalid-feedback">Nomor telepon wajib diisi.</div>
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
    @if ($errors->any())
        <script type="text/javascript">
            let errorMessages = '';
            @foreach ($errors->all() as $error)
                errorMessages += '{{ $error }}\n';
            @endforeach
            Swal.fire({
                title: "Gagal",
                text: errorMessages,
                icon: "error"
            });
        </script>
    @endif
@endsection
