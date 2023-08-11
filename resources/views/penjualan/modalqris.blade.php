<!-- Modal QRIS Success -->
<div class="modal" id="qrisModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pembayaran QRIS </h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <i class="fa fa-spinner fa-3x text-blue" aria-hidden="true"></i>
                </div>
                <br>
                <p class="text-center mt-3">Apakah Pasien sudah melakukan pembayaran? </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="PembayaranQR()">Ya</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Kembali</button>
            </div>
        </div>
    </div>
</div>

<script>
    function PembayaranQR() {
        // Gantilah dengan URL atau route yang sesuai untuk halaman pembayaran terakhir
        window.location.href = "{{ route('penjualan.qr') }}";
    }
</script>