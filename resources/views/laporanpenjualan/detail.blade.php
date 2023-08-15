<div class="modal fade" id="modal-detail" tabindex="-1" role="dialog" aria-labelledby="modal-detail">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Detail Penjualan</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-detail">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Nama</th>
                            <th>Harga</th>
                            <th>Jumlah</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody id="modal-detail-body">
                        @foreach ($detailPenjualan as $item)
                        <tr>
                            <td> {{$item->produk->nama_produk }}</td>
                            <td> Rp. {{ format_uang($item->produk->nama_produk) }}</td>
                            <td> {{ $item->jumlah }} item</td>
                            <td> {{ $item->subtotal }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>