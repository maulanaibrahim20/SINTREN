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
                    <form action="{{ url('/pertanian/prediksi/padi ') }}" method="post">
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
                                        <option value="puso/rusak">Puso/Rusak</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            {{-- <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Dari Bulan</label>
                                    <select class="form-control select2 form-select" name="dariBulan"
                                        data-placeholder="Dari Bulan" required>
                                        <option label="Dari Bulan"></option>
                                        <option value="januari">Januari</option>
                                        <option value="februari">Februari</option>
                                        <option value="maret">Maret</option>
                                        <option value="april">April</option>
                                        <option value="mei">Mei</option>
                                        <option value="juni">Juni</option>
                                        <option value="juli">Juli</option>
                                        <option value="agustus">Agustus</option>
                                        <option value="september">September</option>
                                        <option value="oktober">Oktober</option>
                                        <option value="november">November</option>
                                        <option value="desember">Desember</option>
                                    </select>
                                </div>
                            </div> --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Tahun</label>
                                    <select class="form-control select2 form-select" name="dariTahun"
                                        data-placeholder="Dari Tahun" required>
                                        <option label="Dari Tahun"></option>
                                        @for ($year = 2013; $year <= 2023; $year++)
                                            <option value="{{ $year }}">{{ $year }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            {{-- <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Sampai Bulan</label>
                                    <select class="form-control select2 form-select" name="sampaiBulan"
                                        data-placeholder="Dari Bulan" required>
                                        <option label="Dari Bulan"></option>
                                        <option value="januari">Januari</option>
                                        <option value="februari">Februari</option>
                                        <option value="maret">Maret</option>
                                        <option value="april">April</option>
                                        <option value="mei">Mei</option>
                                        <option value="juni">Juni</option>
                                        <option value="juli">Juli</option>
                                        <option value="agustus">Agustus</option>
                                        <option value="september">September</option>
                                        <option value="oktober">Oktober</option>
                                        <option value="november">November</option>
                                        <option value="desember">Desember</option>
                                    </select>
                                </div>
                            </div> --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Tahun</label>
                                    <select class="form-control select2 form-select" name="sampaiTahun"
                                        data-placeholder="Sampai Tahun" required>
                                        <option label="Sampai Tahun"></option>
                                        @for ($year = 2013; $year <= 2023; $year++)
                                            <option value="{{ $year }}">{{ $year }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary mt-3" type="submit"><i class="fa fa-sign-in"></i>Trend
                            Projection</button>
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
