@extends('index')
@section('title', 'Tambah Pengguna Pasar')
@section('content')
<div class="page-header d-sm-flex d-block">
    <ol class="breadcrumb1 br-7">
        <li class="breadcrumb-item1"><a href="{{ url('/operator/dashboard') }}">{{ $breadcrumb }}</a></li>
        <li class="breadcrumb-item1"><a href="{{ url('/operator/user/pasar') }}">{{ $breadcrumb_1 }}</a></li>
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
                <form action="{{ url('/operator/user/pasar') }}" method="post">
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
                            value="Otto" required>
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
                            <label class="form-label">Pasar</label>
                            <select name="pasar" id="pasar" class="form-control select2 form-select"
                            data-placeholder="Pilih Pasar">
                            <option value="">-- pilih --</option>
                            @foreach ($pasar as $data)
                            <option value="{{ $data->id }}">
                                {{ $data->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="d-flex" style="justify-content: end">
                        <a href="{{ url('/operator/user/pasar') }}" class="btn btn-danger mt-3"><i class="fa fa-times"></i> Cancel</a>
                        <button class="btn btn-primary mt-3" style="margin-left: 10px" type="submit"><i class="fa fa-sign-in"></i>Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        $('.needs-validation').submit(function(event) {
            event.preventDefault();

            Swal.fire({
                title: 'Konfirmasi',
                text: 'Apakah data yang Anda isi sudah benar?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, kirim!',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    $(this).unbind('submit')
                    .submit();
                }
            });
        });
    });
</script>
@endsection
