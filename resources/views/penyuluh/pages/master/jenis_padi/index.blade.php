@extends('index')
@section('title', 'Master Data Padi | Penyuluh')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div
            class="card-header sticky-element bg-label-secondary d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row py-3 mb-4">
            <h5 class="card-title mb-sm-0 me-2"><span class="text-muted fw-light">Dashboard /</span> Data Jenis Padi
            </h5>
            <div class="action-btns">
                <button class="btn btn-primary fa fa-plus" data-bs-toggle="modal" data-bs-target="#modalCenter"> Add New
                    Kelas</button>
            </div>
        </div>
        <!-- Basic Bootstrap Table -->
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

            <h5 class="card-header">Data Jenis Padi</h5>
            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th class="text-center">Nama Jenis Padi</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse ($padi as $data)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="text-center">{{ $data->nama_padi }}</td>
                                <td class="text-center">
                                    <a href="{{ url('/penyuluh/master/jenis_padi/' . $data->id) }}" class="btn btn-warning"
                                        data-bs-toggle="modal" data-bs-target="#modalCenter1{{ $data->id }}"><i
                                            class="ti ti-pencil"></i></a>
                                    <form id="deleteForm{{ $data->id }}"
                                        action="{{ url('/penyuluh/master/jenis_padi/' . $data->id) }}"
                                        style="display: inline;" method="POST">
                                        @method('DELETE')
                                        @csrf
                                        <button type="button" class="btn btn-danger deleteBtn"
                                            data-id="{{ $data->id }}"><i class="ti ti-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Data Kosong</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- start modal tambah Jenis Padi --}}
    <div class="modal fade" id="modalCenter" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="{{ url('/penyuluh/master/jenis_padi') }}" method="post">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCenterTitle">Inpu Data Jenis Padi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="col mb-3">
                            <label for="nameBasic" class="form-label">Nama Jenis Padi</label>
                            <input type="text" name="name" class="form-control" placeholder="Masukkan Nama Kelas" />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <!-- Tambahkan tombol Submit atau yang sesuai dengan kebutuhan Anda -->
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- end modal tambah Jenis Padi --}}

    {{-- start modal edit jenis Padi --}}
    @foreach ($padi as $data)
        <div class="modal fade" id="modalCenter1{{ $data->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <form action="{{ url('/penyuluh/master/jenis_padi/' . $data->id) }}" method="post">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalCenterTitle">Input Data Jenis Padi</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="col mb-3">
                                <label for="nameBasic" class="form-label">Nama Jenis Padi</label>
                                <input type="text" name="name" value="{{ $data->nama_padi }}" class="form-control"
                                    placeholder="Masukkan Nama Kelas" />
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
    {{-- end modal edit Jenis Padi --}}
@endsection
