@extends('layouts.master')

@section('title')
    Kategori
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Produk</li>
@endsection

@section('content')
<div>
    <form>
        <fieldset disabled>
            <img src="{{ asset('img/' . $produk->path_foto) }}" class="rounded float-start mb-3" style="width: 15%">
            <br>
            <div class="mb-3">
                <br>
                <label for="disabledTextInput" class="form-label">Nama Produk</label>
                <input type="text" id="disabledTextInput" class="form-control" value="{{ $produk->nama_produk }}" readonly>
            </div>
            <br>
            <div class="mb-3">
                <label for="disabledTextInput" class="form-label">Jumlah Stok</label>
                <input type="text" id="disabledTextInput" class="form-control" value="{{ $produk->stok }}" readonly>
            </div>
            <br>
            <div class="mb-3">
                <label for="disabledTextInput" class="form-label">Kategori</label>
                <input type="text" id="disabledTextInput" class="form-control" value="@if($produk->id_kategori == 1) Jasa Pelayanan @elseif($produk->id_kategori == 2) Vitamin @elseif($produk->id_kategori == 21) Tablet @elseif($produk->id_kategori == 22) Sirup @endif" readonly>
            </div> 
            <br>           
            <div class="mb-3">
                <label for="disabledTextInput" class="form-label">Harga Jual</label>
                <input type="text" id="disabledTextInput" class="form-control" value="{{ $produk->harga_jual }} -/pcs" readonly>
            </div>
        </fieldset>
        <br>
            <div class="mb-3">
                <label for="disabledTextInput" class="form-label">Masukan Jumlah</label>
                <input type="text" id="disabledTextInput" class="form-control" placeholder="Jumlah tidak boleh melebih stok" >
            </div> 
    </form>
</div>
<br>
<div class="mb-3">
    <button onclick="editForm('{{ route('produk.update', $produk->id_produk) }}')" class="btn btn-primary btn-block"><i class="fa fa-shopping-cart" aria-hidden="true"> Masukan Keranjang</button>
    
</div>
@endsection