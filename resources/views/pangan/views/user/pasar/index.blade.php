@extends('index')
@section('title', 'Data Pengguna Pasar')
@section('content')
    <div class="page-header d-sm-flex d-block">
        <ol class="breadcrumb1 br-7">
            <!-- breadcrumb -->
            <li class="breadcrumb-item1"><a href="{{ url('/pangan/dashboard') }}">{{ $breadcrumb }}</a></li>
            <li class="breadcrumb-item1 active">{{ $breadcrumb_active }}</li>
        </ol><!-- End breadcrumb ---->
        <div class="ms-auto">
            <div>
                <a href="{{ url('/pangan/user/pasar/create') }}" class="btn bg-primary-transparent">
                    <span>
                        <i class="fa fa-plus"></i>
                    </span>
                    {{ $add_button }}
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
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped text-nowrap border-bottom" id="responsive-datatable">
                            <thead>
                                <tr>
                                    <th class="wd-15p border-bottom-0">No</th>
                                    <th class="wd-15p border-bottom-0">Nama</th>
                                    <th class="wd-20p border-bottom-0">Username</th>
                                    {{-- <th class="wd-20p border-bottom-0">Password Default</th> --}}
                                    <th class="wd-15p border-bottom-0">Role</th>
                                    <th class="wd-15p border-bottom-0">Pasar</th>
                                    <th class="text-center wd-10p border-bottom-0">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $data)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $data->user->name }}</td>
                                        <td>{{ $data->user->username }}</td>
                                        {{-- <td>password</td> --}}
                                        <td>{{ $data->user->getAkses->name }}</td>
                                        <td><span class="badge bg-primary">{{ $data->pasar->name }}</span></td>
                                        <td class="text-center">
                                            <a href="{{ url('/pangan/user/pasar/' . encrypt($data->id) . '/edit') }}"
                                                class="btn btn-warning"><i class="fa fa-edit"></i></a>
                                            <a href="{{ url('/pangan/user/pasar/' . encrypt($data->id)) }}"
                                                class="btn btn-primary">
                                                <i class="ti ti-eye"></i></a>
                                            <form id="deleteForm{{ $data->id }}"
                                                action="{{ url('/pangan/user/pasar/' . $data->id) }}"
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
