@extends('layouts.master')

@section('title')
    Daftar Produk
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Daftar Produk</li>
@endsection

@section('content')
@foreach ($produk as $index => $item)
<div class="row align-items-stretch">
    <div class="col-mb-3">
        <div class="box box-widget widget-user-2">
            <div class="widget-user-header">
                <div class="widget-user-image">
                    <img src="{{ asset('img/' . $item->path_foto) }}" class="rounded float-start" style="width: 12%; margin-right: 10px;">
                </div>
                <div class="widget-user-details">
                    <h3 class="widget-user-username">{{ $item->nama_produk }}</h3>
                    <h5 class="widget-user-desc">Harga: Rp.{{ format_uang($item['harga_jual'])}}</h5>
                </div>
            </div>
            <div class="box-footer">
                <div class="d-flex justify-content-end">
                    <div class="col-5 ps-0">
                        @if ($item->stok > 0)
                            <a href="{{ route('penjualan.add_to_cart', $item->id_produk) }}" class="btn btn-primary" id="tambahbtn_{{ $index }}" onclick="hideButton({{ $index }})">Tambah</a>
                        @else
                            <button class="btn btn-primary" disabled><i class="fa fa-ban" aria-hidden="true"></i> Stok Habis</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach

@if (session()->has('success'))
<a href="{{ route('penjualan.cart') }}" type="button" class="btn btn-primary btn-block btn-flat"><i class="fa fa-shopping-cart mr-3" aria-hidden="true"></i> {{ count((array) session('cart')) }} Item</a>
@endif

@includeIf('produk.form')
@endsection

@push('scripts')
<script>
    function hideButton(index) {
        var button = document.getElementById("tambahbtn_" + index);
        button.classList.add("hidden");
        button.removeAttribute("onclick"); // Menghapus atribut onclick agar tombol tidak bisa diklik lagi
    }
</script>
@endpush
