@extends('index')
@section('title', 'Create Laporan Palawija')
@section('content')
    <div class="page-header d-sm-flex d-block">
        <ol class="breadcrumb mb-sm-0 mb-3">
            <!-- breadcrumb -->
            <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Laporan Palawija</li>
        </ol><!-- End breadcrumb -->
    </div>
    <div class="row row-cards">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0 card-title">Tambah Data Laporan Palawija</h3>
                </div>
                <div class="card-body">
                    <form action="{{ url('/penyuluh/create/laporan_palawija') }}" method="POST" id="myForm">
                        @csrf
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
                        </div>
                    </form>
                </div>
                <div class="card-footer">
                    <div class="col-md-12 mt-3">
                        <button type="button" class="btn ripple btn-primary" data-bs-target="#exampleModalToggle"
                            data-bs-toggle="modal">Tambah Data Laporan</button>
                    </div>
                </div>
            </div>
        </div>


        <!-- Modal -->
        <div class="modal fade" id="exampleModalToggle" aria-hidden="true" aria-labelledby="exampleModalToggleLabel"
            tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form action="{{ url('/penyuluh/create/laporan_palawija') }}" method="POST" id="modalForm">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalToggleLabel">Tambah Data Laporan Palawija</h5>
                            <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"></button>
                        </div>
                        <div class="modal-body">

                            <input type="hidden" id="selectedDesa" name="desa">
                            <input type="hidden" id="selectedJenisLahan" name="jenis_lahan">

                            <p>Desa yang dipilih: <span id="selectedDesaText"></span></p>
                            <p>Jenis Lahan yang dipilih: <span id="selectedJenisLahanText"></span></p>

                            <div class="form-group">
                                <label for="select2Basic" class="form-label">Pilih Jenis Palawija</label>
                                <select id="jenis_palawija" class="form-control form-select select2"
                                    aria-label="Default select example" name="jenis_palawija">
                                    <option value="">-- Pilih --</option>
                                    @foreach ($jenisPalawija as $data)
                                        <option value="{{ $data->id }}"
                                            data-bantuan="{{ $data->bantuan ? 'true' : 'false' }}"
                                            data-panen_muda="{{ $data->panen_muda ? 'true' : 'false' }}"
                                            data-panen_pakan_ternak="{{ $data->panen_pakan_ternak ? 'true' : 'false' }}"
                                            data-total_produksi="{{ $data->total_produksi ? 'true' : 'false' }}">
                                            {{ $data->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group" id="bantuan_input" style="display: none;">
                                <label for="select2Basic" class="form-label">Pilih Bantuan</label>
                                <select id="jenis_bantuan" class="form-control form-select select2"
                                    aria-label="Default select example" name="jenis_bantuan">
                                    <option value="">-- Pilih --</option>
                                    <option value="bantuan_pemerintah">Bantuan Pemerintah</option>
                                    <option value="non_bantuan_pemerintah">Non Bantuan Pemerintah</option>
                                </select>
                            </div>
                            <div class="form-group" id="panen_muda_input" style="display: none;">
                                <label for="exampleFormControlInput2">Panen Muda</label>
                                <input type="text" class="form-control" id="panen_muda" name="panen_muda">
                            </div>
                            <div class="form-group" id="panen_pakan_ternak_input" style="display: none;">
                                <label for="exampleFormControlInput2">Panen Ternak</label>
                                <input type="text" class="form-control" id="panen_pakan_ternak"
                                    name="panen_pakan_ternak">
                            </div>
                            <div class="form-group" id="tanaman_akhir_bulan_lalu_input">
                                <label for="exampleFormControlInput2">Tanaman Akhir Bulan Lalu</label>
                                <input type="text" class="form-control" id="tanaman_akhir_bulan_lalu"
                                    name="tanaman_akhir_bulan_lalu">
                            </div>
                            <div class="form-group" id="panen_input">
                                <label for="exampleFormControlInput2">Panen</label>
                                <input type="text" class="form-control" id="panen" name="panen">
                            </div>
                            <div class="form-group" id="tanam_input">
                                <label for="exampleFormControlInput2">Tanam</label>
                                <input type="text" class="form-control" id="tanam" name="tanam">
                            </div>
                            <div class="form-group" id="puso_rusak_input">
                                <label for="exampleFormControlInput2">Puso/Rusak</label>
                                <input type="text" class="form-control" id="puso_rusak" name="puso_rusak">
                            </div>
                            <div class="form-group" id="tanaman_akhir_bulan_laporan_input">
                                <label for="exampleFormControlInput2">Tanaman akhir bulan laporan</label>
                                <input type="text" class="form-control" id="tanaman_akhir_bulan_laporan"
                                    name="tanaman_akhir_bulan_laporan">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- summary --}}
        {{-- <div class="col-lg-12">
            <div class="card ">
                <div class="card-header ">
                    <h3 class="card-title">Order Summery</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered align-items-center">
                            <tbody class="text-dark">
                                <tr>
                                    <td>Cart Subtotal</td>
                                    <td class="text-end">$485.00</td>
                                </tr>
                                <tr>
                                    <td><span>Discount</span></td>
                                    <td class="text-end text-success"><span>0.5%</span></td>
                                </tr>
                                <tr>
                                    <td><span>Totals</span></td>
                                    <td class="text-end text-muted"><span>$456.00</span></td>
                                </tr>
                                <tr>
                                    <td><span>Order Total</span></td>
                                    <td>
                                        <h2 class="price text-end text-primary mb-0">$456.00</h2>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <form class="float-end mt-0">
                        <a href="shop.html" class="btn btn-primary mt-2"><i class="fa fa-arrow-circle-left"></i>
                            Continue
                            Shopping</a>
                        <a href="checkout.html" class="btn btn-success mt-2">Checkout <i
                                class="fa fa-arrow-circle-right"></i></a>
                    </form>
                </div>
            </div>
        </div> --}}
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {

            $('#exampleModalToggle').on('show.bs.modal', function(event) {
                var selectedDesa = $('#desa option:selected').val();
                var selectedDesaText = $('#desa option:selected').text();
                var selectedJenisLahan = $('#jenis_lahan option:selected').val();
                var selectedJenisLahanText = $('#jenis_lahan option:selected').text();

                console.log("Desa yang dipilih: ", selectedDesa);
                console.log("Jenis Lahan yang dipilih: ", selectedJenisLahan);

                $('#exampleModalToggle').find('#selectedDesa').val(selectedDesa);
                $('#exampleModalToggle').find('#selectedJenisLahan').val(selectedJenisLahan);

                $('#selectedDesa').val(selectedDesa);
                $('#selectedJenisLahan').val(selectedJenisLahan);

                $('#selectedDesaText').text(selectedDesaText);
                $('#selectedDesaValue').text(selectedDesa);
                $('#selectedJenisLahanValue').text(selectedJenisLahan);
                $('#selectedJenisLahanText').text(selectedJenisLahanText);
            });

            $('#jenis_palawija').change(function() {
                var selectedOption = $(this).find(':selected');
                var bantuan = selectedOption.data('bantuan');
                var panenMuda = selectedOption.data('panen_muda');
                var panenPakanTernak = selectedOption.data('panen_pakan_ternak');
                var totalProduksi = selectedOption.data('total_produksi');

                var bantuanInput = $('#bantuan_input');
                var panenMudaInput = $('#panen_muda_input');
                var panenPakanTernakInput = $('#panen_pakan_ternak_input');
                var totalProduksiInput = $('#total_produksi_input');

                if (bantuan === true) {
                    bantuanInput.show();
                } else {
                    bantuanInput.hide();
                }

                if (panenMuda === true) {
                    panenMudaInput.show();
                } else {
                    panenMudaInput.hide();
                }

                if (panenPakanTernak === true) {
                    panenPakanTernakInput.show();
                } else {
                    panenPakanTernakInput.hide();
                }

                if (totalProduksi === true) {
                    totalProduksiInput.show();
                } else {
                    totalProduksiInput.hide();
                }

                console.log("Nilai Bantuan: " + bantuan);
                console.log("Nilai Panen Muda: " + panenMuda);
                console.log("Nilai Panen Pakan Ternak: " + panenPakanTernak);
                console.log("Nilai Total Produksi: " + totalProduksi);

            });
        });
    </script>

@endsection
