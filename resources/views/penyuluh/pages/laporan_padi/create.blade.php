@extends('index')
@section('title', 'Tambah Laporan Padi | Penyuluh')
@section('content')
    <div class="main-container container-fluid">

        <!-- page-header -->
        <div class="page-header d-sm-flex d-block">
            <ol class="breadcrumb mb-sm-0 mb-3">
                <!-- breadcrumb -->
                <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Laporan Padi</li>
            </ol><!-- End breadcrumb -->
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Form Tambah Laporan</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ url('/penyuluh/create/laporan_padi') }}" method="POST"
                            enctype="multipart/form-data" id="form">
                            @csrf
                            <div class="list-group">
                                <div class="list-group-item py-3" data-acc-step>
                                    <h5 class="mb-0" data-acc-title>Pilih Jenis Lahan</h5>
                                    <div data-acc-content>
                                        <div class="my-3">
                                            <div class="form-group">
                                                <label class="custom-control custom-radio">
                                                    <input type="radio" class="custom-control-input" name="jenis_lahan"
                                                        value="lahan_sawah" checked>
                                                    <span class="custom-control-label">Lahan Sawah</span>
                                                </label>
                                            </div>
                                            <div class="form-group">
                                                <label class="custom-control custom-radio">
                                                    <input type="radio" class="custom-control-input" name="jenis_lahan"
                                                        value="non_sawah">
                                                    <span class="custom-control-label">Non Sawah</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="list-group-item py-3" data-acc-step>
                                    <h5 class="mb-0" data-acc-title>Laporan Lahan Sawah</h5>
                                    <div data-acc-content>
                                        <div class="my-3">
                                            <div class="form-group">
                                                <label for="select2Basic" class="form-label">Desa</label>
                                                <select id="desa" class="form-control form-select select2"
                                                    aria-label="Default select example" name="desa">
                                                    <option value="">-- Pilih --</option>
                                                    @foreach ($desa as $item)
                                                        <option value="{{ $item->id }}" name="desa">
                                                            {{ $item->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="select2Basic" class="form-label">Jenis Padi</label>
                                                <select id="jenis_padi" class="form-control form-select select2"
                                                    aria-label="Default select example" name="jenis_padi">
                                                    <option value="">-- Pilih --</option>
                                                    @foreach ($jenis_padi as $data)
                                                        <option value="{{ $data->id }}" name="jenis_padi">
                                                            {{ $data->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="select2Basic" class="form-label">Jenis Bantuan</label>
                                                <select class="form-control form-select select2" name="jenis_bantuan">
                                                    <option value="">-- Pilih --</option>
                                                    <option value="bantuan pemerintah">Bantuan Pemerintah</option>
                                                    <option value="non bantuan pemerintah">Non Bantuan Pemerintah</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="list-group-item py-3" data-acc-step>
                                    <h5 class="mb-0" data-acc-title>Lahan</h5>
                                    <div data-acc-content>
                                        <div class="my-3">
                                            <div class="form-group">
                                                <label>Tanaman Akhir Bulan Lalu : </label>
                                                <input type="text" name="tanaman_akhir_bulan_lalu" class="form-control"
                                                    id="tanaman_akhir_bulan_lalu">
                                            </div>
                                            <div class="form-group form-row">
                                                <div class="col-sm-4">
                                                    <label>Panen:</label>
                                                    <input type="text" name="panen" class="form-control"
                                                        id="panen">
                                                </div>
                                                <div class="col-sm-4">
                                                    <label>Tanam : </label>
                                                    <input type="text" name="tanam" class="form-control"
                                                        id="tanam">
                                                </div>
                                                <div class="col-sm-4">
                                                    <label>Puso/Rusak : </label>
                                                    <input type="text" name="rusak" class="form-control"
                                                        id="puso_rusak">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>Tanaman Akhir Bulan Laporan : </label>
                                                <input type="text" name="tanam_akhir_bulan_laporan" class="form-control"
                                                    id="tanaman_akhir_bulan_laporan" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Bagian Pengairan -->
                                <div class="list-group-item py-3" data-acc-step>
                                    <h5 class="mb-0" data-acc-title>Pengairan</h5>
                                    <div data-acc-content>
                                        <div class="my-3">
                                            <div class="form-group form-row">
                                                <label for="select2Basic" class="form-label">Jenis Pengairan</label>
                                                <select id="pengairan" class="form-control form-select select2"
                                                    aria-label="Default select example" name="pengairan">
                                                    <option value="">-- Pilih --</option>
                                                    @foreach ($pengairan as $pengairans)
                                                        <option value="{{ $pengairans->id }}" name="pengairan">
                                                            {{ $pengairans->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group form-row">
                                                <div class="col-sm-4">
                                                    <label>Tanaman Akhir bulan lalu:</label>
                                                    <input type="text" name="tanaman_akhir_bulan_lalu_pengairan"
                                                        class="form-control" id="tanaman_akhir_bulan_lalu_pengairan">
                                                </div>
                                                <div class="col-sm-4">
                                                    <label>Panen:</label>
                                                    <input type="text" name="panen_pengairan" class="form-control"
                                                        id="panen_pengairan">
                                                </div>
                                                <div class="col-sm-4">
                                                    <label>Tanam : </label>
                                                    <input type="text" name="tanam_pengairan" class="form-control"
                                                        id="tanam_pengairan">
                                                </div>
                                                <div class="col-sm-4">
                                                    <label>Puso/Rusak : </label>
                                                    <input type="text" name="rusak_pengairan" class="form-control"
                                                        id="rusak_pengairan">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>Tanaman Akhir Bulan Laporan : </label>
                                                <input type="text" name="tanam_akhir_bulan_laporan_pengairan"
                                                    class="form-control" id="tanam_akhir_bulan_laporan_pengairan"
                                                    readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="list-group-item py-3" data-acc-step>
                                    <h5 class="mb-0" data-acc-title>Ringkasan Inputan Lahan</h5>
                                    <div data-acc-content>
                                        <div class="my-3" id="summary">
                                            <!-- Tempat untuk menampilkan ringkasan -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @section('script')
        <script>
            $(document).ready(function() {
                var tanamanAkhirBulanLalu = 0;
                var panen = 0;
                var tanam = 0;
                var puso = 0;
                var tanamanAkhirBulanIni = 0;

                var tanamanAkhirBulanLaluPengairan = 0;
                var panen_pengairan = 0;
                var tanam_pengairan = 0;
                var puso_pengairan = 0;
                var tanamanAkhirBulanIniPengairan = 0;

                $("#tanaman_akhir_bulan_lalu").change(function() {
                    tanamanAkhirBulanLalu = parseInt($(this).val());
                    hitungTanamanAkhirBulanIni();
                });

                $("#panen").change(function() {
                    panen = parseInt($(this).val());
                    hitungTanamanAkhirBulanIni();
                });

                $("#tanam").change(function() {
                    tanam = parseInt($(this).val());
                    hitungTanamanAkhirBulanIni();
                });

                $("#puso_rusak").change(function() {
                    puso = parseInt($(this).val());
                    hitungTanamanAkhirBulanIni();
                });

                function hitungTanamanAkhirBulanIni() {
                    tanamanAkhirBulanIni = tanamanAkhirBulanLalu - panen + tanam - puso;
                    $("#tanaman_akhir_bulan_laporan").val(tanamanAkhirBulanIni);
                    console.log("tanaman akhir bulan ini: " + tanamanAkhirBulanIni);
                }

                $("#tanaman_akhir_bulan_lalu_pengairan").change(function() {
                    tanamanAkhirBulanLaluPengairan = parseInt($(this).val());
                    hitungTanamanAkhirBulanIniPengairan();
                });

                $("#panen_pengairan").change(function() {
                    panen_pengairan = parseInt($(this).val());
                    hitungTanamanAkhirBulanIniPengairan();
                });

                $("#tanam_pengairan").change(function() {
                    tanam_pengairan = parseInt($(this).val());
                    hitungTanamanAkhirBulanIniPengairan();
                });


                $("#rusak_pengairan").change(function() {
                    puso_pengairan = parseInt($(this).val());
                    hitungTanamanAkhirBulanIniPengairan();
                });

                function hitungTanamanAkhirBulanIniPengairan() {
                    tanamanAkhirBulanIniPengairan = tanamanAkhirBulanLaluPengairan - panen_pengairan + tanam_pengairan -
                        puso_pengairan;
                    $("#tanam_akhir_bulan_laporan_pengairan").val(tanamanAkhirBulanIniPengairan);
                    console.log("tanaman akhir bulan ini: " + tanamanAkhirBulanIniPengairan);
                }

                $('input[type="text"]').on('input', function() {
                    this.value = this.value.replace(/[^0-9]/g, '');
                });

                $('input, select').on('change', function() {
                    showSummary();
                });
                $('input[name="jenis_lahan"]').on('change', function() {
                    if ($(this).val() === 'lahan_sawah') {
                        $('#lahan_sawah').show();
                        $('#non_sawah').hide();
                        $('#pengairan').closest('.list-group-item')
                            .show();
                    } else {
                        $('#lahan_sawah').hide();
                        $('#non_sawah').show();
                        $('#pengairan').closest('.list-group-item')
                            .hide();
                    }
                    showSummary();
                });
            });

            function showSummary() {
                const inputDataSawah = {
                    'jenis_lahan': $('input[name="jenis_lahan"]:checked').val(),
                    'desa': $('#desa option:selected').text(),
                    'jenis_padi': $('#jenis_padi option:selected').text(),
                    'jenis_bantuan': $('select[name="jenis_bantuan"] option:selected').text(),
                    'tanaman_akhir_bulan_lalu': $('input[name="tanaman_akhir_bulan_lalu"]').val(),
                    'panen': $('input[name="panen"]').val(),
                    'tanam': $('input[name="tanam"]').val(),
                    'rusak': $('input[name="rusak"]').val(),
                    'tanam_akhir_bulan_laporan': $('input[name="tanam_akhir_bulan_laporan"]').val(),
                };

                let inputDataPengairan = {}; // Inisialisasi objek inputDataPengairan

                if ($('input[name="jenis_lahan"]:checked').val() === 'lahan_sawah') {
                    inputDataPengairan = {
                        'jenis_padi_pengairan': $('#pengairan option:selected').text(),
                        'tanaman_akhir_bulan_lalu_pengairan': $('input[name="tanaman_akhir_bulan_lalu_pengairan"]').val(),
                        'panen_pengairan': $('input[name="panen_pengairan"]').val(),
                        'tanam_pengairan': $('input[name="tanam_pengairan"]').val(),
                        'rusak_pengairan': $('input[name="rusak_pengairan"]').val(),
                        'tanam_akhir_bulan_laporan_pengairan': $('input[name="tanam_akhir_bulan_laporan_pengairan"]').val(),
                    };
                }

                let summaryHTML = '<table class="table">';
                summaryHTML += '<tr><th colspan="5">Laporan Lahan Sawah</th></tr>';
                summaryHTML +=
                    '<tr><td>Jenis Lahan</td><td>Desa</td><td>Jenis Padi</td><td>Jenis Bantuan</td><td>Tanaman Akhir Bulan Lalu</td><td>Panen</td><td>Tanam</td><td>Puso/Rusak</td><td>Tanaman Akhir Bulan Laporan</td></tr>';
                summaryHTML += '<tr>';
                summaryHTML += '<td>' + inputDataSawah.jenis_lahan + '</td>';
                summaryHTML += '<td>' + inputDataSawah.desa + '</td>';
                summaryHTML += '<td>' + inputDataSawah.jenis_padi + '</td>';
                summaryHTML += '<td>' + inputDataSawah.jenis_bantuan + '</td>';
                summaryHTML += '<td>' + inputDataSawah.tanaman_akhir_bulan_lalu + '</td>';
                summaryHTML += '<td>' + inputDataSawah.panen + '</td>';
                summaryHTML += '<td>' + inputDataSawah.tanam + '</td>';
                summaryHTML += '<td>' + inputDataSawah.rusak + '</td>';
                summaryHTML += '<td>' + inputDataSawah.tanam_akhir_bulan_laporan + '</td>';
                summaryHTML += '</tr>';
                summaryHTML += '</table>';

                // Menambahkan tabel laporan lahan sawah ke dalam summary
                $('#summary').html(summaryHTML);

                // Mengondisikan pembuatan ringkasan laporan pengairan berdasarkan jenis lahan yang dipilih
                if ($('input[name="jenis_lahan"]:checked').val() === 'lahan_sawah') {
                    summaryHTML = '<table class="table">';
                    summaryHTML += '<tr><th colspan="5">Laporan Pengairan</th></tr>';
                    summaryHTML +=
                        '<tr><td>Jenis Padi</td><td>Tanaman Akhir Bulan Lalu</td><td>Panen</td><td>Tanam</td><td>Puso/Rusak</td><td>Tanaman Akhir Bulan Laporan</td></tr>';
                    summaryHTML += '<tr>';
                    summaryHTML += '<td>' + inputDataPengairan.jenis_padi_pengairan + '</td>';
                    summaryHTML += '<td>' + inputDataPengairan.tanaman_akhir_bulan_lalu_pengairan + '</td>';
                    summaryHTML += '<td>' + inputDataPengairan.panen_pengairan + '</td>';
                    summaryHTML += '<td>' + inputDataPengairan.tanam_pengairan + '</td>';
                    summaryHTML += '<td>' + inputDataPengairan.rusak_pengairan + '</td>';
                    summaryHTML += '<td>' + inputDataPengairan.tanam_akhir_bulan_laporan_pengairan + '</td>';
                    summaryHTML += '</tr>';
                    summaryHTML += '</table>';

                    $('#summary').append(summaryHTML);
                }
            }
        </script>

    @endsection
