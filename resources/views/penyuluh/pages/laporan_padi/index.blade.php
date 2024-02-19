@extends('index')
@section('title', 'Laporan Padi | Penyuluh')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div
            class="card-header sticky-element bg-label-secondary d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row py-3 mb-4">
            <h5 class="card-title mb-sm-0 me-2"><span class="text-muted fw-light">Dashboard /</span> Laporan Padi
            </h5>
            <div class="action-btns">
                <a href="{{ url('/penyuluh/create/laporan_padi/create') }}" class="btn btn-primary fa fa-plus"> Add New
                    Laporan</a>
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

            <h5 class="card-header">Data Laporan Luas Tanaman Padi</h5>
            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Kelas</th>
                            <th>Gedung</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        {{-- @forelse ($kelas as $data)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $data->kelas }}</td>
                                <td>{{ $data->gedung }}</td>
                                <td class="text-center">
                                    <form id="deleteForm{{ $data->id }}"
                                        action="{{ url('/koordinator/create/kelas/' . $data->id) }}"
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
                        @endforelse --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
