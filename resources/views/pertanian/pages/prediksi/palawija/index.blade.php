@extends('index')
@section('title', 'Prediksi Padi')
@section('content')
    <div class="page-header d-sm-flex d-block">
        <ol class="breadcrumb mb-sm-0 mb-3">
            <!-- breadcrumb -->
            <li class="breadcrumb-item"><a href="index.html">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
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
                    <form action="{{ url('/pertanian/prediksi/palawija ') }}" method="post">
                        @csrf
                        <div class="form-row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">Pilih Jenis Data</label>
                                    <select class="form-control select2 form-select" name="tipeData"
                                        data-placeholder="Pilih Tipe Data" required>
                                        <option label="Pilih Tipe Data"></option>
                                        <option value="tanam">Tanam</option>
                                        <option value="panen">Panen</option>
                                        <option value="panen muda">Panen Muda</option>
                                        <option value="panen hijauan pakan ternak">Panen Hijauan Pakan Ternak</option>
                                        <option value="puso/rusak">Puso/Rusak</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Dari Tahun</label>
                                    <select class="form-control select2 form-select" name="dariTahun"
                                        data-placeholder="Dari Tahun" required>
                                        <option label="Dari Tahun"></option>
                                        @for ($year = $tahunTerkecil; $year <= $tahunTerbesar; $year++)
                                            <option value="{{ $year }}">{{ $year }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Sampai Tahun</label>
                                    <select class="form-control select2 form-select" name="sampaiTahun"
                                        data-placeholder="Sampai Tahun" required>
                                        <option label="Sampai Tahun"></option>
                                        @for ($year = $tahunTerkecil; $year <= $tahunTerbesar; $year++)
                                            <option value="{{ $year }}">{{ $year }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>

                            <button class="btn btn-primary mt-3" type="submit"><i class="fa fa-sign-in"></i>Trend
                                Projection</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>
@endsection
