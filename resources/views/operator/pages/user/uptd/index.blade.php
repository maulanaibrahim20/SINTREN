@extends('index')
@section('title', 'UPTD | Operator')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div
            class="card-header sticky-element bg-label-secondary d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row py-3 mb-4">
            <h5 class="card-title mb-sm-0 me-2"><span class="text-muted fw-light">Dashboard /</span> Pengguna UPTD
            </h5>
            <div class="action-btns">
                <a href="{{ url('/operator/user/uptd/create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Add
                    New User</a>
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
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse ($users as $data)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $data->user->name }}</td>
                                <td>{{ $data->user->email }}</td>
                                <td>{{ $data->user->getAkses->name }}</td>
                                <td class="text-center">
                                    <a href="{{ url('/operator/user/uptd/' . $data->id . '/edit') }}"
                                        class="btn btn-warning"><i class="ti ti-edit"></i></a>
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#modalCenter{{ $data->id }}">
                                        <i class="ti ti-eye"></i></button>
                                    <form id="deleteForm{{ $data->id }}"
                                        action="{{ url('/operator/user/uptd/' . $data->id) }}" style="display: inline;"
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
    @foreach ($users as $view)
        <div class="modal fade" id="modalCenter{{ $view->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCenterTitle">Detail Akun : {{ $view->user->name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th scope="row">Nama</th>
                                    <td>{{ $view->user->name }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Username</th>
                                    <td>{{ $view->user->username }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Email</th>
                                    <td>{{ $view->user->email }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Role</th>
                                    <td>{{ $view->user->getAkses->name }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Status</th>
                                    <td>
                                        @if ($view->user->email_verified_at)
                                            <span class="badge bg-primary">Terverifikasi</span>
                                        @else
                                            <span class="badge bg-warning">Belum Terverifikasi</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">Nomor Telepon</th>
                                    <td>{{ $view->no_telp }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection
@section('script')
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
@endsection
