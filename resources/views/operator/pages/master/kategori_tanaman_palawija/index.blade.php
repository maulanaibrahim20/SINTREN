@extends('index')
@section('title', 'Kategori Tanaman Palawija || Operator')
@section('content')
    <div class="page-header d-sm-flex d-block">
        <ol class="breadcrumb mb-sm-0 mb-3">
            <!-- breadcrumb -->
            <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
            <li class="breadcrumb-item" aria-current="page">Master</li>
            <li class="breadcrumb-item active" aria-current="page">Kategori Tanaman Palawija</li>
        </ol><!-- End breadcrumb -->
        <div class="ms-auto">
            <div>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCenter">
                    <span>
                        <i class="fa fa-plus"></i>
                    </span>
                    Buat Kategori Palawija
                </button>
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
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-nowrap border-bottom" id="responsive-datatable">
                            <thead>
                                <tr>
                                    <th class="wd-15p border-bottom-0">No.</th>
                                    <th class="wd-15p border-bottom-0">Nama</th>
                                    <th class="wd-15p border-bottom-0">Deskripsi</th>
                                    <th class="wd-15p border-bottom-0">Gambar</th>
                                    <th class="text-center wd-10p border-bottom-0">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tanaman as $data)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $data->name }}</td>
                                        <td>{{ $data->description }}</td>
                                        <td><img src="{{ asset('' . $data->image) }}" style="width:60px;height:60"></td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                                data-bs-target="#modalCenter1{{ $data->id }}"><i
                                                    class="fa fa-edit"></i></button>
                                            <form id="deleteForm{{ $data->id }}"
                                                action="{{ url('/operator/master/kategori_tanaman_palawija/' . $data->id) }}"
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

    {{-- start modal tambah Jenis Padi --}}
    <div class="modal fade" id="modalCenter" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="{{ url('/operator/master/kategori_tanaman_palawija') }}" enctype="multipart/form-data"
                    method="post">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCenterTitle">Tambah kategori tanaman palawija</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="col mb-3">
                            <label for="nameBasic" class="form-label">Nama</label>
                            <input type="text" name="name" class="form-control"
                                placeholder="Masukkan Nama Kategori" />
                        </div>
                        <div class="col mb-3">
                            <label for="nameBasic" class="form-label">Description</label>
                            <input type="text" name="description" class="form-control"
                                placeholder="Masukkan Nama Deskripsi" />
                        </div>
                        <div class="col mb-3">
                            <label for="nameBasic" class="form-label">Foto</label>
                            <input type="file" name="image" class="form-control" />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @foreach ($tanaman as $item)
        <div class="modal fade" id="modalCenter1{{ $item->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <form action="{{ url('/operator/master/kategori_tanaman_palawija') }}" enctype="multipart/form-data"
                        method="post">
                        @method('PUT')
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalCenterTitle">Tambah kategori tanaman palawija</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="col mb-3">
                                <label for="nameBasic" class="form-label">Nama</label>
                                <input type="text" value="{{ $item->name }}" name="name" class="form-control"
                                    placeholder="Masukkan Nama Kategori" />
                            </div>
                            v<div class="col mb-3">
                                <label for="descriptionBasic" class="form-label">Deskripsi</label>
                                <textarea name="description" class="form-control" placeholder="Masukkan Deskripsi">{{ $item->description }}</textarea>
                            </div>
                            <div class="col mb-3">
                                <label for="nameBasic" class="form-label">Foto</label><br>
                                @if ($item->image)
                                    <img src="{{ asset($item->image) }}" alt="Foto Kategori"
                                        style="max-width: 100px;"><br><br>
                                @endif
                                <input type="file" name="image" class="form-control" />
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
    {{-- end modal tambah Jenis Padi --}}
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
