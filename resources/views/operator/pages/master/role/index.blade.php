@extends('index')
@section('title', 'Master Role | Operator')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div
            class="card-header sticky-element bg-label-secondary d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row py-3 mb-4">
            <h5 class="card-title mb-sm-0 me-2"><span class="text-muted fw-light">Dashboard /</span> Role Table
            </h5>
            <div class="action-btns">
                <button class="btn btn-primary fa fa-plus" data-bs-toggle="modal" data-bs-target="#modalCenter"> Add New
                    Role</button>
            </div>
        </div>
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
            <h5 class="card-header">{{ $title }}</h5>
            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead class="table-light">
                        <tr>
                            <th>ID Role</th>
                            <th>Nama</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse ($role as $data)
                            <tr>
                                <td>{{ $data->id }}</td>
                                <td>{{ $data->name }}</td>
                                <td class="text-center">
                                    <a href="{{ url('/operator/master/role/' . $data->id . '/edit') }}"
                                        class="btn btn-warning"><i class="ti ti-edit"></i></a>
                                    <form id="deleteForm{{ $data->id }}"
                                        action="{{ url('/operator/master/role/' . $data->id) }}" style="display: inline;"
                                        method="POST">
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
                <form action="{{ url('/operator/master/role') }}" method="post">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCenterTitle">Masukan Nama Role</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="col mb-3">
                            <label for="nameBasic" class="form-label">Nama</label>
                            <input type="text" name="name" class="form-control"
                                placeholder="Masukkan Nama Role/Peran" />
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
@endsection
