@extends('index')
@section('title', 'Edit Akun Penyuluh')
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
                    <form action="{{ url('/uptd/pengguna/penyuluhUptd/' . $edit['id']) }}" method="post">
                        @method('PUT')
                        @csrf
                        <div class="form-row">
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mb-3">
                                <label>Nama Lengkap</label>
                                <input type="text" class="form-control" name="name"
                                    value="{{ $edit['user']['name'] }}" required>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mb-3">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control"
                                    value="{{ $edit['user']['email'] }}" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mb-3">
                                <label for="validationCustom13">Alamat</label>
                                <input type="text" class="form-control" name="alamat" value="{{ $edit['alamat'] }}"
                                    required>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mb-3">
                                <label>Nomor Telepon</label>
                                <input type="number" class="form-control" name="no_telp" value="{{ $edit['no_telp'] }}"
                                    required>
                            </div>
                        </div>
                        @include('template.component.button')
                    </form>

                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $title_1 }}</h3>
                </div>
                <div class="card-body">
                    <form action="{{ url('/uptd/pengguna/penyuluh/penugasan/' . $edit->user_id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label class="form-label">Pilih Desa Penugasan</label>
                            <select multiple class="form-control select2-show-search form-select"
                                data-placeholder="Choose one" name="penugasan[]">
                                @foreach ($desa as $d)
                                    <option value="{{ $d['desa']['id'] }}"
                                        {{ in_array($d['desa']['id'], $assigned_desa_ids) ? 'selected' : '' }}>
                                        {{ $d['desa']['name'] }}
                                    </option>
                                @endforeach
                            </select>
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
