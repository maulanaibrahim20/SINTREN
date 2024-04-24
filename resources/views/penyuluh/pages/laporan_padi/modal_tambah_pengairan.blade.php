<div id="largeModalPengairan" class="modal fade">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content ">
            <form action="{{ url('/penyuluh/create/laporan_padi') }}" method="POST" id="modalForm">
                @csrf
                <div class="modal-header pd-x-20">
                    <h5 class="modal-title" id="exampleModalToggleLabel">Tambah Data Laporan Pengairan</h5>
                    <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"></button>
                </div>
                <div class="modal-body pd-20">
                    <input type="hidden" id="selectedDesaPerairan" name="desa">
                    <input type="hidden" id="selectedJenisLahanPerairan" name="jenis_lahan">
                    <input type="hidden" id="selectedIrigasiTersierTanamPeraian" name="tanam_irigasi_tersier">
                    <input type="hidden" id="selectedIrigasiTersierPanenPerairan" name="panen_irigasi_tersier">

                    <p>Desa yang dipilih: <span id="selectedDesaPerairanText"></span></p>
                    <p>Jenis Lahan yang dipilih: <span id="selectedJenisLahanPerairanText"></span></p>
                    <p>Irigasi Tersier Tanam: <span id="selectedIrigasiTersierTanamPerairanText"></span></p>
                    <p>Irigasi Tersier Panen: <span id="selectedIrigasiTersierPanenPerairanText"></span></p>

                    <div class="form-group">
                        <label for="select2Basic" class="form-label">Pilih Jenis Pengairan</label>
                        <select id="jenis_pengairan" class="form-control form-select select2"
                            aria-label="Default select example" name="jenis_pengairan">
                            <option value="">-- Pilih --</option>
                            @foreach ($pengairan as $data)
                                <option value="{{ $data->id }}">{{ $data->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleFormControlInput2">Tanaman Akhir Bulan Lalu Pengairan</label>
                                <input type="text" class="form-control" id="tanaman_akhir_bulan_lalu_pengairan"
                                    name="tanaman_akhir_bulan_lalu_pengairan">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleFormControlInput2">Panen Pengairan</label>
                                <input type="text" class="form-control" id="panen_pengairan" name="panen_pengairan">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleFormControlInput2">Tanam Pengairan</label>
                                <input type="text" class="form-control" id="tanam_pengairan" name="tanam_pengairan">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleFormControlInput2">Puso/Rusak Pengairan</label>
                                <input type="text" class="form-control" id="puso_rusak_pengairan"
                                    name="puso_rusak_pengairan">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlInput2">Tanaman akhir bulan laporan Pengairan</label>
                        <input type="text" class="form-control" id="tanaman_akhir_bulan_laporan_pengairan"
                            name="tanaman_akhir_bulan_laporan_pengairan" readonly>
                    </div>
                </div><!-- modal-body -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" id="addButtonPengairan" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div><!-- modal-dialog -->
</div>
