@extends('index')
@section('title', 'Tambah Laporan Padi | Penyuluh')
@section('content')
    <div class="main-container container-fluid">
        <div class="page-header d-sm-flex d-block">
            <ol class="breadcrumb mb-sm-0 mb-3">
                <!-- breadcrumb -->
                <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Laporan Padi</li>
                <!-- End breadcrumb -->
            </ol>
        </div>
    </div>
    <div class="row row-cards">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0 card-title">Tambah Data Laporan Padi</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
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
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="select2Basic" class="form-label">Pilih Jenis Lahan</label>
                                <select id="jenis_lahan" class="form-control form-select select2"
                                    aria-label="Default select example" name="jenis_lahan">
                                    <option value="">-- Pilih --</option>
                                    <option value="lahan_sawah">Lahan Sawah</option>
                                    <option value="non_sawah">Bukan Sawah/Non Sawah</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="select2Basic" class="form-label">Irigasi Tersier</label>
                                    <select id="irigasi_tersier" class="form-control form-select select2"
                                        aria-label="Default select example" name="irigasi_tersier">
                                        <option value="">-- Pilih --</option>
                                        <option value="ada">Ada</option>
                                        <option value="tidak">Tidak</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6" id="jenis_irigasi_tersier" style="display: none;">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="select2Basic" class="form-label">Tanam</label>
                                        <input type="text" id="tanam_tersier" class="form-control" name="tanam_tersier">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="select2Basic" class="form-label">Panen</label>
                                        <input type="text" id="panen_tersier" class="form-control" name="panen_tersier">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="expanel expanel-default">
                                <div class="expanel-heading">
                                    <div class="d-flex">
                                        <h3 class="expanel-title mt-3">Data Detail Pertanian</h3>
                                        <div class="ms-auto">
                                            <button type="button" class="btn ripple btn-success mr-3"
                                                id="tambahDataLaporanBtn">Tambah Data
                                                Padi</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="expanel-body">
                                    <div class="table-responsive">
                                        <table id="table-padi"
                                            class="table border text-nowrap text-md-nowrap table-bordered mg-b-0">
                                            <thead>
                                                <tr>
                                                    <th style="width: 10%;">No</th>
                                                    <th style="width: 20%;">Jenis Padi</th>
                                                    <th style="width: 20%;">Jenis Bantuan</th>
                                                    <th style="width: 20%;">Tanam Akhir Bulan Lalu</th>
                                                    <th style="width: 20%;">Panen</th>
                                                    <th style="width: 20%;">Tanam</th>
                                                    <th style="width: 20%;">Puso/Rusak</th>
                                                    <th style="width: 20%;">Tanam Akhir Bulan Laporan</th>
                                                    <th style="width: 10%;">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="expanel expanel-default">
                                <div class="expanel-heading">
                                    <div class="d-flex">
                                        <h3 class="expanel-title mt-3">Data Detail Pengairan</h3>
                                        <div class="ms-auto">
                                            <button type="button" class="btn ripple btn-secondary mr-3"
                                                id="tambahDataLaporanBtnPengairan">Tambah Data
                                                Pengairan</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="expanel-body">
                                    <div class="table-responsive">
                                        <table id="table-pengairan"
                                            class="table border text-nowrap text-md-nowrap table-bordered mg-b-0">
                                            <thead>
                                                <tr>
                                                    <th style="width: 10%;">No</th>
                                                    <th style="width: 20%;">Jenis Pengairan</th>
                                                    <th style="width: 20%;">Tanaman Akhir bulan lalu</th>
                                                    <th style="width: 20%;">Panen</th>
                                                    <th style="width: 20%;">Tanam</th>
                                                    <th style="width: 20%;">Puso/Rusak</th>
                                                    <th style="width: 20%;">Tanaman Akhir Bulan Laporan</th>
                                                    <th style="width: 10%;">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="col-md-12 mt-3">
                        <form action="{{ url('/penyuluh/create/laporan_padi') }}" method="POST" id="form-data">
                            @csrf
                            <input type="hidden" name="data_padi" id="data_padi" value="">
                            {{-- <input type="hidden" name="data_pengairan" id="data_pengairan" value=""> --}}
                            <button type="button" class="btn ripple btn-success mr-3" id="kirim">Kirim</button>
                            <button type="button" class="btn ripple btn-primary mr-3" id="draf">Draf</button>
                            <button type="button" class="btn ripple btn-warning mr-3" id="cancel">Cancel</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    @include('penyuluh.pages.laporan_padi.modal_tambah_padi')
    @include('penyuluh.pages.laporan_padi.modal_tambah_pengairan')

    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            var jenisIrigasiTersier = $('#jenis_irigasi_tersier');
            jenisIrigasiTersier.closest('.row').hide();
            $('#tambahDataLaporanBtnPengairan').hide();

            $('#tambahDataLaporanBtnPengairan').click(function() {
                $('#largeModalPengairan').modal('show');
            });
            $('#tambahDataLaporanBtn').click(function() {
                var desa = $('#desa').val();
                var jenisLahan = $('#jenis_lahan').val();
                var irigasi_tersier = $('#irigasi_tersier').val();
                var irigasi_tersier_input_tanam = $('#tanam_tersier').val();
                var irigasi_tersier_input_panen = $('#panen_tersier').val();

                var errorMsg = '';

                if (!desa) {
                    errorMsg += 'Silakan pilih Desa. ';
                }
                if (!jenisLahan) {
                    errorMsg += 'Silakan pilih Jenis Lahan. ';
                }
                if (jenisLahan == 'lahan_sawah' && !irigasi_tersier) {
                    errorMsg += 'Silakan pilih status Irigasi Tersier. ';
                }
                if (irigasi_tersier === "ada") {
                    if (!irigasi_tersier_input_tanam || !irigasi_tersier_input_panen) {
                        errorMsg += 'Silakan isi data Irigasi Tersier. ';
                    }
                }
                if (errorMsg !== '') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Peringatan',
                        text: errorMsg,
                    });
                } else {
                    $('#largeModal').modal('show');
                }
            });
            $('#jenis_lahan').change(function() {
                var selectedJenisLahan = $(this).val();
                var jenisPengairan = $('#jenis_pengairan');
                var jenisBantuan = $('#jenis_bantuan');
                var formGroupBantuan = jenisBantuan.closest('.form-group');
                var jenisIrigasiTersier = $('#jenis_irigasi_tersier');
                var btnLaporanPengairan = $('#tambahDataLaporanBtnPengairan');

                if (selectedJenisLahan === 'non_sawah') {
                    jenisIrigasiTersier.closest('.row').hide();
                    $('#tambahDataLaporanBtnPengairan').hide();
                } else {
                    jenisIrigasiTersier.closest('.row').show();
                    btnLaporanPengairan.show();
                }
            });

            $("#irigasi_tersier").change(function() {
                if ($(this).val() == "ada") {
                    $("#jenis_irigasi_tersier").show();
                    $("#selectedIrigasiTersierTanamText").closest('p').show();
                    $("#selectedIrigasiTersierPanenText").closest('p').show();
                    $("#selectedIrigasiTersierTanamPerairanText").closest('p').show();
                    $("#selectedIrigasiTersierPanenPerairanText").closest('p').show();
                } else {
                    //jenis padi
                    $("#jenis_irigasi_tersier").hide();
                    $("#selectedIrigasiTersierTanamText").closest('p').hide();
                    $("#selectedIrigasiTersierPanenText").closest('p').hide();
                    //jenis perairan
                    $("#selectedIrigasiTersierTanamPerairanText").closest('p').hide();
                    $("#selectedIrigasiTersierPanenPerairanText").closest('p').hide();
                }
            });

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

            $("#puso_rusak_pengairan").change(function() {
                puso_pengairan = parseInt($(this).val());
                hitungTanamanAkhirBulanIniPengairan();
            });

            function hitungTanamanAkhirBulanIniPengairan() {
                tanamanAkhirBulanIniPengairan = tanamanAkhirBulanLaluPengairan - panen_pengairan + tanam_pengairan -
                    puso_pengairan;
                $("#tanaman_akhir_bulan_laporan_pengairan").val(tanamanAkhirBulanIniPengairan);
                console.log("tanaman akhir bulan ini: " + tanamanAkhirBulanIniPengairan);
            }

            $('#largeModal').on('show.bs.modal', function(event) {
                var selectedDesa = $('#desa option:selected').val();
                var selectedDesaText = $('#desa option:selected').text();
                var selectedJenisLahan = $('#jenis_lahan option:selected').val();
                var selectedJenisLahanText = $('#jenis_lahan option:selected').text();
                var inputTanamTersier = $('#tanam_tersier').val();
                var inputTanamTersierText = $('#tanam_tersier').val();
                var inputPanenTersier = $('#panen_tersier').val();
                var inputPanenTersierText = $('#panen_tersier').val();

                console.log("Desa yang dipilih: ", selectedDesa);
                console.log("Jenis Lahan yang dipilih: ", selectedJenisLahan);
                console.log("Data Tanam Tersier: ", inputTanamTersier);
                console.log("Data Panen Tersier: ", inputPanenTersier);

                $('#selectedDesa').val(selectedDesa);
                $('#selectedJenisLahan').val(selectedJenisLahan);
                $('#selectedIrigasiTersierTanam').val(
                    inputTanamTersier);
                $('#selectedIrigasiTersierPanen').val(
                    inputPanenTersier);
                $('#selectedDesaText').text(selectedDesaText);
                $('#selectedDesaValue').text(selectedDesa);
                $('#selectedJenisLahanValue').text(selectedJenisLahan);
                $('#selectedJenisLahanText').text(selectedJenisLahanText);
                $('#selectedIrigasiTersierTanamText').text(inputTanamTersierText);
                $('#selectedIrigasiTersierPanenText').text(inputPanenTersierText);
            });

            $('#largeModalPengairan').on('show.bs.modal', function(event) {
                var selectedDesa = $('#desa option:selected').val();
                var selectedDesaText = $('#desa option:selected').text();
                var selectedJenisLahan = $('#jenis_lahan option:selected').val();
                var selectedJenisLahanText = $('#jenis_lahan option:selected').text();
                var inputTanamTersier = $('#tanam_tersier').val();
                var inputTanamTersierText = $('#tanam_tersier').text();
                var inputPanenTersier = $('#panen_tersier').val();
                var inputPanenTersierText = $('#panen_tersier').text();

                console.log("Desa yang dipilih: ", selectedDesa);
                console.log("Jenis Lahan yang dipilih: ", selectedJenisLahan);
                console.log("Data Tanam Tersier: ", inputTanamTersier);
                console.log("Data Panen Tersier: ", inputPanenTersier);

                $('#selectedDesaPerairan').val(selectedDesa);
                $('#selectedJenisLahanPerairan').val(selectedJenisLahan);

                $('#selectedIrigasiTersierTanamPeraian').val(
                    inputTanamTersier);
                $('#selectedIrigasiTersierPanenPerairan').val(
                    inputPanenTersier);

                $('#selectedDesaPerairanText').text(selectedDesaText);
                $('#selectedDesaValue').text(selectedDesa);

                $('#selectedJenisLahanValue').text(selectedJenisLahan);
                $('#selectedJenisLahanPerairanText').text(selectedJenisLahanText);

                $('#selectedIrigasiTersierTanamPerairanText').text(inputTanamTersierText);
                $('#selectedIrigasiTersierPanenPerairanText').text(inputPanenTersierText);
            });

            $("#addButton").on("click", function() {
                var jenisPadi = $("#jenis_padi").val();
                var jenisBantuan = $("#jenis_bantuan").val();
                var tanamAkhirBulanLalu = $("#tanaman_akhir_bulan_lalu").val() || 0;
                var panen = $("#panen").val() || 0;
                var tanam = $("#tanam").val() || 0;
                var puso_rusak = $("#puso_rusak").val() || 0;
                var tanamAkhirBulanLaporan = $("#tanaman_akhir_bulan_laporan").val() || 0;

                addRow(jenisPadi, jenisBantuan, tanamAkhirBulanLalu, panen, tanam, puso_rusak,
                    tanamAkhirBulanLaporan);

                $('select[name="jenis_padi"]').val('').trigger('change.select2');
                $('select[name="jenis_bantuan"]').val('').trigger('change.select2');
                $("#tanaman_akhir_bulan_lalu").val("");
                $("#panen").val("");
                $("#tanam").val("");
                $("#puso_rusak").val("");
                $("#tanaman_akhir_bulan_laporan").val("");
            });
            $("#addButtonPengairan").on("click", function() {
                var jenisPengairan = $("#jenis_pengairan").val();
                var tanamAkhirBulanLaluPengairan = $("#tanaman_akhir_bulan_lalu_pengairan").val() || 0;
                var panenPengairan = $("#panen_pengairan").val() || 0;
                var tanamPengairan = $("#tanam_pengairan").val() || 0;
                var puso_rusakPengairan = $("#puso_rusak_pengairan").val() || 0;
                var tanamAkhirBulanLaporanPengairan = $("#tanaman_akhir_bulan_laporan_pengairan").val() ||
                    0;

                addRowPengairan(jenisPengairan, tanamAkhirBulanLaluPengairan, panenPengairan,
                    tanamPengairan,
                    puso_rusakPengairan,
                    tanamAkhirBulanLaporanPengairan);

                $('select[name="jenis_pengairan"]').val('').trigger('change.select2');
                $("#tanaman_akhir_bulan_lalu_pengairan").val("");
                $("#panen_pengairan").val("");
                $("#tanam_pengairan").val("");
                $("#puso_rusak_pengairan").val("");
                $("#tanaman_akhir_bulan_laporan_pengairan").val("");
            });

            var detailPadi = [];
            var detailPengairan = [];
            var dataPadi = [];
            var rowNumber = 1;
            // membuat tabel untuk menampung data
            function addRowPengairan(jenisPengairan, tanamAkhirBulanLaluPengairan, panenPengairan, tanamPengairan,
                puso_rusakPengairan,
                tanamAkhirBulanLaporanPengairan) {
                var jenisPengairan = $("#jenis_pengairan option:selected").text();
                var desa = $("#desa").val();
                var jenis_lahan = $("#jenis_lahan").val();


                var newRow = $("<tr>");
                newRow.append("<td>" + rowNumber + "</td>");
                newRow.append("<td>" + jenisPengairan + "</td>");
                newRow.append("<td>" + tanamAkhirBulanLaluPengairan + "</td>");
                newRow.append("<td>" + panenPengairan + "</td>");
                newRow.append("<td>" + tanamPengairan + "</td>");
                newRow.append("<td>" + puso_rusakPengairan + "</td>");
                newRow.append("<td>" + tanamAkhirBulanLaporanPengairan + "</td>");
                newRow.append(
                    "<td><button class='btn btn-danger delete-row'><i class='fe fe-trash'></i></button></td>");

                $("#table-pengairan tbody").append(newRow);

                rowNumber++;

                detailPengairan.push({
                    id: null,
                    jenis_pengairan: jenisPengairan,
                    tanaman_akhir_bulan_lalu: tanamAkhirBulanLaluPengairan,
                    panen: panenPengairan,
                    tanam: tanamPengairan,
                    puso_rusak: puso_rusakPengairan,
                    tanaman_akhir_bulan_laporan: tanamAkhirBulanLaporanPengairan,
                });
                dataPadi.push({
                    desa_id: desa,
                    jenis_lahan: jenis_lahan,
                    detailPadi: detailPadi,
                    detailPengairan: detailPengairan,
                });
            }

            function addRow(jenisPadi, jenisBantuan, tanamAkhirBulanLalu, panen, tanam, puso_rusak,
                tanamAkhirBulanLaporan) {
                var jenisPadi = $("#jenis_padi option:selected").text();
                var jenisBantuan = $("#jenis_bantuan option:selected").text();
                var desa = $("#desa").val();
                var jenis_lahan = $("#jenis_lahan").val();


                var newRow = $("<tr>");
                newRow.append("<td>" + rowNumber + "</td>");
                newRow.append("<td>" + jenisPadi + "</td>");
                newRow.append("<td>" + jenisBantuan + "</td>");
                newRow.append("<td>" + tanamAkhirBulanLalu + "</td>");
                newRow.append("<td>" + panen + "</td>");
                newRow.append("<td>" + tanam + "</td>");
                newRow.append("<td>" + puso_rusak + "</td>");
                newRow.append("<td>" + tanamAkhirBulanLaporan + "</td>");
                newRow.append(
                    "<td><button class='btn btn-danger delete-row'><i class='fe fe-trash'></i></button></td>");

                $("#table-padi tbody").append(newRow);

                rowNumber++;

                detailPadi.push({
                    id: null,
                    jenis_padi: jenisPadi,
                    jenis_bantuan: jenisBantuan,
                    tanaman_akhir_bulan_lalu: tanamAkhirBulanLalu,
                    panen: panen,
                    tanam: tanam,
                    puso_rusak: puso_rusak,
                    tanaman_akhir_bulan_laporan: tanamAkhirBulanLaporan,
                });
                dataPadi.push({
                    desa_id: desa,
                    jenis_lahan: jenis_lahan,
                    detailPadi: detailPadi,
                    detailPengairan: detailPengairan,
                });
            }

            $("#table-padi").on("click", ".delete-row", function() {
                var rowIndex = $(this).closest("tr").index();
                detailPadi.splice(rowIndex, 1);
                $(this).closest("tr").remove();
                rowNumber--;
                console.log(detailPadi);
            });

            // $('#kirim').click(function() {

            //     // var id = $('#id').val();
            //     var desa = $("#desa").val();
            //     var jenis_lahan = $("#jenis_lahan").val();

            //     var dataPadi = {
            //         // _token: "{{ csrf_token() }}",
            //         desa_id: desa,
            //         jenis_lahan: jenis_lahan,
            //         detailPadi: detailPadi,
            //         detailPengairan: detailPengairan,
            //     };

            //     $.ajax({
            //         type: 'POST',
            //         url: '/api/storePadi',
            //         headers: {
            //             'X-CSRF-TOKEN': "{{ csrf_token() }}"
            //         },
            //         data: JSON.stringify(dataPadi),
            //         // processData: false, // Tetapkan false agar jQuery tidak memproses data FormData
            //         // contentType: false, // Mengatur tipe media ke JSON
            //         success: function(response) {
            //             // console.log(response);
            //             // $("#extraLargeModal").modal("hide");
            //             // $('#responsive-datatable').DataTable().ajax.reload();
            //         },
            //         error: function(error) {
            //             console.error(error);
            //         }
            //     });

            // });
        });
        document.getElementById("kirim").addEventListener("click", function() {
            let dataPadi = [];
            let laporanPadi = [];
            let laporanPengairan = [];
            let dataPengairan = [];

            // Mengambil data dari tabel pertanian
            let desa = document.getElementById("desa").value;
            let jenis_lahan = document.getElementById("jenis_lahan").value;

            let tablePadi = document.getElementById("table-padi");
            for (let i = 1; i < tablePadi.rows.length; i++) {
                let row = tablePadi.rows[i];
                let rowData = {
                    jenis_padi: row.cells[1].innerHTML.trim(),
                    jenis_bantuan: row.cells[2].innerHTML.trim(),
                    tanam_akhir_bulan_lalu: row.cells[3].innerHTML.trim(),
                    panen: row.cells[4].innerHTML.trim(),
                    tanam: row.cells[5].innerHTML.trim(),
                    puso_rusak: row.cells[6].innerHTML.trim(),
                    tanam_akhir_bulan_laporan: row.cells[7].innerHTML.trim()
                };
                dataPadi.push(rowData);
            }

            // Mengambil data dari tabel pengairan
            let tablePengairan = document.getElementById("table-pengairan");
            for (let i = 1; i < tablePengairan.rows.length; i++) {
                let row = tablePengairan.rows[i];
                let rowDataPengairan = {
                    jenis_pengairan: row.cells[1].innerText.trim(),
                    tanaman_akhir_bulan_lalu: row.cells[2].innerText.trim(),
                    panen: row.cells[3].innerText.trim(),
                    tanam: row.cells[4].innerText.trim(),
                    puso_rusak: row.cells[5].innerText.trim(),
                    tanaman_akhir_bulan_laporan: row.cells[6].innerText.trim()
                };
                dataPengairan.push(rowDataPengairan);
            }

            laporanPadi = ({
                desa_id: desa,
                jenis_lahan: jenis_lahan,
                detailPadi: dataPadi,
                detailPengairan: dataPengairan
            });
            console.log(laporanPadi);


            document.getElementById("data_padi").value = JSON.stringify(laporanPadi);
            // document.getElementById("data_pengairan").value = JSON.stringify(laporanPengairan);

            document.getElementById("form-data").submit();
        });
    </script>

@endsection
