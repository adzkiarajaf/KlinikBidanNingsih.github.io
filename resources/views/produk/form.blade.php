<style>
    .star-red {
        color: red;
    }
</style>

<div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form">
    <div class="modal-dialog modal-lg" role="document">
        <form action="{{ route('produk.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('post')
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="nama_produk" class="col-lg-3 control-label">Nama Produk<span class="star-red">*</span></label>
                        <div class="col-lg-9">
                            <input type="text" name="nama_produk" id="nama_produk" class="form-control" required autofocus oninvalid="this.setCustomValidity('Nama Produk harus diisi.')" oninput="setCustomValidity('')">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="id_kategori" class="col-lg-3 control-label">Kategori<span class="star-red"> *</span></label>
                        <div class="col-lg-9">
                            <select name="id_kategori" id="id_kategori" class="form-control" required autofocus oninvalid="this.setCustomValidity('Kategori harus diisi.')" oninput="setCustomValidity('')">
                                <option value="">Pilih Kategori</option>
                                @foreach ($kategori as $key => $item)
                                    <option value="{{ $key }}">{{ $item }}</option>
                                @endforeach
                            </select>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="harga_jual" class="col-lg-3 control-label">Harga Jual<span class="star-red"> *</span></label>
                        <div class="col-lg-9">
                            <input type="number" name="harga_jual" id="harga_jual" class="form-control" required autofocus oninvalid="this.setCustomValidity('Harga Jual harus diisi.')" oninput="setCustomValidity('')">
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="harga_beli" class="col-lg-3 control-label">Harga Beli<span class="star-red"> *</span></label>
                        <div class="col-lg-9">
                            <input type="number" name="harga_beli" id="harga_beli" class="form-control" required autofocus oninvalid="this.setCustomValidity('Harga Beli harus diisi.')" oninput="setCustomValidity('')">
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="stok" class="col-lg-3 control-label">Stok</label>
                        <div class="col-lg-9">
                            <input type="number" name="stok" id="stok" class="form-control" required value="0">
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="path_foto" class="col-lg-3 control-label">Foto Produk<span class="star-red"> *</span></label>
                        <div class="col-lg-9">
                            <input type="file" name="path_foto" id="path_foto" class="form-control" required onchange="preview('.tampil-foto', this.files[0], 300)">
                            <br>
                            <div class="tampil-foto"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-primary" id="btnSimpan"><i class="fa fa-save"></i> Simpan</button>
                    <button type="button" class="btn btn-sm btn-warning" data-dismiss="modal"><i class="fa fa-arrow-circle-left"></i> Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function preview(selector, file, maxWidth) {
        if (file) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var img = $('<img>').attr('src', e.target.result);
                img.css('max-width', maxWidth + 'px');
                $(selector).html(img);
            };
            reader.readAsDataURL(file);
        } else {
            $(selector).empty();
        }
    }
</script>
@endpush