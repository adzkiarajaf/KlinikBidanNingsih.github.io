@extends('layouts.master')

@section('title')
    Pembayaran Tunai
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Pembayaran Tunai</li>
@endsection
        
@section('content')
        @if(session('cart'))
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Pembayaran</h3>
                    </div>
                    <div class="box-body">
                        <div class="card-body box-profile">
                            <div class="text-center mb-3">
                                <i class="fa fa-check-square-o fa-3x text-success" aria-hidden="true"></i>
                            </div>
                            <br>
                            <div class="form-group text-center">
                                <label for="jumlahKembali" class="lead mt-2">Pembayaran dengan Tunai Berhasil</label>
                                <h3 id="jumlahKembaliLabel">Rp. {{ format_uang($kembalian) }}</h3>
                            </div>
                        </div>
                                <div class="box-footer">
                                    <button class="btn btn-block btn-primary" onclick="notaKecil('{{ route('penjualan.nota_kecil') }}', 'Nota Kecil')"> Print Struk</button>
                                    <button class="btn btn-block btn-primary" type="button" onclick="reset()" id="bayar">
                                        Kembali
                                    </button>
                                </div>
                                </div>
                                </div>
                                </div>
                                </div>
    @endif
@endsection


@push('scripts')
<script> 
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

    function reset() {
        // Lakukan tindakan untuk mereset semua transaksi di sini
        $.ajax({
            url: '{{ route('penjualan.remove_cart') }}',
            type: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function (data) {
                // Berhasil mereset, arahkan ke halaman awal
                window.location.href = '{{ route('penjualan.index') }}'; // Ganti dengan URL halaman awal Anda
            },
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Gagal mereset transaksi.'
                });
            }
        });
    }

    function notaKecil(url, title) {
        popupCenter(url, title, 625, 500);
    }
</script>
@endpush
