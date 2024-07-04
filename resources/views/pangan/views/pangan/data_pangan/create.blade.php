@extends('index')
@section('title', 'Tambah Data Stok Pangan')
@section('content')
    <div class="page-header d-sm-flex d-block">
        <ol class="breadcrumb mb-sm-0 mb-3">
            <!-- breadcrumb -->
            <li class="breadcrumb-item1"><a href="{{ url('/pangan/dashboard') }}">{{ $breadcrumb }}</a></li>
            <li class="breadcrumb-item1"><a href="{{ url('/pangan/create/data_pangan') }}">{{ $breadcrumb_1 }}</a></li>
            <li class="breadcrumb-item1 active">{{ $breadcrumb_active }}</li>
        </ol><!-- End breadcrumb -->
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
                    <form action="{{ url('/pangan/create/data_pangan') }}" method="POST" id="myForm">
                        <div class="card-body">
                            @csrf
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="select2Basic" class="form-label">Pilih Tanggal</label>
                                        <div class="input-group">
                                            <div class="input-group-text">
                                                <i class="typcn typcn-calendar-outline tx-24 lh--9 op-6"></i>
                                            </div>
                                            <input type="text" class="form-control" name="date" id="date"
                                                placeholder="Pilih Tanggal">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="select2Basic" class="form-label">Pasar</label>
                                        <select id="pasar" class="form-control form-select select2"
                                            aria-label="Default select example" name="pasar_id">
                                            <option value="">-- Pilih --</option>
                                            @foreach ($pasar as $item)
                                                <option value="{{ $item->id }}">
                                                    {{ $item->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="select2Basic" class="form-label">Jenis Pangan</label>
                                        <select id="jenispangan" class="form-control form-select select2"
                                            aria-label="Default select example" name="jenis_pangan_id">
                                            <option value="">-- Pilih --</option>
                                            @foreach ($jenispangan as $item)
                                                <option value="{{ $item->id }}">
                                                    {{ $item->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Nama Pangan</label>
                                    <input type="text" class="form-control" name="name">
                                </div>
                                </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Kebutuhan(TON)</label>
                                    <input type="number" class="form-control" name="kebutuhan">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Ketersediaan (TON)</label>
                                    <input type="number" class="form-control" name="ketersediaan">
                                </div>
                            </div>
                            </div>
                            <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Neraca (TON)</label>
                                    <input type="number" class="form-control" name="neraca">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Harga (Rp/Kg)</label>
                                    <input type="number" class="form-control" name="harga">
                                </div>
                            </div>
                            </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="col-md-12">
                                <button type="reset" class="btn ripple btn-warning mr-3">Cancel</button>
                                <button type="submit" class="btn ripple btn-success mr-3">Kirim</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('script')
    <script>
        $('.deleteBtn').on('click', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var deleteForm = $('#deleteForm' + id);

            Swal.fire({
                title: 'Anda yakin?',
                text: "Data akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteForm.submit();
                }
            });
        });
    </script>
@endsection
