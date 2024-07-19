@extends('index')
@section('title', 'Update Pengguna Pasar')
@section('content')
    <div class="page-header d-sm-flex d-block">
        <ol class="breadcrumb1 br-7">
            <li class="breadcrumb-item1"><a href="{{ url('/pangan/dashboard') }}">{{ $breadcrumb }}</a></li>
            <li class="breadcrumb-item1"><a href="{{ url('/pangan/user/pasar') }}">{{ $breadcrumb_1 }}</a></li>
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
                    <form action="{{ url('/pangan/user/pasar') }}" method="post" class="needs-validation"
                    novalidate>
                    @csrf
                    <div class="form-row">
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mb-3">
                            <label for="validationCustom011">Nama Lengkap</label>
                            <input type="text" class="form-control" id="validationCustom011" name="name"
                                value="{{ $user->user->name }}" required>
                            <div class="valid-feedback">Looks good!</div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mb-3">
                            <label for="validationCustom13">Alamat</label>
                            <input type="text" class="form-control" id="validationCustom13" name="alamat"
                                value="{{ $user->alamat }}" required>
                            <div class="invalid-feedback">Please provide a valid address.</div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mb-3">
                            <label for="validationCustom12">Email</label>
                            <input type="email" name="email" class="form-control" id="validationCustom12"
                                value="{{ $user->user->email }}" value="Otto" required>
                            <div class="valid-feedback">Looks good!</div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mb-3">
                            <label for="validationCustom15">Username</label>
                            <input type="username" class="form-control" id="validationCustom15" name="username"
                                value="{{ $user->user->username }}" required>
                            <div class="invalid-feedback">Please provide a valid zip.</div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mb-3">
                            <label for="validationCustom15">Nomor Telepon</label>
                            <input type="number" class="form-control" id="validationCustom15" name="no_telp"
                                value="{{ $user->no_telp }}" required>
                            <div class="invalid-feedback">Please provide a valid zip.</div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mb-3">
                            <label for="validationCustom15">Pasar</label>
                            <select name="pasar" id="pasar" class="form-control form-select select2">
                                <option value="">-- pilih --</option>
                            @foreach ($pasar as $data)
                                @php
                                    $selected = $data->id == $selected_pas ? 'selected' : '';
                                @endphp
                                <option value="{{ $data->id }}" {{ $selected ? 'selected' : '' }}>
                                    {{ $data->name }}</option>
                            @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="d-flex" style="justify-content: end">
                    <a href="{{ url('/pangan/create/data_pangan') }}" class="btn ripple btn-warning mr-2">Cancel</a>
                    <button class="btn btn-primary" type="submit">Submit </button>
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
