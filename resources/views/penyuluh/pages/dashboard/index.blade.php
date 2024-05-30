@extends('index')
@section('title', 'Dashboard ')
@section('content')
    <div class="page-header d-sm-flex d-block">
        <ol class="breadcrumb br-7">
            <li class="breadcrumb-item1 active">Dashboard</li>
        </ol>
    </div>
    <div class="row">
        @forelse ($penugasan as $tugas)
            @php
                $desaId = $tugas->desa_id;
                $perbandingan = $perbandinganNilai[$desaId] ?? ['sawah' => 0, 'non_sawah' => 0, 'has_value' => false];
            @endphp
            <div class="col-xl-6 col-md-6 col-lg-6 col-sm-6 m-b-3">
                <div class="card">
                    <div class="">
                        <div class="row">
                            <!-- row -->
                            <div class="col-12">
                                <div class="p-2 bg-primary br-tr-7 br-tl-7">
                                    <div class="text-center text-white social mt-3">
                                        <h4> Desa {{ $tugas->desa->name }}</h4>
                                        @if ($perbandingan['has_value'])
                                            <p>Data Tersedia</p>
                                        @else
                                            <p>Data Tidak Tersedia</p>
                                        @endif
                                    </div>
                                </div>
                                @if ($perbandingan['has_value'])
                                    <div class="row">
                                        <div class="col-md-6 mt-7 chart-circle chart-circle-md donutShadow"
                                            data-value="{{ $perbandingan['sawah'] / 100 }}" data-thickness="20"
                                            data-color="#467fcf ">
                                            <div class="chart-circle-value fs"><i class="fa fa-share-square-o"></i>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mt-7 chart-circle chart-circle-md donutShadow"
                                            data-value="{{ $perbandingan['non_sawah'] / 100 }}" data-thickness="20"
                                            data-color="#467fcf ">
                                            <div class="chart-circle-value fs"><i class="fa fa-share-square-o"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body mt-4">
                                        <div class="d-flex  align-items-center">
                                            <div>
                                                <h4 class="font-medium mb-1">{{ round($perbandingan['sawah'], 2) }}% - 100%
                                                </h4>
                                                <p class="mb-0"><span class="text-primary"><i
                                                            class="fa fa-plus me-1"></i>Sawah</span></p>
                                            </div>
                                            <div class="ms-auto">
                                                <h4 class="font-medium mb-1">{{ round($perbandingan['non_sawah'], 2) }}% -
                                                    100%
                                                </h4>
                                                <p class=" mb-0"><span class="text-success"><i class="fa fa-plus me-1"></i>
                                                        Non Sawah</span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="card-body mt-4">
                                        <p>Belum ada data laporan padi untuk desa ini.</p>
                                    </div>
                                @endif
                            </div>
                        </div><!-- row end -->
                    </div>
                </div>
            </div><!-- col end -->
        @empty
            <p>Belum ada penugasan</p>
        @endforelse
    </div>

@endsection
