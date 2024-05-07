<div class="modal fade" id="modalCenter" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="{{ url('/operator/master/pengairan') }}" enctype="multipart/form-data" method="post">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">Tambah Pengairan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="col mb-3">
                        <label for="nameBasic" class="form-label">Nama</label>
                        <input type="text" name="name" class="form-control"
                            placeholder="Masukkan Nama Pengairan" />
                    </div>
                </div>
                <div class="modal-footer">
                    @include('template.component.button_modal')
                </div>
            </form>
        </div>
    </div>
</div>
