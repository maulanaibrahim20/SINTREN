<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card-header sticky-element bg-label-secondary d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row py-3 mb-4">
        <h5 class="card-title mb-sm-0 me-2"><span class="text-muted fw-light">Dashboard /</span> Pengguna Pertanian
        </h5>
        <div class="action-btns">
            <a href="{{ url('/operator/user/pertanian/create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Add
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
                            <a href="{{ url('/operator/user/pertanian/' . $data->id . '/edit') }}" class="btn btn-warning"><i class="ti ti-edit"></i></a>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCenter{{ $data->id }}">
                                <i class="ti ti-eye"></i></button>
                            <form id="deleteForm{{ $data->id }}" action="{{ url('/operator/user/pertanian/' . $data->id) }}" style="display: inline;" method="POST">
                                @method('DELETE')
                                @csrf
                                <button type="button" class="btn btn-danger deleteBtn" data-id="{{ $data->id }}"><i class="ti ti-trash"></i></button>
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
