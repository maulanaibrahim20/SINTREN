<div class="modal fade" id="modalCenter" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="{{ url('/operator/master/data_pasar') }}" enctype="multipart/form-data" method="post">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">Tambah Data Pasar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="col mb-3">
                        <label for="nameBasic" class="form-label">Nama</label>
                        <input type="text" name="name" class="form-control"
                            placeholder="Masukkan Nama Pasar" />
                    </div>
                    {{-- <div class="col mb-3">
                        <label for="nameBasic" class="form-label">Description</label>
                        <input type="text" name="description" class="form-control"
                            placeholder="Masukkan Nama Deskripsi" />
                    </div> --}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
