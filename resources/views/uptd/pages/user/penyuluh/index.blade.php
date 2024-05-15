@extends('index')
@section('title', 'Akun Penyuluh')
@section('content')
    <div class="page-header d-sm-flex d-block">
        <ol class="breadcrumb mb-sm-0 mb-3">
            <!-- breadcrumb -->
            <li class="breadcrumb-item"><a href="{{ url('/uptd/user/penyuluh') }}">{{ $breadcrumb }}
                </a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $breadcrumb_active }}</li>
        </ol><!-- End breadcrumb -->
        <div class="ms-auto">
            <div>
                <a href="{{ url('/uptd/pengguna/penyuluh/create') }}" class="btn bg-primary-transparent">
                    <span>
                        <i class="fa fa-plus"></i>
                    </span>
                    {{ $button_create }}
                </a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
                <div class="card-header">
                    <h3 class="card-title">{{ $title }}</h3>
                    <div class="dropdown ms-auto">
                        <button class="btn btn-outline-primary fw-bold text-primary d-flex align-items-center"
                            type="button" data-bs-toggle="modal" data-bs-target="#largeModal">
                            <i class="bi bi-calendar-date fw-semibold mx-1"></i> Penugasan
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-nowrap border-bottom" id="responsive-datatable">
                            <thead>
                                <tr>
                                    <th class="wd-15p border-bottom-0">No</th>
                                    <th class="wd-15p border-bottom-0">Nama</th>
                                    <th class="wd-20p border-bottom-0">Email</th>
                                    <th class="wd-15p border-bottom-0">Role</th>
                                    <th class="wd-15p border-bottom-0">Penugasan</th>
                                    <th class="text-center wd-10p border-bottom-0">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($penyuluh as $data)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $data->user->name }}</td>
                                        <td>{{ $data->user->email }}</td>
                                        <td>{{ $data->user->getAkses->name }}</td>
                                        <td>
                                            @if ($data['penugasan'])
                                                @foreach ($penugasan as $tugas)
                                                    <span class="badge bg-primary">{{ $tugas->desa->name }}</span>
                                                @endforeach
                                            @elseif ($data['penugasan'] === null)
                                                <span class="badge bg-warning">Belum ada penugasan</span>
                                            @else
                                                <span class="badge bg-danger">Error: penugasan tidak ditemukan</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ url('/operator/user/penyuluh/' . encrypt($data->id) . '/edit') }}"
                                                class="btn btn-warning"><i class="fa fa-edit"></i></a>
                                            <a href="{{ url('/operator/user/penyuluh/' . encrypt($data->id)) }}"
                                                class="btn btn-primary">
                                                <i class="ti ti-eye"></i></a>
                                            <form id="deleteForm{{ $data->id }}"
                                                action="{{ url('/operator/user/penyuluh/' . $data->id) }}"
                                                style="display: inline;" method="POST">
                                                @method('DELETE')
                                                @csrf
                                                <button type="button" class="btn btn-danger deleteBtn"
                                                    data-id="{{ $data->id }}"><i class="ti ti-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="largeModal" class="modal fade">
        <div class="modal-dialog modal-lg" role="document">
            <form action="{{ url('/uptd/pengguna/penyuluh/penugasan/') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content ">
                    <div class="modal-header pd-x-20">
                        <h6 class="modal-title">Tambah Penugasan</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <div class="modal-body pd-20">
                        <div class="form-group">
                            <label class="form-label">Penyuluh</label>
                            <select name="name" id="name" class="form-control select2 form-select"
                                data-placeholder="Pilih Pengguna">
                                <option value="">-- pilih --</option>
                                @foreach ($penyuluh as $item)
                                    <option value="{{ $item->user->id }}">
                                        {{ $item->user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Desa</label>
                            <select name="desa[]" id="desa" multiple="multiple" class="multi-select"
                                data-placeholder="Pilih Desa">
                                @foreach ($desa as $data)
                                    <option value="{{ $data->id }}">
                                        {{ $data->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <table class="table table-striped" style="width: 100%">
                            <tr>
                                <th> Nama Desa</th>
                                <th> Action</th>
                            </tr>
                            <tbody>
                                <tr>
                                    <td>Desa</td>
                                    <td>
                                        <button class="btn btn-danger"><i class="fa fa-trash"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary br-7">Save changes</button>
                        <button type="reset" class="btn btn-secondary br-7" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div><!-- modal-dialog -->
    </div><!-- modal -->

@endsection
