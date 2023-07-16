@extends('layouts.master')

@section('title')
    Pembayaran
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Pembayaran</li>
@endsection

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
                    <img
                        src="{{ asset('AdminLTE-2/dist/img/qris.png') }}"
                        alt="Visa"
                        style="width: 20%; height:20%; margin-right: 10%">
                        <img
                            src="{{ asset('AdminLTE-2/dist/img/dmpt.png') }}"
                            alt="Visa"
                            style="width: 10%; height:20%"></div>
                        <div class="col-xs-6">
                            <p class="lead mt-2">Jumlah</p>
                            <h3>Rp.
                                {{ format_uang($totalHargaTerbaru) }}</h3>
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
                            <button class="btn btn-block btn-primary" type="submit" onclick="bayar()">
                                <i class="fa fa-money"></i>
                                Bayar</button>
                        </div>
                        <div class="box-footer">
                            <button class="btn btn-warning btn-flat" onclick="notaKecil('{{ route('penjualan.nota_kecil') }}', 'Nota Kecil')">Cetak Ulang Nota</button>
                        </div>
                    </div>
                </div>
            </div>
@endif
@endsection

@push('scripts')
<script>
    // tambahkan untuk delete cookie innerHeight terlebih dahulu
    document.cookie = "innerHeight=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
    
    function notaKecil(url, title) {
        popupCenter(url, title, 625, 500);
    }


    function uangPas() {
        var uangDiterima = document.getElementById('uangDiterima');
        var totalHarga = {{ $totalHargaTerbaru }};
        uangDiterima.value = totalHarga;
    }

    function bayar() {
        var uangDiterima = document.getElementById('uangDiterima').value;
        var totalHarga = {{ $totalHargaTerbaru }};
        if (uangDiterima >= totalHarga) {
            // Proses pembayaran selesai
            alert('Pembayaran selesai!');
            // Tampilkan struk atau lakukan aksi lainnya
        } else {
            // Jika jumlah bayar kurang dari total harga
            alert('Jumlah bayar tidak mencukupi!');
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
</script>
@endpush