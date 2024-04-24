<div id="largeModal" class="modal fade">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content ">
            <form action="{{ url('/penyuluh/create/laporan_padi') }}" method="POST" id="modalForm">
                @csrf
                <div class="modal-header pd-x-20">
                    <h5 class="modal-title" id="exampleModalToggleLabel">Tambah Data Laporan Padi</h5>
                    <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"></button>
                </div>
                <div class="modal-body pd-20">
                    <input type="hidden" id="selectedDesa" name="desa">
                    <input type="hidden" id="selectedJenisLahan" name="jenis_lahan">
                    <input type="hidden" id="selectedIrigasiTersierTanam" name="tanam_irigasi_tersier">
                    <input type="hidden" id="selectedIrigasiTersierPanen" name="panen_irigasi_tersier">

                    <p>Desa yang dipilih: <span id="selectedDesaText"></span></p>
                    <p>Jenis Lahan yang dipilih: <span id="selectedJenisLahanText"></span></p>
                    <p>Irigasi Tersier Tanam: <span id="selectedIrigasiTersierTanamText"></span></p>
                    <p>Irigasi Tersier Panen: <span id="selectedIrigasiTersierPanenText"></span></p>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="select2Basic" class="form-label">Pilih Jenis Padi</label>
                                <select id="jenis_padi" class="form-control form-select select2"
                                    aria-label="Default select example" name="jenis_padi">
                                    <option value="">-- Pilih --</option>
                                    @foreach ($jenis_padi as $data)
                                        <option value="{{ $data->id }}">{{ $data->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="select2Basic" class="form-label">Pilih Bantuan</label>
                                <select id="jenis_bantuan" class="form-control form-select select2"
                                    aria-label="Default select example" name="jenis_bantuan">
                                    <option value="">-- Pilih --</option>
                                    <option value="bantuan_pemerintah">Bantuan Pemerintah</option>
                                    <option value="non_bantuan_pemerintah">Non Bantuan Pemerintah</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleFormControlInput2">Tanaman Akhir Bulan Lalu</label>
                                <input type="text" class="form-control" id="tanaman_akhir_bulan_lalu"
                                    name="tanaman_akhir_bulan_lalu">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleFormControlInput2">Panen</label>
                                <input type="text" class="form-control" id="panen" name="panen">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleFormControlInput2">Tanam</label>
                                <input type="text" class="form-control" id="tanam" name="tanam">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleFormControlInput2">Puso/Rusak</label>
                                <input type="text" class="form-control" id="puso_rusak" name="puso_rusak">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlInput2">Tanaman akhir bulan laporan</label>
                        <input type="text" class="form-control" id="tanaman_akhir_bulan_laporan"
                            name="tanaman_akhir_bulan_laporan" readonly>
                    </div>
                </div><!-- modal-body -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" id="addButton" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div><!-- modal-dialog -->
</div>
