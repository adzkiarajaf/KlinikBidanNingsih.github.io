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

    .custom-button {
        border: 1px solid #3c8dbc;;
        padding: 5px 10px;
        margin-top: 10px;
        font-size: 14px;
        transition: background-color 0.3s, color 0.3s; /* Efek transisi perubahan warna */
    }

    .custom-button:hover {
        background-color: #3c8dbc;; /* Warna latar belakang biru saat dihover */
        color: white; /* Warna teks putih saat dihover */
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
                            <form action="{{ route('penjualan.processCheckout', ['id_penjualan' => $penjualanTerbaru->uuid]) }}" method="POST" id="checkout-form">
                                @csrf
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="metode_pembayaran" id="qris" value="qris"
                                        {{ $penjualanTerbaru->metode_pembayaran === 'qris' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="qris">QRIS</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="metode_pembayaran" id="tunai" value="tunai"
                                        {{ $penjualanTerbaru->metode_pembayaran === 'tunai' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="tunai">Tunai</label>
                                </div>
                                <input type="hidden" name="id_penjualan" value="{{ $penjualanTerbaru->id }}">
                            <p class="lead mt-2">Jumlah</p>
                            <h3>Rp. {{ format_uang($total) }}</h3>
                            <div class="form-group">
                                <label for="uangDiterima" class="lead mt-2">Uang diterima</label>
                                <input
                                    name="uangDiterima"
                                    type="number"
                                    class="form-control"
                                    id="uangDiterima"
                                    placeholder=""
                                    required="required"
                                    data-total="{{ $total }}">
                            </div>
                            <button type="submit" class="custom-button btn-xs" id='update-button'>Simpan Metode Pembayaran</button>
                            </form>
                        </div>
                        <div class="box-footer">
                            <button
                                class="btn btn-block btn-primary"
                                type="button"
                                id="checkout-live-button"
                                onclick="uangPas()"
                                disabled>
                                <i class="fa fa-money"></i>
                                Uang Pas
                            </button>
                            <button class="btn btn-block btn-primary" type="submit" id="bayar">
                                <i class="fa fa-money"></i>
                                Bayar
                            </button>
                        </div>
                        </div>
                        </div>
                        </div>
                        </div>
@endif
@endsection

@includeIf('penjualan.modalqris')
@includeIf('penjualan.modaltunai')

@push('scripts')
<script>
    $('#checkout-form').submit(function(event) {
        event.preventDefault();

        var formData = $(this).serialize(); // Mengambil data formulir

        $.ajax({
            url: $(this).attr('action'), // Mengambil URL aksi dari formulir
            type: $(this).attr('method'), // Mengambil metode aksi dari formulir
            data: formData,
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Metode Pembayaran Berhasil Di Simpan',
                    text: 'Silahkan Lanjutkan pembayaran.',
                    showConfirmButton: false, // Tidak menampilkan tombol konfirmasi
                    timer: 3000, // Menampilkan selama 5 detik
                });

                // Lakukan tindakan lain, jika perlu
            },
            error: function(xhr, status, error) {
                console.error(error);
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Terjadi kesalahan saat melakukan checkout.',
                    showConfirmButton: false, // Tidak menampilkan tombol konfirmasi
                    timer: 3000, // Menampilkan selama 5 detik
                });
            }
        });
    });

    function setUangPasStatus() {
    // Dapatkan elemen input uangDiterima
    const uangDiterimaInput = document.getElementById('uangDiterima');

    // Dapatkan elemen input metode_pembayaran
    const metodePembayaranInput = document.getElementsByName('metode_pembayaran');

    // Dapatkan tombol "Uang Pas"
    const uangPasButton = document.getElementById('checkout-live-button');

    // Dapatkan nilai total pembayaran dari atribut data-total pada elemen uangDiterima
    const totalPembayaran = parseFloat(uangDiterimaInput.dataset.total);

    // Dapatkan nilai metode_pembayaran yang dipilih
    let metodePembayaran = '';
    metodePembayaranInput.forEach(input => {
        if (input.checked) {
            metodePembayaran = input.value;
        }
    });

    // Jika metode_pembayaran adalah 'qris', aktifkan tombol "Uang Pas" dan set nilai uangDiterima sama dengan total harga
    // Jika metode_pembayaran adalah 'tunai', biarkan tombol "Uang Pas" non-aktif dan biarkan nilai uangDiterima kosong
    if (metodePembayaran === 'qris') {
        uangPasButton.disabled = true;
        uangDiterimaInput.disabled = true; // Menonaktifkan input uangDiterima
    } else {
        uangPasButton.disabled = false;
        uangDiterimaInput.value = '';
        uangDiterimaInput.disabled = false; // Mengaktifkan kembali input uangDiterima
    }
}

    // Tambahkan event listener untuk setiap elemen metode_pembayaran
    const metodePembayaranInput = document.getElementsByName('metode_pembayaran');
    metodePembayaranInput.forEach(input => {
        input.addEventListener('change', setUangPasStatus);
    });

    // Jalankan fungsi setUangPasStatus saat halaman dimuat untuk pertama kali
    setUangPasStatus();

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

    document.addEventListener('DOMContentLoaded', function () {
    const bayarButton = document.getElementById('bayar');
    bayarButton.addEventListener('click', handleBayarClick);
    });

    function handleBayarClick(event) {
        event.preventDefault(); // Menghentikan aksi bawaan tombol submit

        const paymentMethod = document.querySelector('input[name="metode_pembayaran"]:checked').value;
        if (paymentMethod === 'qris') {
            $('#qrisModal').modal('show');
        } else if (paymentMethod === 'tunai') {
            // Navigasi ke halaman terakhir
            window.location.href = "{{ route('penjualan.tunai') }}";
        }
    }
</script>
@endpush