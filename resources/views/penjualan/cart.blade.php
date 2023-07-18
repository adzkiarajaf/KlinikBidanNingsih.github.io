@extends('layouts.master')

@section('title')
    Daftar Produk
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Daftar Produk</li>
@endsection

<style>
    .tfoot-spacing td {
        padding-top: 10px;
        padding-bottom: 10px;
    }
</style>

@section('content') 
<div class="mb-2">
    <a href="{{ route('penjualan.index') }}" type="button" class="btn btn-success btn-flat btn-xs mt-2">
        <i class="fa fa-reply-all" aria-hidden="true"></i> Tambah Produk
    </a>
</div>
<form action="{{ route('penjualan.store') }}"></form>
<table id="cart" class="table table-hover table-condensed">
    <thead>
        <tr>
            <th style="width:50%">Produk</th>
            <th style="width:10%">Harga</th>
            <th style="width:8%">Quantity</th>
            <th style="width:22%" class="text-center">Subtotal</th>
            <th style="width:10%"></th>
        </tr>
    </thead>
    <tbody>
        @php
$total = 0;
@endphp
<tbody>
    @if(session('cart'))
        @foreach(session('cart') as $produk => $item)
            @php
            $subtotal = $item['harga_jual'] * $item['quantity'];
            $total += $subtotal;
            @endphp
            <tr data-id="{{ $produk }}">
                <td data-th="Product">
                    <div class="row">
                        <div class="col-sm-3 hidden-xs">
                            <img src="{{ asset('img/' . $item['path_foto']) }}" width="100" height="100" class="img-responsive" />
                        </div>
                        <div class="col-sm-9">
                            <h5 class="nomargin">{{ $item['nama_produk'] }}</h5>
                        </div>
                    </div>
                </td>
                <td data-th="Harga">Rp. {{ format_uang($item['harga_jual']) }}</td>
                <td data-th="quantity">
                    <input type="number" value="{{ $item['quantity'] }}" class="form-control quantity cart_update" min="1" data-id="{{ $produk }}">
                </td>
                <td data-th="subtotal" class="text-center" data-subtotal="{{ $subtotal }}">Rp. {{ format_uang($subtotal) }}</td>
                <td class="actions" data-th="">
                    <button class="btn btn-danger btn-sm cart_remove"><i class="fa fa-trash-o"></i> Delete</button>
                </td>
            </tr>
        @endforeach
    @endif
</tbody>
<tfoot>
    <tr>
        <td colspan="3" class="tfoot-spacing"></td>
        <td class="text-center">Total</td>
        <td class="text-center">Rp. {{ format_uang($total) }}</td>
    </tr>
</tfoot>
</table>
<div>
    <form action="{{ route('penjualan.store') }}" method="POST">
        @csrf
        <input type="hidden" name="total" value="{{ $total }}">
        <button class="btn btn-block btn-primary" type="submit" id="checkout-live-button"><i class="fa fa-money"></i> Pembayaran</button>
    </form>
</div>

    
@endsection

@push('scripts')
<script>
    $(".cart_update").change(function (e) {
    e.preventDefault();
    var ele = $(this);
    $.ajax({

        url: '{{ route('update_cart') }}',
        method: "patch",
        data: {
            _token: '{{ csrf_token() }}',
            id: ele
                .parents("tr")
                .attr("data-id"),
            quantity: ele
                .parents("tr")
                .find(".quantity")
                .val()
        },
        success: function (response) {
            window
                .location
                .reload();
        }
    });
});

    

$(".cart_remove").click(function (e) {
    e.preventDefault();

    var ele = $(this);

    if (confirm("Yakin ingin menghapus produk ini?")) {
        $.ajax({
            url: '{{ route('remove_from_cart') }}',
            method: "patch",
            data: {
                _token: '{{ csrf_token() }}',
                id: ele
                    .parents("tr")
                    .attr("data-id")
            },
            success: function (response) {
                window
                    .location
                    .reload();
            }
        });
    }
});

// Ambil elemen tombol menggunakan id atau selektor yang sesuai
const btnTambahPenjualan = document.getElementById('btnTambahPenjualan');

// Tambahkan event listener untuk klik
btnTambahPenjualan.addEventListener('click', function() {
    // Ambil nilai id_penjualan dari atribut data
    const idPenjualan = btnTambahPenjualan.getAttribute('data-id-penjualan');

    // Lakukan aksi yang diinginkan dengan id_penjualan
    // Misalnya, kirim data ke backend atau lakukan pengoperasian lain

    // Contoh tindakan: Tampilkan pesan dengan id_penjualan
    alert('Id Penjualan yang ditambahkan: ' + idPenjualan);
});
</script>
@endpush
