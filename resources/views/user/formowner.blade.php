<style>
    .star-red {
        color: red;
    }
</style>


<div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form">
    <div class="modal-dialog modal-lg" role="document">
        <form action="" method="post" class="form-horizontal">
            @csrf
            @method('post')

            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="name" class="col-lg-3 col-lg-offset-1 control-label">Nama<span class="star-red"> *</span></label>
                        <div class="col-lg-6">
                            <input type="text" name="name" id="name" class="form-control" required autofocus
                            data-error="Nama  harus diisi">
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="email" class="col-lg-3 col-lg-offset-1 control-label">Email<span class="star-red"> *</span></label>
                        <div class="col-lg-6">
                            <input type="email" name="email" id="email" class="form-control" required autofocus
                            data-error="Email harus diisi">
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="level" class="col-lg-3 col-lg-offset-1 control-label">Role</label>
                        <div class="col-lg-6">
                            <input type="level" name="level" id="level" class="form-control" required disabled>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="password" class="col-lg-3 col-lg-offset-1 control-label">Password<span class="star-red"> *</span></label>
                        <div class="col-lg-6">
                            <input type="password" name="password" id="password" class="form-control" 
                            required minlength="6" autofocus
                            data-error="Password Harus di isi">
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="password_confirmation" class="col-lg-3 col-lg-offset-1 control-label">Konfirmasi Password<span class="star-red"> *</span></label>
                        <div class="col-lg-6">
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" 
                                required data-match="#password">
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-flat btn-primary" id="btnSimpan"><i class="fa fa-save"></i> Simpan</button>
                    <button type="button" class="btn btn-sm btn-flat btn-warning" data-dismiss="modal"><i class="fa fa-arrow-circle-left"></i> Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>