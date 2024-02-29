@extends('index')
@section('title', 'Update User | Operator')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div
            class="card-header sticky-element bg-label-secondary d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row py-3 mb-4">
            <h5 class="card-title mb-sm-0 me-2"><span class="text-muted fw-light">Create Account User /</span> Create Client
                Table
            </h5>
        </div>
        <div class="row">
            <!-- FormValidation -->
            <div class="col-12">
                <div class="card">
                    <h5 class="card-header">Masukan Data Pengguna</h5>
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
                        <form action="{{ url('/operator/user/uptd/' . $user->id) }}" method="post"
                            id="formValidationExamples" class="row g-3" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="col-12">
                                <h6 class="fw-semibold">1. Data Pengguna</h6>
                                <hr class="mt-0" />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="formValidationName">Nama Lengkap</label>
                                <input type="text" id="formValidationName" class="form-control" placeholder="John Doe"
                                    name="name" value="{{ $user->user->name }}" />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="formValidationEmail">Email</label>
                                <input class="form-control" type="email" id="formValidationEmail" name="email"
                                    placeholder="john.doe" value="{{ $user->user->email }}" />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="formValidationEmail">Alamat</label>
                                <input class="form-control" type="text" id="formValidationEmail" name="alamat"
                                    placeholder="indramayuxx" value="{{ $user->alamat }}" />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="formValidationEmail">Nomor Telepon</label>
                                <input class="form-control" type="number" id="formValidationEmail" name="no_telp"
                                    placeholder="08xxxxx" value="{{ $user->no_telp }}" />
                            </div>
                            <div class="col-12 d-flex justify-content-end">
                                <a href="{{ url('/koordinator/create/dosen') }}" type="submit" class="btn btn-warning"><i
                                        class="fa fa-arrow-left"></i>Back</a>
                                <button type="submit" class="btn btn-primary ms-2">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
