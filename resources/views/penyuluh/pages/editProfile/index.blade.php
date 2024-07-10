@extends('index')
@section('title', 'Edit Profile')
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card overflow-hidden">
                <div class="card-body">
                    <div class="profile-bg h-250 cover-image" data-bs-image-src="{{ url('/assets') }}/images/photos/30.jpg">
                    </div>
                    <a href="javascript:void(0);" class="profile-background-pic-edit op5"><i
                            class="fe fe-camera p-1 bg-white-transparent border border-white text-white op-7 br-7 fs-5"></i></a>
                    <div class="py-4 position-relative">
                        <div class="profile-img">
                            <img src="{{ url('/assets') }}/images/users/male/24.jpg" class="avatar avatar-xxl br-7"
                                alt="person-image">
                        </div>
                        <div class="mt-5 d-sm-flex align-items-center">
                            <div>
                                <h3 class="fw-semibold mb-1">{{ $penyuluh->user->name }}</h3>
                                <p class="mb-0 fw-semibold text-muted-dark">Alamat : {{ $penyuluh->alamat }}</p>
                                <p class="mb-0 my-1 fw-semibold text-muted-dark fs-13">Penyuluh Kecamatan :
                                    {{ $penyuluh->kecamatan->name }}</p>
                                <div class="mb-2">
                                    <span
                                        class="badge badge-light fw-semibold text-dark fs-12 me-2">{{ $penyuluh->user->getAkses->name }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-wrap">
                        <div class="panel tabs-style6">
                            <div class="panel-head d-flex">
                                <ul class="nav nav-tabs d-block d-sm-inline-flex">
                                    <li class="nav-item"><a class="nav-link active fw-bold" data-bs-toggle="tab"
                                            href="#style6tab2">Edit Profile</a></li>
                                    <li class="nav-item"><a class="nav-link fw-bold" data-bs-toggle="tab"
                                            href="#style6tab3">Edit Password</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-12 p-0">
                <div class="panel-body p-0">
                    <div class="tab-content">
                        <div class="tab-pane active p-0" id="style6tab2">
                            <div class="card overflow-hidden border-0">
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
                                    <form action="{{ url('/penyuluh/pengaturan/editProfile/' . $penyuluh->id) }}"
                                        method="POST" class="form-horizontal" enctype="multipart/form-data">
                                        @method('PUT')
                                        @csrf
                                        <div class="d-flex">
                                            <div class="ms-auto">
                                                <button type="submit" class="btn btn-sm btn-success mx-1">Update</button>
                                                <button type="reset" class="btn btn-sm btn-danger">Discard</button>
                                            </div>
                                        </div>
                                        <h6 class="text-uppercase fw-semibold mb-3">User Name</h6>
                                        <div class="form-group">
                                            <div class="row row-sm">
                                                <div class="col-md-3">
                                                    <label class="form-label fw-semibold text-muted-dark">Nama</label>
                                                </div>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" name="nama"
                                                        placeholder="Masukan Nama"
                                                        value="{{ old('nama', $penyuluh->user->name) }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row row-sm">
                                                <div class="col-md-3">
                                                    <label class="form-label fw-semibold text-muted-dark">Email</label>
                                                </div>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" name="email"
                                                        placeholder="Masukan Email"
                                                        value="{{ old('email', $penyuluh->user->email) }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row row-sm">
                                                <div class="col-md-3">
                                                    <label class="form-label fw-semibold text-muted-dark">Nomor
                                                        Telepon</label>
                                                </div>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" name="no_telp"
                                                        placeholder="Nomor Telepon"
                                                        value="{{ old('no_telp', $penyuluh->no_telp) }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row row-sm">
                                                <div class="col-md-3">
                                                    <label class="form-label fw-semibold text-muted-dark">Alamat</label>
                                                </div>
                                                <div class="col-md-9">
                                                    <textarea class="form-control" name="alamat" rows="2">{{ old('alamat', $penyuluh->alamat) }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane p-0" id="style6tab3">
                            <div class="card overflow-hidden border-0">
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
                                    <form action="{{ url('/penyuluh/pengaturan/editPassword/' . $penyuluh->id) }}"
                                        method="POST" class="form-horizontal" enctype="multipart/form-data">
                                        @method('PUT')
                                        @csrf
                                        <div class="d-flex">
                                            <div class="ms-auto">
                                                <button type="submit" class="btn btn-sm btn-success mx-1">Update
                                                    Password</button>
                                                <button type="reset" class="btn btn-sm btn-danger">Cancel</button>
                                            </div>
                                        </div>
                                        <h6 class="text-uppercase fw-semibold mb-3">Ubah Password</h6>
                                        <div class="form-group">
                                            <div class="row row-sm">
                                                <div class="col-md-3">
                                                    <label class="form-label fw-semibold text-muted-dark">Password
                                                        Lama</label>
                                                </div>
                                                <div class="col-md-9">
                                                    <input type="password" class="form-control" name="password_lama"
                                                        placeholder="Masukan Password Lama" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row row-sm">
                                                <div class="col-md-3">
                                                    <label class="form-label fw-semibold text-muted-dark">Password
                                                        Baru</label>
                                                </div>
                                                <div class="col-md-9">
                                                    <input type="password" class="form-control" name="password_baru"
                                                        placeholder="Masukan Password Baru" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row row-sm">
                                                <div class="col-md-3">
                                                    <label class="form-label fw-semibold text-muted-dark">Konfirmasi
                                                        Password</label>
                                                </div>
                                                <div class="col-md-9">
                                                    <input type="password" class="form-control"
                                                        name="konfirmasi_password" placeholder="Konfirmasi Password"
                                                        required>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
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
