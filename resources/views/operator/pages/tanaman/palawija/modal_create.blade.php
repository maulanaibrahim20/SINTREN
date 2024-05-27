<div class="modal fade" id="modalCenter" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="{{ url('/operator/tanaman/palawija') }}" enctype="multipart/form-data" method="post">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">Tambah Kategori Tanaman Palawija</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="col mb-3">
                        <label class="form-label">Nama Tanaman Palawija</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control"
                            placeholder="Masukkan Nama Kategori" />
                    </div>
                    <div class="col mb-3">
                        <label class="form-label">Nama Tanaman Palawija</label>
                        <select id="category" class="form-control select2 form-select" name="category"
                            data-placeholder="Pilih Kategori Palawija">
                            <option value="">-- Pilih --</option>
                            @foreach ($kategori as $item)
                                <option value="{{ $item->id }}" name="category">
                                    {{ $item->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    @include('template.component.button_modal')
                </div>
            </form>
        </div>
    </div>
</div>
