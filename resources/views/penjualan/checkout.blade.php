@extends('layouts.master')

@section('title')
    Pembayaran
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Pembayaran</li>
@endsection

<style>
    /* Gambar akan diperbesar saat kursor diarahkan ke atas */
    .hoverable-image:hover {
        .hoverable-image {
        transition: transform 0.3s;
    }

    .hoverable-image:hover {
    transform: scale(1.1);
    }

    /* Untuk gambar responsif */
    img {
    max-width: 100%;
    height: auto;
    }
    }
</style>

@section('content')
@if(session('cart'))
<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Pembayaran</h3>
            </div>
            <div class="box-body">
                <div class="col-xs-6">
                    <p class="lead">Metode Pembayaran</p>
                    <a href="#" onclick="showModal()">
                        <img
                            src="{{ asset('AdminLTE-2/dist/img/qris.png') }}"
                            alt="Visa"
                            class="hoverable-image"
                            style="width: 20%; height: 10%; margin-right: 10%"
                        >
                    </a>
                    <a href="">
                        <img
                            src="{{ asset('AdminLTE-2/dist/img/dmpt.png') }}"
                            alt="Visa"
                            class="hoverable-image"
                            style="width: 10%; height: 10%"
                        >
                    </a>
                        </div>
                        <div class="col-xs-6">
                            <p class="lead mt-2">Jumlah</p>
                            <h3>Rp.
                                {{ format_uang($total) }}</h3>
                            <div class="form-group">
                                <label for="uangDiterima" class="lead mt-2">Uang diterima</label>
                                <input
                                    type="number"
                                    class="form-control"
                                    id="uangDiterima"
                                    placeholder=""
                                    required="required"></div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button
                                class="btn btn-block btn-primary"
                                type="submit"
                                id="checkout-live-button"
                                onclick="uangPas()">
                                <i class="fa fa-money"></i>
                                Uang Pas</button>
                            <button class="btn btn-block btn-primary" type="submit" onclick="bayar()" id="bayar">
                                <i class="fa fa-money"></i>
                                Bayar</button>
                        </div>
                        <div class="box-footer">
                            <button class="btn btn-warning btn-flat" onclick="notaKecil('{{ route('penjualan.nota_kecil') }}', 'Nota Kecil')"> Print Struk</button>
                        </div>
                    </div>
                </div>
            </div>
@endif
@endsection

@includeIf('penjualan.bayar')

@push('scripts')
<script>
    // tambahkan untuk delete cookie innerHeight terlebih dahulu
    document.cookie = "innerHeight=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
    
    function notaKecil(url, title) {
        popupCenter(url, title, 625, 500);
    }

    function uangPas() {
        var uangDiterima = document.getElementById('uangDiterima');
        var totalHarga = {{ $total }};
        uangDiterima.value = totalHarga;
    }

    function bayar() {
        var uangDiterima = document.getElementById('uangDiterima').value;
        var totalHarga = {{ $total}};
        var kembalian = uangDiterima - totalHarga;

        if (uangDiterima == totalHarga) {
            Swal.fire({
                title: 'Pembayaran selesai!',
                text: 'Terima kasih telah melakukan pembayaran.',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                
            });
        } else if (uangDiterima > totalHarga) {
            Swal.fire({
                    title: 'Kembalian',
                    text: 'Kembalian yang Anda terima: ' + kembalian,
                    icon: 'info',
                    timer: 2000,
                    showConfirmButton: false
            }).then(() => {
                Swal.fire({
                    title: 'Pembayaran selesai!',
                    text: 'Terima kasih telah melakukan pembayaran.',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: 'OK'
                });
                
            });
        } else {
            Swal.fire({
                title: 'Pembayaran kurang!',
                text: 'Jumlah pembayaran Anda kurang dari total harga.',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
        }
}


    function popupCenter(url, title, w, h) {
        const dualScreenLeft = window.screenLeft !==  undefined ? window.screenLeft : window.screenX;
        const dualScreenTop  = window.screenTop  !==  undefined ? window.screenTop  : window.screenY;

        const width  = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
        const height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

        const systemZoom = width / window.screen.availWidth;
        const left       = (width - w) / 2 / systemZoom + dualScreenLeft
        const top        = (height - h) / 2 / systemZoom + dualScreenTop
        const newWindow  = window.open(url, title, 
        `
            scrollbars=yes,
            width  = ${w / systemZoom}, 
            height = ${h / systemZoom}, 
            top    = ${top}, 
            left   = ${left}
        `
        );

        if (window.focus) newWindow.focus();
    }

    function showModal() {
    $('#qrisModal').modal('show');
    }
</script>
@endpush