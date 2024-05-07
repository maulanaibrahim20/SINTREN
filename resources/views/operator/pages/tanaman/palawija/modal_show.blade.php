@foreach ($palawija as $show)
    <div class="modal fade" id="modalCenterShow{{ $edit['id'] }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">Show Kategori Tanaman Palawija</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-striped" style="width: 100%">
                        <tr>
                            <td class="text-right">Nama</td>
                            <td>:</td>
                            <td>
                                {{ $show->name }}
                            </td>
                        </tr>
                        <tr>
                            <td class="text-right">Kategori</td>
                            <td>:</td>
                            <td>
                                {{ $show->kategori->name }}
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    @include('template.component.button_modal')
                </div>
            </div>
        </div>
    </div>
@endforeach
